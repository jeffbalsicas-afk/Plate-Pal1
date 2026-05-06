<?php

namespace App\Http\Controllers;

class LandingPageController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        return view('landingpage.home');
    }

    /**
     * Display the "How it works" page.
     */
    public function howItWorks()
    {
        return view('landingpage.how-it-works');
    }

    /**
     * Display the "For caterers" page.
     */
    public function forCaterers()
    {
        return view('landingpage.for-caterers');
    }
}
