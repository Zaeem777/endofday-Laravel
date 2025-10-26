<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

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
            } elseif ($user->role === 'customer') {
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

    public function showforgotPassword()
    {
        return view('forgotpassword');
    }

    public function forgotpassword(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }

        // Send reset link
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link sent successfully!');
        }
        return back()->with('error', 'Failed to send password reset link.');
    }

    public function showupdatepassword($token)
    {
        return view('updatepassword', ['token' => $token]);
    }

    public function resetpassword(Request $request)
    {

        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Your password has been reset successfully!');
        }

        return back()->with('error', 'Failed to reset password. Please try again.');
    }
}
