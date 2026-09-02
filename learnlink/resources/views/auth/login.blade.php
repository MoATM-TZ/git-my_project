@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <div class="ll-auth">
        <div class="ll-card ll-auth-card">
            <h1 class="ll-auth-title">Welcome back</h1>
            <p class="ll-auth-sub">Log in to upload, vote and comment.</p>

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
