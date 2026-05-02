<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'utilisateur.
     */
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_transactions' => Transaction::where('buyer_id', $user->id)
                ->orWhere('seller_id', $user->id)
                ->count(),
            'pending_transactions' => Transaction::where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                      ->orWhere('seller_id', $user->id);
            })->where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                      ->orWhere('seller_id', $user->id);
            })->where('status', 'completed')->count(),
            'total_spent' => Transaction::where('buyer_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount'),
            'total_earned' => Transaction::where('seller_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        $recentTransactions = Transaction::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['buyer', 'seller', 'payment'])
            ->latest()
            ->limit(5)
            ->get();

        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentTransactions', 'notifications'));
    }

    /**
     * Récupère les notifications de l'utilisateur (AJAX).
     */
    public function notifications(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->limit(20)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Marque une notification comme lue.
     */
    public function markNotificationRead(Request $request, $id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
