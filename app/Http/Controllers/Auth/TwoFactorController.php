<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function index()
    {
        if (!session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('auth.2fa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::find(session('2fa:user:id'));

        if (!$user) {
            return redirect()->route('login');
        }

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->google2fa_secret,
            $request->otp
        );

        if (!$valid) {
            return back()->withErrors([
                'otp' => 'Invalid verification code.'
            ]);
        }

        Auth::login($user);

        session()->forget('2fa:user:id');

        return redirect()->route('dashboard');
    }
}