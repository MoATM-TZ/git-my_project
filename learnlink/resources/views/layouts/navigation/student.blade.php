<a href="{{ route('student.dashboard') }}" class="ll-brand" aria-label="LearnLink dashboard">
    <span class="ll-brand-mark"><x-icon name="book" :size="22" /></span>
    <span class="ll-brand-name">LearnLink</span>
</a>
<nav class="ll-nav" id="primary-nav" aria-label="Primary">
    <a href="{{ route('student.dashboard') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('student.dashboard')])><x-icon name="book" :size="16" /> Dashboard</a>
    <a href="{{ route('search.index') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('search.*')])><x-icon name="search" :size="16" /> Explore Resources</a>
    <a href="{{ route('reputation.index') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('reputation.*')])><x-icon name="trophy" :size="16" /> Reputation</a>
    <a href="{{ route('resources.create') }}" class="ll-btn ll-btn-primary ll-nav-upload"><x-icon name="upload" :size="16" /> Upload</a>
</nav>
@include('layouts.navigation.search')
@include('layouts.navigation.account')
