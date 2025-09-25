<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard/landing page
     */
    public function index()
    {
        return view('landing');
    }

    /**
     * Show the admin dashboard
     */
    public function admin()
    {
        return view('admin.dashboard');
    }
}