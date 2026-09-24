@extends('layouts.app')

@section('title', $resource->title)

@section('content')
    <article class="ll-resource-detail">
        <div class="ll-card ll-resource-hero">
            <div class="ll-resource-heading">
                <span class="ll-badge ll-badge-{{ $resource->category }}">{{ \App\Models\Resource::CATEGORIES[$resource->category] ?? $resource->category }}</span>
                <h1>{{ $resource->title }}</h1>
                <p class="ll-resource-by"><img class="ll-avatar ll-avatar-image ll-avatar-small" src="{{ $resource->user->avatar_url }}" alt=""> Shared by <strong>{{ $resource->user->name }}</strong> · {{ $resource->created_at->diffForHumans() }}</p>
            </div>
            <div class="ll-resource-actions">
                <a class="ll-btn ll-btn-primary" href="{{ route('resources.download', $resource) }}"><x-icon name="download" :size="16" /> Download</a>
                @auth
                    @if (auth()->id() === $resource->user_id)
                        <form method="POST" action="{{ route('resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource permanently?')">@csrf @method('DELETE')<button class="ll-btn ll-btn-danger" type="submit">Delete</button></form>
                    @endif
                @endauth
            </div>
            <dl class="ll-resource-stats">
                <div><dt>File</dt><dd>{{ $resource->original_name }}</dd></div>
                <div><dt>Size</dt><dd>{{ $resource->humanSize() }}</dd></div>
                <div><dt>Downloads</dt><dd>{{ number_format($resource->download_count) }}</dd></div>
                <div><dt>Views</dt><dd>{{ number_format($resource->views_count) }}</dd></div>
            </dl>
        </div>

        @if (in_array(strtolower((string) $resource->file_type), ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']))
            <section class="ll-card ll-section">
                <h2>Preview</h2>
                @if (strtolower((string) $resource->file_type) === 'pdf')
                    <iframe title="{{ $resource->title }} preview" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($resource->file_path) }}" style="width:100%; min-height: 560px;"></iframe>
                @else
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($resource->file_path) }}" alt="Preview of {{ $resource->title }}" style="max-width:100%; height:auto;">
                @endif
            </section>
        @endif

        @if ($resource->description)<section class="ll-card ll-section"><h2>About this resource</h2><p class="ll-prose">{{ $resource->description }}</p></section>@endif

        <section class="ll-card ll-section">
            <h2>Community feedback</h2>
            <div class="ll-voting" data-vote-group>
                <span class="ll-score" data-vote-score>{{ $upvotes - $downvotes }}</span>
                @auth
                    <form method="POST" action="{{ route('resources.votes.store', $resource) }}" data-vote-form>@csrf<button class="ll-btn ll-btn-ghost @if($myVote?->value === 1) is-selected @endif" name="direction" value="up" type="submit"><x-icon name="arrow-up" :size="16" /> Upvote</button><button class="ll-btn ll-btn-ghost @if($myVote?->value === -1) is-selected @endif" name="direction" value="down" type="submit"><x-icon name="arrow-down" :size="16" /> Downvote</button></form>
                @else <a class="ll-link" href="{{ route('login') }}">Log in to vote</a> @endauth
            </div>
        </section>

        <section class="ll-card ll-section" id="comments">
            <h2>{{ $comments->count() }} {{ Str::plural('comment', $comments->count()) }}</h2>
            @auth
                <form method="POST" action="{{ route('resources.comments.store', $resource) }}" class="ll-comment-form">@csrf<textarea class="ll-input" name="body" rows="3" required minlength="2" maxlength="2000" placeholder="Add a helpful comment…">{{ old('body') }}</textarea>@error('body')<p class="ll-field-error">{{ $message }}</p>@enderror<button class="ll-btn ll-btn-primary" type="submit">Post comment</button></form>
            @else <p><a class="ll-link" href="{{ route('login') }}">Log in</a> to join the discussion.</p> @endauth
            <div class="ll-comments">@forelse($comments as $comment)<article class="ll-comment"><img class="ll-avatar ll-avatar-image" src="{{ $comment->user->avatar_url }}" alt=""><div><strong>{{ $comment->user->name }}</strong><time datetime="{{ $comment->created_at->toDateString() }}">{{ $comment->created_at->diffForHumans() }}</time><p>{{ $comment->body }}</p></div></article>@empty<p class="ll-muted">No comments yet. Be the first to start a discussion.</p>@endforelse</div>
        </section>

        @auth
            @if (auth()->id() !== $resource->user_id)
                <details class="ll-report"><summary><x-icon name="flag" :size="15" /> Report this resource</summary><form method="POST" action="{{ route('resources.reports.store', $resource) }}" class="ll-form">@csrf<label class="ll-label" for="reason">Reason</label><select class="ll-input" id="reason" name="reason" required>@foreach(\App\Models\Report::REASONS as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select><textarea class="ll-input" name="details" rows="3" maxlength="2000" placeholder="Optional details"></textarea><button class="ll-btn ll-btn-ghost" type="submit">Submit report</button></form></details>
            @endif
        @endauth
    </article>
@endsection
