@extends('layouts.app')

@section('title', 'Search resources')

@section('content')
    <section class="ll-page-head"><h1>Find learning resources</h1><p>Search the material shared by the LearnLink community.</p></section>
    <form class="ll-card ll-filter-form" method="GET" action="{{ route('search.index') }}">
        <input class="ll-input" type="search" name="q" value="{{ $term }}" placeholder="Search title, description, or filename">
        <select class="ll-input" name="category"><option value="">All categories</option>@foreach (\App\Models\Resource::CATEGORIES as $value => $label)<option value="{{ $value }}" @selected($category === $value)>{{ $label }}</option>@endforeach</select>
        <select class="ll-input" name="sort"><option value="recent" @selected($sort === 'recent')>Most recent</option><option value="popular" @selected($sort === 'popular')>Most downloaded</option><option value="title" @selected($sort === 'title')>Title A–Z</option></select>
        <button class="ll-btn ll-btn-primary" type="submit"><x-icon name="search" :size="16" /> Search</button>
    </form>

    <div class="ll-results-head"><p>{{ $results->total() }} {{ Str::plural('resource', $results->total()) }} found</p></div>
    @if ($results->isNotEmpty())
        <div class="ll-grid">@foreach ($results as $resource)<x-resource-card :resource="$resource" />@endforeach</div>
        <div class="ll-pagination">{{ $results->links() }}</div>
    @else
        <div class="ll-empty"><x-icon name="search" :size="30" /><h2>No resources found</h2><p>Try another search term or remove a filter.</p></div>
    @endif
@endsection
