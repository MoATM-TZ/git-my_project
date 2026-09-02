@extends('layouts.app')

@section('title', 'Home')

@section('content')
    {{-- Hero --}}
    <section class="ll-hero">
        <div class="ll-hero-content">
            <h1 class="ll-hero-title">Learn from the community.</h1>
            <p class="ll-hero-sub">Browse notes, slides, past papers and books shared by fellow students and educators.</p>
            <div class="ll-hero-actions">
                <a href="{{ route('search.index') }}" class="ll-btn ll-btn-primary">
                    <x-icon name="search" :size="16" /> Explore resources
                </a>
                @auth
                    <a href="{{ route('resources.create') }}" class="ll-btn ll-btn-outline">
                        <x-icon name="upload" :size="16" /> Upload a resource
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Stats strip --}}
    <section class="ll-stats" aria-label="Platform statistics">
        <div class="ll-stat">
            <span class="ll-stat-value">{{ number_format($stats['resources']) }}</span>
            <span class="ll-stat-label">Resources</span>
        </div>
        <div class="ll-stat">
            <span class="ll-stat-value">{{ number_format($stats['users']) }}</span>
            <span class="ll-stat-label">Members</span>
        </div>
        <div class="ll-stat">
            <span class="ll-stat-value">{{ number_format($stats['downloads']) }}</span>
            <span class="ll-stat-label">Downloads</span>
        </div>
    </section>

    {{-- Category chips --}}
    @if ($categories->isNotEmpty())
        <section class="ll-categories" aria-label="Browse by category">
            @foreach ($categories as $slug => $count)
                <a href="{{ route('search.index', ['category' => $slug]) }}" class="ll-chip">
                    {{ \App\Models\Resource::CATEGORIES[$slug] ?? $slug }}
                    <span class="ll-chip-count">{{ $count }}</span>
                </a>
            @endforeach
        </section>
    @endif

    {{-- Trending rail --}}
    @if ($trending->isNotEmpty())
        <section class="ll-rail">
            <div class="ll-rail-head">
                <h2 class="ll-rail-title"><x-icon name="trending" :size="18" /> Trending now</h2>
                <a href="{{ route('search.index', ['sort' => 'popular']) }}" class="ll-rail-more">View all</a>
            </div>
            <div class="ll-grid">
                @foreach ($trending as $resource)
                    <x-resource-card :resource="$resource" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Recent rail --}}
    @if ($recent->isNotEmpty())
        <section class="ll-rail">
            <div class="ll-rail-head">
                <h2 class="ll-rail-title"><x-icon name="book" :size="18" /> Recently added</h2>
                <a href="{{ route('search.index') }}" class="ll-rail-more">View all</a>
            </div>
            <div class="ll-grid">
                @foreach ($recent as $resource)
                    <x-resource-card :resource="$resource" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Popular rail --}}
    @if ($popular->isNotEmpty())
        <section class="ll-rail">
            <div class="ll-rail-head">
                <h2 class="ll-rail-title"><x-icon name="download" :size="18" /> Most downloaded</h2>
                <a href="{{ route('search.index', ['sort' => 'popular']) }}" class="ll-rail-more">View all</a>
            </div>
            <div class="ll-grid">
                @foreach ($popular as $resource)
                    <x-resource-card :resource="$resource" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Top contributors --}}
    @if ($topContributors->isNotEmpty())
        <section class="ll-rail">
            <div class="ll-rail-head">
                <h2 class="ll-rail-title"><x-icon name="trophy" :size="18" /> Top contributors</h2>
                <a href="{{ route('reputation.index') }}" class="ll-rail-more">Leaderboard</a>
            </div>
            <ol class="ll-leaderboard">
                @foreach ($topContributors as $index => $contributor)
                    <li class="ll-leaderboard-row">
                        <span class="ll-leaderboard-rank">{{ $index + 1 }}</span>
                        <span class="ll-avatar">{{ strtoupper(substr($contributor->name, 0, 1)) }}</span>
                        <span class="ll-leaderboard-name">{{ $contributor->name }}</span>
                        <span class="ll-leaderboard-badge">
                            <x-icon name="star" :size="14" /> {{ $contributor->reputation_points }}
                        </span>
                    </li>
                @endforeach
            </ol>
        </section>
    @endif
@endsection
