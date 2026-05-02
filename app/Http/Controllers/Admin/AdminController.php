<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Dispute;
use App\Models\KycProfile;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord admin.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_transactions' => Transaction::count(),
            'open_disputes' => Dispute::where('status', 'open')->orWhere('status', 'mediation')->count(),
            'pending_kyc' => KycProfile::where('status', 'pending')->count(),
            'total_volume' => Transaction::where('status', 'completed')->sum('amount'),
            'monthly_transactions' => Transaction::whereMonth('created_at', now()->month)->count(),
        ];

        $recentTransactions = Transaction::with(['buyer', 'seller'])
            ->latest()
            ->limit(10)
            ->get();

        $recentDisputes = Dispute::with(['transaction'])
            ->latest()
            ->limit(5)
            ->get();

        $pendingKycUsers = KycProfile::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'recentDisputes', 'pendingKycUsers'));
    }
}
