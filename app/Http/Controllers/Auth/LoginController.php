<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::validate($credentials)) {
            $user = \App\Models\User::where('email', $credentials['email'])->first();
            
            // Generate 6-digit code
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->update(['two_factor_secret' => $code]);

            // Send Email
            try {
                \Illuminate\Support\Facades\Mail::raw("Your Parish Pal verification code is: $code", function ($message) use ($user) {
                    $message->to($user->email)->subject('Login Verification Code');
                });
            } catch (\Exception $e) {
                \Log::error("Failed to send 2FA email: " . $e->getMessage());
            }

            session(['2fa_user_id' => $user->id]);

            return redirect()->route('admin.2fa.index');
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
        return redirect('/');
    }
}
