@extends('layouts.app')

@section('title', 'My profile')

@section('content')
    <section class="ll-page-head"><h1>My profile</h1><p>Manage your account and see your community activity.</p></section>
    <div class="ll-profile-grid">
        <aside class="ll-card ll-profile-summary">
            <img class="ll-avatar ll-avatar-large ll-avatar-image" src="{{ $user->avatar_url }}" alt="{{ $user->name }}'s profile picture">
            <h2>{{ $user->name }}</h2><p>{{ $user->email }}</p>
            <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="ll-avatar-form">
                @csrf
                <label class="ll-label" for="avatar">Profile picture</label>
                <input class="ll-input" id="avatar" type="file" name="avatar" accept="image/jpeg,image/png,image/webp" required>
                @error('avatar')<p class="ll-field-error">{{ $message }}</p>@enderror
                <button class="ll-btn ll-btn-primary" type="submit">Upload picture</button>
            </form>
            @if($user->avatar_path)
                <form method="POST" action="{{ route('profile.avatar.delete') }}" class="ll-avatar-remove-form">
                    @csrf @method('DELETE')
                    <button class="ll-btn ll-btn-outline" type="submit">Remove picture</button>
                </form>
            @endif
            <div class="ll-stat"><span class="ll-stat-value">{{ $user->reputation_points }}</span><span class="ll-stat-label">Reputation</span></div>
            <div class="ll-profile-counts"><span>{{ $user->resources_count }} uploads</span><span>{{ $user->comments_count }} comments</span></div>
        </aside>
        <form class="ll-card ll-form" method="POST" action="{{ route('profile.update') }}">@csrf @method('PATCH')<h2>Account details</h2><div class="ll-field"><label class="ll-label" for="name">Name</label><input class="ll-input" id="name" name="name" value="{{ old('name', $user->name) }}" required>@error('name')<p class="ll-field-error">{{ $message }}</p>@enderror</div><div class="ll-field"><label class="ll-label" for="email">Email address</label><input class="ll-input" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>@error('email')<p class="ll-field-error">{{ $message }}</p>@enderror</div><h2>Change password <span class="ll-muted">(optional)</span></h2><div class="ll-field"><label class="ll-label" for="current_password">Current password</label><input class="ll-input" type="password" id="current_password" name="current_password" autocomplete="current-password">@error('current_password')<p class="ll-field-error">{{ $message }}</p>@enderror</div><div class="ll-field"><label class="ll-label" for="new_password">New password</label><input class="ll-input" type="password" id="new_password" name="new_password" autocomplete="new-password">@error('new_password')<p class="ll-field-error">{{ $message }}</p>@enderror</div><div class="ll-field"><label class="ll-label" for="new_password_confirmation">Confirm new password</label><input class="ll-input" type="password" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password"></div><button class="ll-btn ll-btn-primary" type="submit">Save changes</button></form>
    </div>
@endsection
