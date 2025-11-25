<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
class UserController extends Controller
{
 public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('user.dashboard', compact('users'));
    }}
