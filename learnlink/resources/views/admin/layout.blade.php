@extends('layouts.app')
@section('content')
<div style="display:grid;grid-template-columns:minmax(180px,240px) 1fr;gap:1.5rem">
<aside class="ll-card" style="height:max-content"><h2>Administration</h2><nav class="ll-form"><a class="ll-nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a><a class="ll-nav-link" href="{{ route('admin.approvals.index') }}">Account approvals</a><a class="ll-nav-link" href="{{ route('admin.users.index') }}">Users</a><a class="ll-nav-link" href="{{ route('admin.academic.index') }}">Academic</a><a class="ll-nav-link" href="{{ route('admin.reports.index') }}">Reports</a><a class="ll-nav-link" href="{{ route('admin.dashboard') }}#settings">Settings</a></nav></aside>
<div>@yield('admin-content')</div></div>
@endsection
