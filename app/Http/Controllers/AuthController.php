<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login'); 
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

    if ($user->role === 'admin') {
    return redirect('/admin/adminanalysis');
}

    return redirect('/user/dashboard'); // normal user dashboard
}

    return back()->withErrors(['email' => 'Invalid credentials']);
}

    public function logout()
{
    session()->forget('is_superadmin');
    Auth::logout();
    return redirect('/login');
}
  public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'user', // default role
                ]
            );

            Auth::login($user);

            if ($user->role === 'superadmin') return redirect('/superadmin/dashboard');
            if ($user->role === 'admin') return redirect('/admin/adminanalysis');

            return redirect('/'); // normal user dashboard

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Google');
        }
    }

}