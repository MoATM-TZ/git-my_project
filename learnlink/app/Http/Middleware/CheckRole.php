<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Symfony\Component\HttpFoundation\Response;
class CheckRole { public function handle(Request $request, Closure $next, string $role): Response { if (!$request->user()) return redirect()->route($role.'.login'); if ($request->user()->role !== $role) { $destination = in_array($request->user()->role, ['lecturer', 'student'], true) ? route($request->user()->role.'.dashboard') : route('home'); return redirect()->to($destination)->with('error', 'This area is restricted to '.$role.' accounts.'); } return $next($request); } }
