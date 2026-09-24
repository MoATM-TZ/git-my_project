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
            @include('layouts.navigation')
        </div>
    </header>

    <main class="ll-main">
        <div class="{{ request()->routeIs('admin.dashboard') ? 'mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8' : 'll-container' }}">
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
