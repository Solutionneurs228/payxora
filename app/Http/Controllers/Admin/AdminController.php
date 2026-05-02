<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DisputeStatus;
use App\Enums\KycStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Transaction;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'pending_kyc' => User::whereHas('kycProfile', function ($q) {
                $q->where('status', KycStatus::PENDING);
            })->count(),
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', TransactionStatus::PENDING_PAYMENT)->count(),
            'active_escrow' => Transaction::whereIn('status', [
                TransactionStatus::FUNDED,
                TransactionStatus::SHIPPED,
                TransactionStatus::DELIVERED,
            ])->count(),
            'completed_transactions' => Transaction::where('status', TransactionStatus::COMPLETED)->count(),
            'open_disputes' => Dispute::where('status', DisputeStatus::OPEN)->count(),
            'total_volume' => Transaction::where('status', TransactionStatus::COMPLETED)->sum('amount'),
        ];

        $recent_transactions = Transaction::with(['seller', 'buyer'])
            ->latest()
            ->limit(10)
            ->get();

        $recent_disputes = Dispute::with(['transaction', 'initiator'])
            ->where('status', DisputeStatus::OPEN)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_transactions', 'recent_disputes'));
    }
}
