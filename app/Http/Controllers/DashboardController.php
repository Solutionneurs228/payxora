<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Notification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats selon le role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $stats = [
            'total_transactions' => Transaction::where('seller_id', $user->id)
                ->orWhere('buyer_id', $user->id)
                ->count(),
            'pending_transactions' => Transaction::where(function ($q) use ($user) {
                    $q->where('seller_id', $user->id)->orWhere('buyer_id', $user->id);
                })
                ->where('status', TransactionStatus::PENDING_PAYMENT)
                ->count(),
            'active_escrow' => Transaction::where(function ($q) use ($user) {
                    $q->where('seller_id', $user->id)->orWhere('buyer_id', $user->id);
                })
                ->whereIn('status', [
                    TransactionStatus::FUNDED,
                    TransactionStatus::SHIPPED,
                    TransactionStatus::DELIVERED,
                ])
                ->count(),
            'completed_transactions' => Transaction::where(function ($q) use ($user) {
                    $q->where('seller_id', $user->id)->orWhere('buyer_id', $user->id);
                })
                ->where('status', TransactionStatus::COMPLETED)
                ->count(),
            'total_sales' => Transaction::where('seller_id', $user->id)
                ->where('status', TransactionStatus::COMPLETED)
                ->sum('net_amount'),
            'total_purchases' => Transaction::where('buyer_id', $user->id)
                ->where('status', TransactionStatus::COMPLETED)
                ->sum('amount'),
        ];

        // Transactions recentes
        $recent_transactions = Transaction::where('seller_id', $user->id)
            ->orWhere('buyer_id', $user->id)
            ->with(['seller', 'buyer'])
            ->latest()
            ->limit(5)
            ->get();

        // Notifications non lues
        $unread_notifications = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->latest()
            ->limit(5)
            ->get();

        $unread_count = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('dashboard.index', compact(
            'stats',
            'recent_transactions',
            'unread_notifications',
            'unread_count'
        ));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('dashboard.notifications', compact('notifications'));
    }

    public function markNotificationRead(Request $request, int $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marquee comme lue.');
    }
}
