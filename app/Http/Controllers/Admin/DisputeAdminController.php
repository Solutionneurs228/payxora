<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeAdminController extends Controller
{
    public function index()
    {
        $disputes = Dispute::with(['transaction', 'opener'])->latest()->paginate(20);
        return view('admin.disputes.index', compact('disputes'));
    }

    public function show(Dispute $dispute)
    {
        $dispute->load(['transaction.seller', 'transaction.buyer', 'opener', 'messages.user']);
        return view('admin.disputes.show', compact('dispute'));
    }

    public function arbitrate(Request $request, Dispute $dispute)
    {
        $validated = $request->validate([
            'resolution' => ['required', 'in:buyer,seller'],
            'notes' => ['required', 'string', 'min:10'],
        ]);

        $transaction = $dispute->transaction;

        if ($validated['resolution'] === 'buyer') {
            $transaction->update(['status' => 'refunded']);
            if ($transaction->escrow) {
                $transaction->escrow->update(['status' => 'refunded', 'refunded_at' => now()]);
            }
            $winnerId = $transaction->buyer_id;
            $message = 'Le litige a ete resolu en votre faveur. Remboursement effectue.';
        } else {
            $transaction->update(['status' => 'completed', 'completed_at' => now()]);
            if ($transaction->escrow) {
                $transaction->escrow->update(['status' => 'released', 'released_at' => now()]);
            }
            $winnerId = $transaction->seller_id;
            $message = 'Le litige a ete resolu en votre faveur. Paiement libere.';
        }

        $dispute->update([
            'status' => $validated['resolution'] === 'buyer' ? 'resolved_buyer' : 'resolved_seller',
            'resolved_by' => Auth::id(),
            'resolution_notes' => $validated['notes'],
            'resolved_at' => now(),
        ]);

        Notification::create([
            'user_id' => $winnerId,
            'type' => 'dispute_resolved',
            'title' => 'Litige resolu',
            'message' => $message,
            'link' => route('disputes.show', $dispute),
        ]);

        return back()->with('success', 'Arbitrage effectue.');
    }

    public function close(Request $request, Dispute $dispute)
    {
        $dispute->update([
            'status' => 'closed',
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Litige ferme.');
    }
}
