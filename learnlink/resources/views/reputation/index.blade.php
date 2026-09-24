@extends('layouts.app')

@section('title', 'Reputation')

@section('content')
    <section class="ll-page-head"><h1>Community reputation</h1><p>Recognition for sharing helpful learning materials and contributing to discussions.</p></section>
    <section class="ll-card ll-section"><h2>Top contributors</h2><ol class="ll-leaderboard">@forelse($leaderboard as $member)<li><span class="ll-rank">{{ $loop->iteration }}</span><img class="ll-avatar ll-avatar-image" src="{{ $member->avatar_url }}" alt=""><span class="ll-leader-name">{{ $member->name }}<small>{{ $member->resources_count }} uploads · {{ $member->comments_count }} comments</small></span><strong>{{ number_format($member->reputation_points) }} pts</strong></li>@empty<li class="ll-muted">No contributors yet.</li>@endforelse</ol></section>
    @auth
        <section class="ll-card ll-section"><h2>My reputation history</h2><ul class="ll-history">@forelse($myHistory as $entry)<li><span class="{{ $entry->points >= 0 ? 'll-positive' : 'll-negative' }}">{{ $entry->points >= 0 ? '+' : '' }}{{ $entry->points }}</span><span>{{ str_replace('_', ' ', $entry->reason) }} @if($entry->resource) · <a class="ll-link" href="{{ route('resources.show', $entry->resource) }}">{{ $entry->resource->title }}</a>@endif<small>{{ $entry->created_at->diffForHumans() }}</small></span></li>@empty<li class="ll-muted">Your reputation activity will appear here.</li>@endforelse</ul></section>
    @endauth
@endsection
