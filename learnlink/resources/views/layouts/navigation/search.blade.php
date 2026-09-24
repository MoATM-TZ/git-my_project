<div class="ll-search" data-live-search>
    <x-icon name="search" :size="16" class="ll-search-icon" />
    <input type="search" id="live-search" class="ll-search-input" placeholder="Search notes, papers, books…" autocomplete="off" data-search-url="{{ route('search.live') }}">
    <div class="ll-search-results" data-search-results hidden></div>
</div>
