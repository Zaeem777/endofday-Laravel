<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }   

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

        $user = Auth::user();
        if ($user->role === 'restaurant_owner') {
            return redirect()->intended('/Restaurant/dashboard')->with('success', 'Logged in successfully.');
        }elseif ($user->role === 'customer') {
            return redirect()->intended('/Customer/dashboard')->with('success', 'Logged in successfully.');
        }
        // elseif ($user->role === 'rider') {
        //     return redirect()->intended('/Rider/dashboard')->with('success', 'Logged in successfully.');
        // }
 return redirect('/login')->withErrors(['role' => 'Invalid role.']);
    }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}
