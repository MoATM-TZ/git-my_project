@php
    $pendingApprovals = \App\Models\User::query()->where('approval_status', 'pending')->count();
    $openReports = \App\Models\Report::query()->where('status', \App\Models\Report::STATUS_OPEN)->count();
@endphp
<a href="{{ route('admin.dashboard') }}" class="ll-brand" aria-label="LearnLink system dashboard">
    <span class="ll-brand-mark"><x-icon name="book" :size="22" /></span>
    <span class="ll-brand-name">LearnLink</span>
</a>
<nav class="ll-nav" id="primary-nav" aria-label="Primary">
    <a href="{{ route('admin.dashboard') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('admin.dashboard')])>System Dashboard</a>
    <a href="{{ route('admin.approvals.index') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('admin.approvals.*')])>Pending Approvals <span class="ll-badge ll-nav-count">{{ $pendingApprovals }}</span></a>
    <a href="{{ route('admin.reports.index') }}" @class(['ll-nav-link', 'is-active' => request()->routeIs('admin.reports.*')])>Reports <span class="ll-badge ll-nav-count">{{ $openReports }}</span></a>
</nav>
@include('layouts.navigation.account')
