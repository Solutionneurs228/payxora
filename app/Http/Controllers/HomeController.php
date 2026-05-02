<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Comment ca marche.
     */
    public function howItWorks()
    {
        return view('pages.how-it-works');
    }

    /**
     * Page tarifs.
     */
    public function pricing()
    {
        return view('pages.pricing');
    }

    /**
     * Page a propos.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Page contact.
     */
    public function contact()
    {
        return view('pages.contact');
    }
}
