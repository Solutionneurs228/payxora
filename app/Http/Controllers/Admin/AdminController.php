<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Dispute;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'verified_users' => User::where('kyc_status', 'verified')->count(),
            'pending_kyc' => User::where('kyc_status', 'pending')->count(),
            'total_transactions' => Transaction::count(),
            'active_transactions' => Transaction::whereIn('status', ['pending', 'paid', 'shipped', 'delivered'])->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'total_volume' => Transaction::where('status', 'completed')->sum('amount') ?? 0,
            'open_disputes' => Dispute::where('status', 'open')->count(),
            'total_commissions' => Transaction::where('status', 'completed')->sum('commission_amount') ?? 0,
        ];

        $recent_transactions = Transaction::latest()->limit(10)->get();
        $recent_users = User::latest()->limit(10)->get();
        $recent_disputes = Dispute::where('status', 'open')->with('transaction')->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recent_transactions', 'recent_users', 'recent_disputes'));
    }
}
