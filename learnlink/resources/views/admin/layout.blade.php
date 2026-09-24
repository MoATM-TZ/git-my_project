@extends('layouts.app')
@section('content')
<div class="ll-admin-layout">
    <aside class="ll-card ll-admin-sidebar">
        <h2>Administration</h2>
        <nav aria-label="Administration">
            <a class="ll-nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="ll-nav-link" href="{{ route('admin.approvals.index') }}">Account approvals</a>
            <a class="ll-nav-link" href="{{ route('admin.users.index') }}">Users</a>
            <a class="ll-nav-link" href="{{ route('admin.academic.index') }}">Academic</a>
            <a class="ll-nav-link" href="{{ route('admin.reports.index') }}">Reports</a>
            <a class="ll-nav-link" href="{{ route('admin.dashboard') }}#settings">Settings</a>
        </nav>
    </aside>
    <div class="ll-admin-content">@yield('admin-content')</div>
</div>
@endsection
