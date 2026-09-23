@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <div class="ll-auth min-h-screen bg-slate-50/50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="ll-card ll-auth-card sm:mx-auto sm:w-full sm:max-w-md bg-white py-8 px-6 shadow-md rounded-2xl border border-slate-100">
            <h2 class="ll-auth-gradient-title text-2xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Welcome Back to LearnLink</h2>
            <p class="ll-auth-description text-sm text-slate-500 mt-1">Access your academic resources and community dashboard</p>

            <form method="POST" action="{{ route('login.store') }}" class="ll-form">
                @csrf

                <div class="ll-field">
                    <label for="email" class="ll-label">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="you@example.com"
                        class="ll-input"
                    >
                    @error('email')
                        <p class="ll-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="ll-field">
                    <label for="password" class="ll-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Your password"
                        class="ll-input"
                    >
                    @error('password')
                        <p class="ll-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="ll-remember-row">
                    <label class="ll-checkbox-label" for="remember">
                        <input type="checkbox" id="remember" name="remember" class="ll-checkbox">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="ll-btn ll-btn-primary ll-btn-block">Log in</button>
            </form>

            <p class="ll-auth-alt">
                New to LearnLink?
                <a href="{{ route('register') }}" class="ll-link">Create an account</a>
            </p>
        </div>
    </div>
@endsection
