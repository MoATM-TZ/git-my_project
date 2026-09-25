@extends('layouts.app')

@section('title', 'Platform announcements')

@section('content')
    <section class="ll-page-head">
        <h1>Platform announcements</h1>
        <p>Publish notices for students and lecturers across LearnLink.</p>
    </section>

    <form method="POST" action="{{ route('admin.announcements.store') }}" class="ll-card ll-form ll-form-wide">
        @csrf
        <h2>Publish an announcement</h2>
        <div class="ll-field">
            <label class="ll-label" for="title">Title</label>
            <input class="ll-input" id="title" name="title" value="{{ old('title') }}" required maxlength="255">
            @error('title') <p class="ll-field-error">{{ $message }}</p> @enderror
        </div>
        <div class="ll-field">
            <label class="ll-label" for="body">Message</label>
            <textarea class="ll-input" id="body" name="body" rows="5" required maxlength="10000">{{ old('body') }}</textarea>
            @error('body') <p class="ll-field-error">{{ $message }}</p> @enderror
        </div>
        <button class="ll-btn ll-btn-primary" type="submit">Publish announcement</button>
    </form>

    <section class="ll-rail">
        <h2>Published announcements</h2>
        @forelse($announcements as $announcement)
            <article class="ll-card">
                <div class="ll-rail-head">
                    <h3>{{ $announcement->title }}</h3>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button class="ll-btn ll-btn-ghost" type="submit">Delete</button>
                    </form>
                </div>
                <p>{{ $announcement->body }}</p>
                <small class="ll-muted">By {{ $announcement->author->name }} · {{ $announcement->created_at->diffForHumans() }}</small>
            </article>
        @empty
            <p>No platform announcements yet.</p>
        @endforelse
        {{ $announcements->links() }}
    </section>
@endsection
