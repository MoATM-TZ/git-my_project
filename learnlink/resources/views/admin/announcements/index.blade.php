@extends('layouts.app')

@section('title', 'Platform announcements')

@section('content')
    <section class="ll-page-head">
        <h1>Platform announcements</h1>
        <p>Publish notices for students and lecturers across LearnLink.</p>
    </section>

    <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" class="ll-card ll-form ll-form-wide">
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
        <div class="ll-field">
            <label class="ll-label" for="banner_image">Banner image (optional, PNG/JPG/WEBP, max 2 MB)</label>
            <input class="ll-input" id="banner_image" name="banner_image" type="file" accept="image/png,image/jpeg,image/webp">
            @error('banner_image') <p class="ll-field-error">{{ $message }}</p> @enderror
        </div>
        <div class="ll-field">
            <label class="ll-label" for="display_type">Display format</label>
            <select class="ll-input" id="display_type" name="display_type" required>
                <option value="card" @selected(old('display_type', 'card') === 'card')>Standard Card</option>
                <option value="hero_banner" @selected(old('display_type') === 'hero_banner')>Hero Banner</option>
                <option value="alert_bar" @selected(old('display_type') === 'alert_bar')>Top Alert Bar</option>
            </select>
            @error('display_type') <p class="ll-field-error">{{ $message }}</p> @enderror
        </div>
        <div class="ll-grid">
            <div class="ll-field"><label class="ll-label" for="action_label">Call-to-action text (optional)</label><input class="ll-input" id="action_label" name="action_label" value="{{ old('action_label') }}" maxlength="80">@error('action_label') <p class="ll-field-error">{{ $message }}</p> @enderror</div>
            <div class="ll-field"><label class="ll-label" for="action_url">Destination URL (optional)</label><input class="ll-input" id="action_url" name="action_url" type="url" value="{{ old('action_url') }}" maxlength="2048">@error('action_url') <p class="ll-field-error">{{ $message }}</p> @enderror</div>
        </div>
        <button class="ll-btn ll-btn-primary" type="submit">Publish announcement</button>
    </form>

    <section class="ll-rail">
        <h2>Published announcements</h2>
        @forelse($announcements as $announcement)
            <article class="ll-card">
                <div class="ll-rail-head">
                    <h3>{{ $announcement->title }}</h3>
                    <a class="ll-btn ll-btn-ghost" href="{{ route('admin.announcements.edit', $announcement) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button class="ll-btn ll-btn-ghost" type="submit">Delete</button>
                    </form>
                </div>
                <p>{{ $announcement->body }}</p>
                <small class="ll-muted">{{ str($announcement->display_type)->headline() }}</small>
                <small class="ll-muted">By {{ $announcement->author->name }} · {{ $announcement->created_at->diffForHumans() }}</small>
            </article>
        @empty
            <p>No platform announcements yet.</p>
        @endforelse
        {{ $announcements->links() }}
    </section>
@endsection
