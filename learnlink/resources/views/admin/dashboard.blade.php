@extends('layouts.app')
@section('title', 'Admin dashboard')
@section('content')
@php
    $pendingApprovalsCount = $pendingApprovalsCount ?? ($stats['approvals'] ?? $pendingApprovals ?? 0);
    $openReportsCount = $openReportsCount ?? ($stats['reports'] ?? $openReports ?? 0);
    $studentsCount = $stats['students'] ?? 0;
    $lecturersCount = $stats['lecturers'] ?? 0;
    $uploadsCount = $stats['uploads'] ?? 0;
    $downloadsCount = $stats['downloads'] ?? 0;
    $activeClassesCount = $stats['classes'] ?? 0;
@endphp

    <header class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">System Overview</h1>
        <p class="mt-1 text-sm text-slate-500">Monitor platform activity and resolve pending operational tasks.</p>
    </header>

    @if($pendingApprovalsCount > 0)
        <div class="mb-4 flex flex-col gap-4 rounded-xl border border-amber-200/60 bg-amber-50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-3">
                <span class="font-semibold text-amber-900">Account Approvals Pending</span>
                <span class="rounded-full bg-amber-200 px-2.5 py-0.5 text-xs font-bold text-amber-800">{{ number_format($pendingApprovalsCount) }}</span>
            </div>
            <a href="{{ route('admin.approvals.index') }}" class="rounded-lg bg-amber-600 px-4 py-2 text-center text-sm font-medium text-white transition-all hover:bg-amber-700">Review Requests</a>
        </div>
    @endif

    @if($openReportsCount > 0)
        <div class="mb-6 flex flex-col gap-4 rounded-xl border border-rose-200/60 bg-rose-50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-3">
                <span class="font-semibold text-rose-900">Open Content Reports</span>
                <span class="rounded-full bg-rose-200 px-2.5 py-0.5 text-xs font-bold text-rose-800">{{ number_format($openReportsCount) }}</span>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="rounded-lg bg-rose-600 px-4 py-2 text-center text-sm font-medium text-white transition-all hover:bg-rose-700">Moderate Reports</a>
        </div>
    @endif

    <section class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4" aria-label="Platform metrics">
        <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Users</p>
            <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($studentsCount) }}</p>
            <p class="mt-1 text-sm text-slate-500">Students</p>
            <p class="mt-4 border-t border-slate-100 pt-3 text-sm text-slate-600"><strong class="font-semibold text-slate-900">{{ number_format($lecturersCount) }}</strong> Lecturers</p>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Uploads</p>
            <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($uploadsCount) }}</p>
            <p class="mt-1 text-sm text-slate-500">Total uploads</p>
            <p class="mt-4 border-t border-slate-100 pt-3 text-sm text-slate-600"><strong class="font-semibold text-slate-900">{{ number_format($downloadsCount) }}</strong> Downloads</p>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Classes</p>
            <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($activeClassesCount) }}</p>
            <p class="mt-1 text-sm text-slate-500">Active course classes</p>
        </article>

        <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Approvals / Queue</p>
            <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($pendingApprovalsCount) }}</p>
            <p class="mt-1 text-sm text-slate-500">Total pending approvals</p>
        </article>
    </section>

    <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm" aria-labelledby="admin-tools-title">
        <h2 id="admin-tools-title" class="text-lg font-semibold text-slate-900">Quick Tools</h2>
        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">User Management</a>
            <a href="{{ route('admin.academic.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Academic Setup</a>
            <a href="{{ route('admin.reports.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Content Moderation</a>
            <a href="#system-logs" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">System Logs</a>
        </div>
        <p id="system-logs" class="mt-5 border-t border-slate-100 pt-4 text-sm text-slate-500">Application logs are available to deployment administrators through the server log channel.</p>
    </section>
@endsection
