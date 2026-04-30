<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\ActivityLog;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::where('seller_id', $user->id)
            ->orWhere('buyer_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'product_description' => ['nullable', 'string', 'max:1000'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'seller_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $transaction = Transaction::create([
            ...$validated,
            'seller_id' => Auth::id(),
            'status' => 'pending',
        ]);

        ActivityLog::log('transaction_created', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction creee. Partagez ce lien avec l\'acheteur.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);
        return view('transactions.show', compact('transaction'));
    }

    public function pay(Transaction $transaction)
    {
        if ($transaction->buyer_id && $transaction->buyer_id !== Auth::id()) {
            abort(403);
        }

        if (!$transaction->isPending()) {
            return back()->with('error', 'Cette transaction ne peut plus etre payee.');
        }

        $transaction->update(['buyer_id' => Auth::id()]);

        return redirect()->route('payment.show', $transaction);
    }

    public function confirmDelivery(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if (!$transaction->isDelivered()) {
            return back()->with('error', 'La livraison n\'est pas confirmee.');
        }

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'Seul l\'acheteur peut confirmer la reception.');
        }

        $transaction->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Liberer fonds escrow
        if ($transaction->escrow) {
            $transaction->escrow->update([
                'status' => 'released',
                'released_at' => now(),
            ]);
        }

        // Notifier vendeur
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'transaction_completed',
            'title' => 'Paiement libere',
            'message' => "Le paiement pour {$transaction->product_name} a ete libere.",
            'link' => route('transactions.show', $transaction),
        ]);

        // Email via Brevo
        \App\Services\BrevoService::sendPaymentReleased($transaction->seller, $transaction);

        ActivityLog::log('delivery_confirmed', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Reception confirmee. Le vendeur a ete paye.');
    }

    public function cancel(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if (!$transaction->canBeCancelled()) {
            return back()->with('error', 'Cette transaction ne peut plus etre annulee.');
        }

        $transaction->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        ActivityLog::log('transaction_cancelled', $transaction, Auth::user());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction annulee.');
    }

    private function authorizeAccess(Transaction $transaction)
    {
        $user = Auth::user();
        if ($transaction->seller_id !== $user->id && $transaction->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }
    }
}
