@extends('layouts.app')

@section('title', 'Account pending approval')

@section('content')
    <section class="ll-auth">
        <div class="ll-card ll-auth-card">
            <span class="ll-approval-icon" aria-hidden="true">✓</span>
            <h1 class="ll-auth-gradient-title">Your account is under review</h1>
            <p class="ll-auth-description">
                Thanks for joining LearnLink, {{ auth()->user()->name }}. An administrator needs to approve your account before you can access the platform.
            </p>
            <p class="ll-muted">We’ll send an email when a decision has been made.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="ll-btn ll-btn-primary ll-btn-block">Log out</button>
            </form>
        </div>
    </section>
@endsection
