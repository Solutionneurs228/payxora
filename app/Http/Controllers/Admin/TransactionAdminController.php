<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Http\Request;

class TransactionAdminController extends Controller
{
    public function __construct(
        private EscrowService $escrowService
    ) {}

    public function index()
    {
        $transactions = Transaction::with(['seller', 'buyer', 'payment'])
            ->latest()
            ->paginate(25);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['seller', 'buyer', 'payment', 'escrow', 'dispute', 'logs']);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function releaseFunds(Transaction $transaction)
    {
        if (!$transaction->isDelivered()) {
            return back()->with('error', 'La transaction doit etre livree pour liberer les fonds.');
        }

        $success = $this->escrowService->complete($transaction);

        if (!$success) {
            return back()->with('error', 'Impossible de liberer les fonds.');
        }

        return redirect()->route('admin.transactions.show', $transaction)
            ->with('success', 'Fonds liberes au vendeur.');
    }

    public function refund(Transaction $transaction)
    {
        if ($transaction->isCompleted() || $transaction->isRefunded() || $transaction->isCancelled()) {
            return back()->with('error', 'Cette transaction ne peut plus etre remboursee.');
        }

        $success = $this->escrowService->refund($transaction);

        if (!$success) {
            return back()->with('error', 'Impossible de rembourser.');
        }

        return redirect()->route('admin.transactions.show', $transaction)
            ->with('success', 'Acheteur rembourse.');
    }
}
