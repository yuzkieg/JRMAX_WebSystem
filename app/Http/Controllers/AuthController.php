<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login'); // Make sure resources/views/login.blade.php exists
    }

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $email = $request->email;
    $password = $request->password;

    // Attempt normal login first
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
    $user = Auth::user();

    if ($user->role === 'superadmin') {
        return redirect('/superadmin/dashboard');
    }

    return redirect('/'); // normal user dashboard
}

    return back()->withErrors(['email' => 'Invalid credentials']);
}

    public function logout()
{
    session()->forget('is_superadmin');
    Auth::logout();
    return redirect('/login');
}
}