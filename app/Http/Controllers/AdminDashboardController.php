<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class AdminDashboardController extends Controller
{
    public function index()
    {
         $admins = User::where('role', 'admin')->get();
        return view('admin.adminanalysis', compact('admins'));
    }

    
    public function hrmanagement()
    {
         $admins = User::where('role', 'admin')->get();
        return view('admin.users', compact('admins'));
    }

}
