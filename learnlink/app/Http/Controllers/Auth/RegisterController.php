<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /** Show the registration form (legacy register.php). */
    public function create(): View
    {
        return view('auth.register');
    }

    /** Validate and create a new account (legacy register_process.php). */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:student,lecturer'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            if ($user->role === 'student') {
                $user->studentProfile()->create([
                    'full_name' => $user->name,
                    'registration_number' => 'PENDING-'.$user->id,
                    'year_of_study' => 1,
                    'phone_number' => 'PENDING',
                    'department' => 'Unassigned',
                    'program_level' => 'Degree',
                    'program_name' => 'Unassigned',
                ]);
            } else {
                $user->lecturerProfile()->create([
                    'full_name' => $user->name,
                    'department' => 'Unassigned',
                ]);
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->role === 'student' ? 'onboarding.student.edit' : 'onboarding.lecturer.edit');
    }
}
