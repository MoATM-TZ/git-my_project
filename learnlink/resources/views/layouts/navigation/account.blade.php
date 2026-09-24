<div class="ll-account">
    <button type="button" class="ll-user-btn" data-dropdown-trigger aria-haspopup="true" aria-expanded="false">
        <span class="ll-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        <span class="ll-user-name">{{ auth()->user()->name }}</span>
        <x-icon name="arrow-down" :size="14" />
    </button>
    <div class="ll-dropdown" data-dropdown hidden>
        <a href="{{ route('profile.edit') }}" class="ll-dropdown-link"><x-icon name="user" :size="16" /> My profile</a>
        <form method="POST" action="{{ route('logout') }}" class="ll-dropdown-form">
            @csrf
            <button type="submit" class="ll-dropdown-link"><x-icon name="logout" :size="16" /> Sign out</button>
        </form>
    </div>
    <button type="button" class="ll-menu-btn" data-menu-toggle aria-label="Toggle menu" aria-expanded="false"><x-icon name="menu" :size="22" /></button>
</div>
