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
        <span class="ll-card-author">@if($resource->user)<img class="ll-avatar ll-avatar-image ll-avatar-tiny" src="{{ $resource->user->avatar_url }}" alt="">{{ $resource->user->name }}@else<x-icon name="user" :size="14" /> Anonymous@endif</span>
        <span class="ll-card-count"><x-icon name="download" :size="14" /> {{ $resource->download_count }}</span>
    </div>
</a>
