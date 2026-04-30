<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Dispute;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::where('kyc_status', 'verified')->count(),
            'pending_kyc' => User::where('kyc_status', 'pending')->count(),
            'total_transactions' => Transaction::count(),
            'active_transactions' => Transaction::active()->count(),
            'completed_transactions' => Transaction::completed()->count(),
            'total_volume' => Transaction::completed()->sum('amount'),
            'open_disputes' => Dispute::where('status', 'open')->count(),
            'total_commissions' => Transaction::completed()->sum('commission_amount'),
        ];

        $recentTransactions = Transaction::latest()->limit(10)->get();
        $recentUsers = User::latest()->limit(10)->get();
        $openDisputes = Dispute::where('status', 'open')->with('transaction')->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'recentUsers', 'openDisputes'));
    }
}
