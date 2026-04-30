<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Dispute;
use App\Models\DisputeMessage;
use App\Models\Notification;
use App\Models\ActivityLog;

class DisputeController extends Controller
{
    public function index()
    {
        $disputes = Dispute::whereHas('transaction', function($q) {
            $q->where('seller_id', Auth::id())->orWhere('buyer_id', Auth::id());
        })->latest()->paginate(15);

        return view('disputes.index', compact('disputes'));
    }

    public function create(Transaction $transaction)
    {
        if (!$transaction->canOpenDispute()) {
            return back()->with('error', 'Delai de litige depasse ou statut invalide.');
        }

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'Seul l\'acheteur peut ouvrir un litige.');
        }

        return view('disputes.create', compact('transaction'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => ['required', 'exists:transactions,id'],
            'reason' => ['required', 'in:not_received,not_as_described,damaged,wrong_item,seller_no_ship,other'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $transaction = Transaction::findOrFail($validated['transaction_id']);

        if (!$transaction->canOpenDispute()) {
            return back()->with('error', 'Impossible d\'ouvrir un litige sur cette transaction.');
        }

        $evidence = null;
        if ($request->hasFile('evidence')) {
            $evidence = $request->file('evidence')->store('disputes/evidence', 'private');
        }

        $dispute = Dispute::create([
            'transaction_id' => $transaction->id,
            'opened_by' => Auth::id(),
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'evidence' => $evidence,
            'status' => 'open',
        ]);

        $transaction->update(['status' => 'disputed']);

        // Notifier admin
        Notification::create([
            'user_id' => 1,
            'type' => 'new_dispute',
            'title' => 'Nouveau litige',
            'message' => "Litige ouvert sur transaction #{$transaction->reference}",
            'link' => route('admin.disputes.show', $dispute),
        ]);

        // Notifier vendeur
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'dispute_opened',
            'title' => 'Litige ouvert',
            'message' => "Un litige a ete ouvert sur {$transaction->product_name}.",
            'link' => route('disputes.show', $dispute),
        ]);

        ActivityLog::log('dispute_opened', $dispute, Auth::user());

        return redirect()->route('disputes.show', $dispute)
            ->with('success', 'Litige ouvert. Notre equipe va examiner votre cas.');
    }

    public function show(Dispute $dispute)
    {
        $transaction = $dispute->transaction;
        $user = Auth::user();

        if ($transaction->seller_id !== $user->id && $transaction->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        return view('disputes.show', compact('dispute', 'transaction'));
    }

    public function reply(Request $request, Dispute $dispute)
    {
        $transaction = $dispute->transaction;
        $user = Auth::user();

        if ($transaction->seller_id !== $user->id && $transaction->buyer_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:2000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $attachment = null;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment')->store('disputes/messages', 'private');
        }

        DisputeMessage::create([
            'dispute_id' => $dispute->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'attachment' => $attachment,
        ]);

        return back()->with('success', 'Message ajoute.');
    }
}
