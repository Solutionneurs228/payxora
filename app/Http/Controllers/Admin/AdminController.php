<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Transaction;
use App\Models\User;
use App\Enums\TransactionStatus;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // ─── Utilisateurs ─────────────────────────────────────────
        $stats['total_users'] = User::count();
        $stats['verified_users'] = User::where('kyc_status', 'verified')->count();
        $stats['pending_kyc'] = User::where('kyc_status', 'pending')->count();

        // ─── Transactions ───────────────────────────────────────────
        $stats['total_transactions'] = Transaction::count();

        // Actives = FUNDED + SHIPPED + DELIVERED (fonds en séquestre)
        $stats['active_transactions'] = Transaction::whereIn('status', [
            TransactionStatus::FUNDED->value,
            TransactionStatus::SHIPPED->value,
            TransactionStatus::DELIVERED->value,
        ])->count();

        // Terminées = COMPLETED uniquement
        $stats['completed_transactions'] = Transaction::where('status', TransactionStatus::COMPLETED->value)->count();

        // ─── FINANCES ─────────────────────────────────────────────
        // Volume total des transactions terminées
        $stats['total_volume'] = Transaction::where('status', TransactionStatus::COMPLETED->value)->sum('amount');

        // Commissions perçues (3% sur chaque transaction terminée)
        $stats['total_commissions'] = Transaction::where('status', TransactionStatus::COMPLETED->value)->sum('commission_amount');

        // Fonds en séquestre (FUNDED + SHIPPED + DELIVERED)
        $stats['escrow_balance'] = Transaction::whereIn('status', [
            TransactionStatus::FUNDED->value,
            TransactionStatus::SHIPPED->value,
            TransactionStatus::DELIVERED->value,
        ])->sum('amount');

        // Fonds libérés (versés aux vendeurs)
        $stats['released_funds'] = Transaction::where('status', TransactionStatus::COMPLETED->value)->sum('net_amount');

        // Paiements effectués (total encaissé)
        $stats['total_payments'] = Transaction::whereIn('status', [
            TransactionStatus::FUNDED->value,
            TransactionStatus::SHIPPED->value,
            TransactionStatus::DELIVERED->value,
            TransactionStatus::COMPLETED->value,
        ])->sum('amount');

        // Revenus plateforme = commissions
        $stats['platform_revenue'] = $stats['total_commissions'];

        // ─── Litiges ──────────────────────────────────────────────
        $stats['open_disputes'] = Dispute::where('status', 'open')->count();

        // ─── Données récentes ───────────────────────────────────
        $recentTransactions = Transaction::with(['seller', 'buyer'])->latest()->limit(10)->get();
        $recentUsers = User::latest()->limit(10)->get();
        $openDisputes = Dispute::where('status', 'open')->with('transaction')->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'recentUsers', 'openDisputes'));
    }
}
