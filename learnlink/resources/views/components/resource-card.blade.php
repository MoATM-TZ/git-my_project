@props(['resource'])

<a href="{{ route('resources.show', $resource) }}" class="ll-card ll-resource-card">
    <div class="ll-card-top">
        <span class="ll-badge ll-badge-{{ $resource->category }}">
            {{ \App\Models\Resource::CATEGORIES[$resource->category] ?? $resource->category }}
        </span>
        <span class="ll-card-score">
            <x-icon name="arrow-up" :size="14" /> {{ $resource->score() }}
        </span>
    </div>

    <h3 class="ll-card-title">{{ $resource->title }}</h3>

    @if ($resource->description)
        <p class="ll-card-desc">{{ Str::limit($resource->description, 90) }}</p>
    @endif

    <div class="ll-card-meta">
        <span class="ll-card-author"><x-icon name="user" :size="14" /> {{ $resource->user->name ?? 'Anonymous' }}</span>
        <span class="ll-card-count"><x-icon name="download" :size="14" /> {{ $resource->download_count }}</span>
    </div>
</a>
