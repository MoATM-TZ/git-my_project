<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($user->role, $roles, true)) {
            $destination = match ($user->role) {
                'student' => route('student.dashboard'),
                'lecturer' => route('lecturer.dashboard'),
                'admin' => route('admin.dashboard'),
                default => route('home'),
            };
            $requiredRoles = implode(' or ', $roles);

            return redirect()->to($destination)
                ->with('error', 'This area is restricted to '.$requiredRoles.' accounts.');
        }

        return $next($request);
    }
}
