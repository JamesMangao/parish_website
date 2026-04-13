<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    public function index()
    {
        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.2fa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa_user_id');
        $user = \App\Models\User::find($userId);

        if ($user && $user->two_factor_secret === $request->code) {
            Auth::login($user);
            
            // Clear code
            $user->update(['two_factor_secret' => null]);
            
            session()->forget('2fa_user_id');
            session(['2fa_passed' => true]);

            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    public function resend()
    {
        $userId = session('2fa_user_id');
        $user = \App\Models\User::find($userId);

        if ($user) {
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->update(['two_factor_secret' => $code]);

            // In a real app, send email. For now, we'll log it if mail fails.
            try {
                Mail::raw("Your Parish Pal verification code is: $code", function ($message) use ($user) {
                    $message->to($user->email)->subject('Login Verification Code');
                });
            } catch (\Exception $e) {
                \Log::error("Failed to send 2FA email: " . $e->getMessage());
            }

            return back()->with('success', 'A new code has been sent to your email.');
        }

        return redirect()->route('login');
    }
}
