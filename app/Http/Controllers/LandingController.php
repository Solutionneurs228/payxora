<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Affiche la page d'accueil avec les stats.
     */
    public function index()
    {
        $stats = [
            'total_transactions' => Transaction::count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        return view('pages.home', compact('stats'));
    }
}
