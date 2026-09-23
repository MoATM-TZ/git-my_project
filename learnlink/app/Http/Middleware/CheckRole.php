<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Symfony\Component\HttpFoundation\Response;
class CheckRole { public function handle(Request $request, Closure $next, string $role): Response { if (!$request->user()) return redirect()->route('login'); if ($request->user()->role !== $role) { $destination = match ($request->user()->role) { 'student' => route('student.dashboard'), 'lecturer' => route('lecturer.dashboard'), 'admin' => route('admin.dashboard'), default => route('home') }; return redirect()->to($destination)->with('error', 'This area is restricted to '.$role.' accounts.'); } return $next($request); } }
