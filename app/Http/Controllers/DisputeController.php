<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dispute\CreateDisputeRequest;
use App\Models\Dispute;
use App\Models\Transaction;
use App\Services\DisputeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    public function __construct(
        private DisputeService $disputeService
    ) {}

    public function index()
    {
        $user = Auth::user();

        $disputes = Dispute::whereHas('transaction', function ($q) use ($user) {
            $q->where('seller_id', $user->id)
              ->orWhere('buyer_id', $user->id);
        })
        ->with(['transaction', 'initiator'])
        ->latest()
        ->paginate(15);

        return view('disputes.index', compact('disputes'));
    }

    public function create(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        if (!$transaction->canOpenDispute()) {
            return back()->with('error', 'Vous ne pouvez pas ouvrir de litige sur cette transaction.');
        }

        if ($transaction->dispute) {
            return redirect()->route('disputes.show', $transaction->dispute)
                ->with('info', 'Un litige existe deja pour cette transaction.');
        }

        return view('disputes.create', compact('transaction'));
    }

    public function store(CreateDisputeRequest $request)
    {
        $transaction = Transaction::findOrFail($request->input('transaction_id'));

        $this->authorize('view', $transaction);

        if (!$transaction->canOpenDispute()) {
            return back()->with('error', 'Cette transaction ne peut plus faire l'objet d'un litige.');
        }

        $dispute = $this->disputeService->open(
            $transaction,
            $request->input('reason'),
            $request->input('description')
        );

        return redirect()->route('disputes.show', $dispute)
            ->with('success', 'Litige ouvert avec succes.');
    }

    public function show(Dispute $dispute)
    {
        $this->authorizeAccess($dispute);

        $dispute->load(['transaction', 'messages.user', 'initiator']);

        return view('disputes.show', compact('dispute'));
    }

    public function reply(Request $request, Dispute $dispute)
    {
        $this->authorizeAccess($dispute);

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $this->disputeService->reply($dispute, $validated['message']);

        return redirect()->route('disputes.show', $dispute)
            ->with('success', 'Reponse envoyee.');
    }

    private function authorizeAccess(Dispute $dispute): void
    {
        $user = Auth::user();
        $transaction = $dispute->transaction;

        if ($transaction->seller_id !== $user->id
            && $transaction->buyer_id !== $user->id
            && !$user->isAdmin()) {
            abort(403, 'Acces non autorise.');
        }
    }
}
