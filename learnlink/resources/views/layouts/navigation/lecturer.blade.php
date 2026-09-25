<a href="{{ route('lecturer.dashboard') }}" class="ll-brand" aria-label="LearnLink dashboard">
    <span class="ll-brand-mark"><x-icon name="book" :size="22" /></span>
    <span class="ll-brand-name">LearnLink</span>
</a>
<nav class="ll-nav" id="primary-nav" aria-label="Primary">
    <a href="{{ route('home') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('home')])><x-icon name="book" :size="16" /> Dashboard</a>
    <a href="{{ route('lecturer.dashboard') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('lecturer.*')])>My Course Classes</a>
</nav>
@include('layouts.navigation.search')
@include('layouts.navigation.account')
