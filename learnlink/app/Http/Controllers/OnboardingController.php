<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function student(Request $request): View
    {
        abort_unless($request->user()->role === 'student', 403);
        return view('onboarding.student');
    }

    public function saveStudent(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->role === 'student', 403);
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:80', Rule::unique('student_profiles', 'registration_number')->ignore($user->studentProfile?->id)],
            'year_of_study' => ['required', 'integer', 'between:1,10'],
            'phone_number' => ['required', 'string', 'max:30'],
            'department' => ['required', 'string', 'max:120'],
            'program_level' => ['required', 'in:Certificate,Diploma,Degree,Masters,PhD'],
            'program_name' => ['required', 'string', 'max:160'],
        ]);
        DB::transaction(fn () => $user->studentProfile()->updateOrCreate(['user_id' => $user->id], $data));
        return redirect()->route('student.dashboard')->with('status', 'Student profile completed.');
    }

    public function lecturer(Request $request): View
    {
        abort_unless($request->user()->role === 'lecturer', 403);
        return view('onboarding.lecturer');
    }

    public function saveLecturer(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->role === 'lecturer', 403);
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:120'],
            'phone_number' => ['nullable', 'string', 'max:30'],
        ]);
        DB::transaction(fn () => $user->lecturerProfile()->updateOrCreate(['user_id' => $user->id], $data));
        return redirect()->route('lecturer.dashboard')->with('status', 'Lecturer profile completed.');
    }
}
