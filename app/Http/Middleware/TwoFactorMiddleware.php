<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // যদি user login না থাকে
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // যদি user এর 2FA enabled থাকে কিন্তু verify না করে
        if (
            $user->google2fa_enabled &&
            !session()->has('2fa_verified')
        ) {
            return redirect()->route('2fa.index');
        }

        return $next($request);
    }
}