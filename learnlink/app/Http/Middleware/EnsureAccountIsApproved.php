<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->approval_status === 'pending') {
            if ($request->routeIs('approval.notice')) {
                return $next($request);
            }

            return redirect()->route('approval.notice');
        }

        if ($user?->approval_status !== 'approved') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has not been approved. Please contact LearnLink support.',
            ]);
        }

        return $next($request);
    }
}
