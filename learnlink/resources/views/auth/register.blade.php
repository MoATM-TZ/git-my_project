@extends('layouts.app')

@section('title', 'Create account')

@section('content')
    <div class="ll-auth min-h-screen bg-slate-50/50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="ll-card ll-auth-card sm:mx-auto sm:w-full sm:max-w-md bg-white py-8 px-6 shadow-md rounded-2xl border border-slate-100">
            <h2 class="ll-auth-gradient-title text-2xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Create Your Account</h2>
            <p class="ll-auth-description text-sm text-slate-500 mt-1">Join the academic community to access notes, slides, and collaborate</p>

            <form method="POST" action="{{ route('register.store') }}" class="ll-form">
                @csrf
                <div class="ll-field">
                    <label class="ll-label" for="role">I am registering as</label>
                    <select class="ll-input" id="role" name="role" required>
                        <option value="student" @selected(old('role') === 'student')>Student</option>
                        <option value="lecturer" @selected(old('role') === 'lecturer')>Lecturer</option>
                    </select>
                    @error('role') <p class="ll-field-error">{{ $message }}</p> @enderror
                </div>
                <div class="ll-field">
                    <label class="ll-label" for="name">Name</label>
                    <input class="ll-input" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name') <p class="ll-field-error">{{ $message }}</p> @enderror
                </div>
                <div class="ll-field">
                    <label class="ll-label" for="email">Email address</label>
                    <input class="ll-input" type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email') <p class="ll-field-error">{{ $message }}</p> @enderror
                </div>
                <div class="ll-field">
                    <label class="ll-label" for="password">Password</label>
                    <input class="ll-input" type="password" id="password" name="password" required autocomplete="new-password">
                    @error('password') <p class="ll-field-error">{{ $message }}</p> @enderror
                </div>
                <div class="ll-field">
                    <label class="ll-label" for="password_confirmation">Confirm password</label>
                    <input class="ll-input" type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                </div>
                <button class="ll-btn ll-btn-primary ll-btn-block" type="submit">Create account</button>
            </form>

            <p class="ll-auth-alt">Already have an account? <a href="{{ route('login') }}" class="ll-link">Log in</a></p>
        </div>
    </div>
@endsection
