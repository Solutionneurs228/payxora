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
    protected DisputeService $disputeService;

    public function __construct(DisputeService $disputeService)
    {
        $this->disputeService = $disputeService;
    }

    /**
     * Liste les litiges de l'utilisateur.
     */
    public function index()
    {
        $user = Auth::user();

        $disputes = Dispute::whereHas('transaction', function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhere('seller_id', $user->id);
        })
        ->with(['transaction'])
        ->latest()
        ->paginate(10);

        return view('disputes.index', compact('disputes'));
    }

    /**
     * Affiche le formulaire de creation de litige.
     */
    public function create(Transaction $transaction)
    {
        $this->authorize('createDispute', $transaction);

        return view('disputes.create', compact('transaction'));
    }

    /**
     * Cree un nouveau litige.
     */
    public function store(CreateDisputeRequest $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);

        $this->authorize('createDispute', $transaction);

        $dispute = $this->disputeService->createDispute(
            $transaction,
            Auth::user(),
            $request->validated()
        );

        return redirect()->route('disputes.show', $dispute)
            ->with('success', 'Votre litige a ete ouvert. Un mediateur va examiner votre cas.');
    }

    /**
     * Affiche un litige.
     */
    public function show(Dispute $dispute)
    {
        $this->authorize('view', $dispute);

        $dispute->load(['transaction', 'messages.user', 'initiator']);

        return view('disputes.show', compact('dispute'));
    }

    /**
     * Repondre a un litige.
     */
    public function reply(Request $request, Dispute $dispute)
    {
        $this->authorize('reply', $dispute);

        $request->validate([
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $this->disputeService->addMessage($dispute, Auth::user(), $request->message);

        return back()->with('success', 'Votre message a ete ajoute.');
    }
}
