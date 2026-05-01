<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_transactions' => Transaction::count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'active_users' => \App\Models\User::count(),
        ];

        $recent_transactions = Transaction::with(['seller', 'buyer'])
            ->where('status', '!=', 'draft')
            ->latest()
            ->take(5)
            ->get();

        return view('pages.home', compact('stats', 'recent_transactions'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function howItWorks()
    {
        return view('pages.how-it-works');
    }

    public function pricing()
    {
        return view('pages.pricing');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
