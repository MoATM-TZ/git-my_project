@extends('layouts.app')

@section('title', 'Edit announcement')

@section('content')
    <section class="ll-page-head"><h1>Edit announcement</h1><p>Update its message, appearance, image, or action link.</p></section>

    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data" class="ll-card ll-form ll-form-wide">
        @csrf
        @method('PUT')
        <div class="ll-field"><label class="ll-label" for="title">Title</label><input class="ll-input" id="title" name="title" value="{{ old('title', $announcement->title) }}" required maxlength="255">@error('title') <p class="ll-field-error">{{ $message }}</p> @enderror</div>
        <div class="ll-field"><label class="ll-label" for="body">Message</label><textarea class="ll-input" id="body" name="body" rows="5" required maxlength="10000">{{ old('body', $announcement->body) }}</textarea>@error('body') <p class="ll-field-error">{{ $message }}</p> @enderror</div>
        @if($announcement->banner_image)
            <div class="ll-field"><p class="ll-label">Current banner image</p><img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($announcement->banner_image) }}" alt="Current announcement banner" style="max-width:100%;max-height:220px;object-fit:cover;border-radius:12px"></div>
        @endif
        <div class="ll-field"><label class="ll-label" for="banner_image">Replace banner image (PNG/JPG/WEBP, max 2 MB)</label><input class="ll-input" id="banner_image" name="banner_image" type="file" accept="image/png,image/jpeg,image/webp">@error('banner_image') <p class="ll-field-error">{{ $message }}</p> @enderror</div>
        <div class="ll-field"><label class="ll-label" for="display_type">Display format</label><select class="ll-input" id="display_type" name="display_type" required><option value="card" @selected(old('display_type', $announcement->display_type) === 'card')>Standard Card</option><option value="hero_banner" @selected(old('display_type', $announcement->display_type) === 'hero_banner')>Hero Banner</option><option value="alert_bar" @selected(old('display_type', $announcement->display_type) === 'alert_bar')>Top Alert Bar</option></select>@error('display_type') <p class="ll-field-error">{{ $message }}</p> @enderror</div>
        <div class="ll-grid">
            <div class="ll-field"><label class="ll-label" for="action_label">Call-to-action text (optional)</label><input class="ll-input" id="action_label" name="action_label" value="{{ old('action_label', $announcement->action_label) }}" maxlength="80">@error('action_label') <p class="ll-field-error">{{ $message }}</p> @enderror</div>
            <div class="ll-field"><label class="ll-label" for="action_url">Destination URL (optional)</label><input class="ll-input" id="action_url" name="action_url" type="url" value="{{ old('action_url', $announcement->action_url) }}" maxlength="2048">@error('action_url') <p class="ll-field-error">{{ $message }}</p> @enderror</div>
        </div>
        <button class="ll-btn ll-btn-primary" type="submit">Save changes</button>
        <a class="ll-btn ll-btn-ghost" href="{{ route('admin.announcements.index') }}">Cancel</a>
    </form>
@endsection
