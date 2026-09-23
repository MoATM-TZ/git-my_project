@extends('admin.layout')

@section('title', 'Account approvals')

@section('admin-content')
    <section class="ll-page-head">
        <h1>Account approvals</h1>
        <p>Review new student and lecturer account requests.</p>
    </section>

    <section class="ll-card ll-approval-list">
        @forelse ($users as $user)
            <article class="ll-approval-row">
                <div>
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }} · {{ ucfirst($user->role) }}</p>
                    @if ($user->studentProfile)
                        <p class="ll-muted">Student ID: {{ $user->studentProfile->registration_number }} · {{ $user->studentProfile->department }}</p>
                    @elseif ($user->lecturerProfile)
                        <p class="ll-muted">Department: {{ $user->lecturerProfile->department }}</p>
                    @endif
                    <small>Requested {{ $user->created_at->diffForHumans() }}</small>
                </div>
                <div class="ll-approval-actions">
                    <form method="POST" action="{{ route('admin.approvals.approve', $user) }}">
                        @csrf
                        <button type="submit" class="ll-btn ll-btn-primary">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.approvals.reject', $user) }}" onsubmit="return confirm('Reject this account request?')">
                        @csrf
                        <button type="submit" class="ll-btn ll-btn-danger">Reject</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="ll-empty">There are no accounts waiting for approval.</p>
        @endforelse

        {{ $users->links() }}
    </section>
@endsection
