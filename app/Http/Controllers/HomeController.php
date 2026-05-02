<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function howItWorks()
    {
        return view('pages.how-it-works');
    }

    public function pricing()
    {
        return view('pages.pricing');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
