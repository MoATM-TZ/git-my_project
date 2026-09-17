<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LearnLink') — {{ config('app.name', 'LearnLink') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="ll-app">
    <header class="ll-header">
        <div class="ll-container ll-header-inner">
            {{-- Brand --}}
            <a href="{{ route('home') }}" class="ll-brand" aria-label="LearnLink home">
                <span class="ll-brand-mark"><x-icon name="book" :size="22" /></span>
                <span class="ll-brand-name">LearnLink</span>
            </a>

            {{-- Primary navigation --}}
            <nav class="ll-nav" id="primary-nav" aria-label="Primary">
                <a href="{{ route('home') }}" @if(request()->routeIs('home')) class="ll-nav-link is-active" @else class="ll-nav-link" @endif>
                    <x-icon name="book" :size="16" /> Home
                </a>
                <a href="{{ route('search.index') }}" @if(request()->routeIs('search.*')) class="ll-nav-link is-active" @else class="ll-nav-link" @endif>
                    <x-icon name="search" :size="16" /> Search
                </a>
                <a href="{{ route('reputation.index') }}" @if(request()->routeIs('reputation.*')) class="ll-nav-link is-active" @else class="ll-nav-link" @endif>
                    <x-icon name="trophy" :size="16" /> Reputation
                </a>

                @auth
                    @if(auth()->user()->role === 'lecturer')
                        <a href="{{ route('lecturer.dashboard') }}" class="ll-nav-link">Lecturer portal</a>
                    @elseif(auth()->user()->role === 'student')
                        <a href="{{ route('student.dashboard') }}" class="ll-nav-link">Student portal</a>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="ll-nav-link">Admin portal</a>
                    @endif
                    <a href="{{ route('resources.create') }}" class="ll-nav-link ll-nav-upload">
                        <x-icon name="upload" :size="16" /> Upload
                    </a>
                @endauth
            </nav>

            {{-- Live search --}}
            <div class="ll-search" data-live-search>
                <x-icon name="search" :size="16" class="ll-search-icon" />
                <input
                    type="search"
                    id="live-search"
                    class="ll-search-input"
                    placeholder="Search notes, papers, books…"
                    autocomplete="off"
                    data-search-url="{{ route('search.live') }}"
                >
                <div class="ll-search-results" data-search-results hidden></div>
            </div>

            {{-- Account / mobile toggle --}}
            <div class="ll-account">
                @auth
                    <button type="button" class="ll-user-btn" data-dropdown-trigger aria-haspopup="true" aria-expanded="false">
                        <span class="ll-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="ll-user-name">{{ auth()->user()->name }}</span>
                        <x-icon name="arrow-down" :size="14" />
                    </button>
                    <div class="ll-dropdown" data-dropdown hidden>
                        <a href="{{ route('profile.edit') }}" class="ll-dropdown-link">
                            <x-icon name="user" :size="16" /> My profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="ll-dropdown-form">
                            @csrf
                            <button type="submit" class="ll-dropdown-link">
                                <x-icon name="logout" :size="16" /> Sign out
                            </button>
                        </form>
                    </div>
                @endauth

                @guest
                    <div class="ll-account-links">
                        <a href="{{ route('student.login') }}" class="ll-btn ll-btn-ghost">Student login</a>
                        <a href="{{ route('lecturer.login') }}" class="ll-btn ll-btn-primary">Lecturer login</a>
                    </div>
                @endguest

                <button type="button" class="ll-menu-btn" data-menu-toggle aria-label="Toggle menu" aria-expanded="false">
                    <x-icon name="menu" :size="22" />
                </button>
            </div>
        </div>
    </header>

    <main class="ll-main">
        <div class="ll-container">
            @include('partials.flash')

            @yield('content')
        </div>
    </main>

    <footer class="ll-footer">
        <div class="ll-container ll-footer-inner">
            <p class="ll-footer-brand"><x-icon name="book" :size="16" /> LearnLink</p>
            <p class="ll-footer-copy">A community repository for learning materials. &copy; {{ date('Y') }}</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
