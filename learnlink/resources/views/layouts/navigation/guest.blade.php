<a href="{{ route('home') }}" class="ll-brand" aria-label="LearnLink home">
    <span class="ll-brand-mark"><x-icon name="book" :size="22" /></span>
    <span class="ll-brand-name">LearnLink</span>
</a>
<nav class="ll-nav" id="primary-nav" aria-label="Primary">
    <a href="{{ route('home') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('home')])><x-icon name="book" :size="16" /> Home</a>
    <a href="{{ route('search.index') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('search.*')])><x-icon name="search" :size="16" /> Explore Resources</a>
    <a href="{{ route('reputation.index') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('reputation.*')])><x-icon name="trophy" :size="16" /> Reputation</a>
</nav>
<div class="ll-search" data-live-search>
    <x-icon name="search" :size="16" class="ll-search-icon" />
    <input type="search" id="live-search" class="ll-search-input" placeholder="Search notes, papers, books…" autocomplete="off" data-search-url="{{ route('search.live') }}">
    <div class="ll-search-results" data-search-results hidden></div>
</div>
<div class="ll-account">
    <div class="ll-account-links">
        <a href="{{ route('login') }}" class="ll-btn ll-btn-ghost">Log in</a>
        <a href="{{ route('register') }}" class="ll-btn ll-btn-primary">Create account</a>
    </div>
    <button type="button" class="ll-menu-btn" data-menu-toggle aria-label="Toggle menu" aria-expanded="false"><x-icon name="menu" :size="22" /></button>
</div>
