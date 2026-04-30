<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;

class TransactionAdminController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['seller', 'buyer', 'payment', 'escrow', 'dispute']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function releaseFunds(Request $request, Transaction $transaction)
    {
        if (!$transaction->isDelivered()) {
            return back()->with('error', 'La transaction doit etre livree.');
        }

        $transaction->update(['status' => 'completed', 'completed_at' => now()]);

        if ($transaction->escrow) {
            $transaction->escrow->update(['status' => 'released', 'released_at' => now()]);
        }

        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'funds_released',
            'title' => 'Fonds liberes',
            'message' => "Les fonds pour {$transaction->product_name} ont ete liberes.",
            'link' => route('transactions.show', $transaction),
        ]);

        return back()->with('success', 'Fonds liberes.');
    }

    public function refund(Request $request, Transaction $transaction)
    {
        if ($transaction->isCompleted()) {
            return back()->with('error', 'Transaction deja terminee.');
        }

        $transaction->update(['status' => 'refunded']);

        if ($transaction->escrow) {
            $transaction->escrow->update(['status' => 'refunded', 'refunded_at' => now()]);
        }

        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'refund_processed',
            'title' => 'Remboursement effectue',
            'message' => "Vous avez ete rembourse pour {$transaction->product_name}.",
            'link' => route('transactions.show', $transaction),
        ]);

        return back()->with('success', 'Remboursement effectue.');
    }
}
