<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        if ($user->role === 'student') {
            $profile = $user->studentProfile;
            $pending = ! $profile || str_starts_with((string) $profile->registration_number, 'PENDING-');
            if ($pending && ! $request->routeIs('onboarding.student.*')) {
                return redirect()->route('onboarding.student.edit');
            }
        } elseif ($user->role === 'lecturer' && (! $user->lecturerProfile || $user->lecturerProfile->department === 'Unassigned')) {
            if (! $request->routeIs('onboarding.lecturer.*')) {
                return redirect()->route('onboarding.lecturer.edit');
            }
        }

        return $next($request);
    }
}
