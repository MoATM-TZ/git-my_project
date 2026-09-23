<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LecturerAuthController extends Controller
{
    public function createLogin(): View
    {
        return view('lecturer.auth.login');
    }

    public function createRegister(): View
    {
        return view('lecturer.auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt([...$data, 'role' => 'lecturer', 'is_active' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('lecturer.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'These credentials do not match an active lecturer account.',
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'min:5', 'max:255', 'regex:/^\S+(?:\s+\S+){1,}$/'],
            'department' => ['required', 'string', 'max:120'],
            'phone_number' => ['nullable', 'string', 'regex:/^\+?[1-9]\d{7,14}$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $data['full_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'lecturer',
        ]);

        $user->lecturerProfile()->create(
            collect($data)->only(['full_name', 'department', 'phone_number'])->all()
        );

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('lecturer.dashboard');
    }
}