@extends('admin.layout')
@section('title','Admin dashboard')
@section('admin-content')
    <section class="ll-page-head">
        <h1>System overview</h1>
        <p>Monitor learning activity and resolve operational work.</p>
    </section>

    <section class="ll-admin-alerts" aria-label="Urgent actions">
        <article class="ll-admin-alert">
            <div><span class="ll-admin-alert-label">Pending approvals</span><strong>{{ number_format($stats['approvals']) }}</strong></div>
            <a class="ll-btn ll-btn-primary" href="{{ route('admin.approvals.index') }}">Review Requests</a>
        </article>
        <article class="ll-admin-alert">
            <div><span class="ll-admin-alert-label">Open content reports</span><strong>{{ number_format($stats['reports']) }}</strong></div>
            <a class="ll-btn ll-btn-outline" href="{{ route('admin.reports.index') }}">Moderate Reports</a>
        </article>
    </section>

    <section class="ll-admin-kpis" aria-label="System metrics">
        <article class="ll-card ll-admin-kpi">
            <span class="ll-admin-kpi-label">User Community</span>
            <div class="ll-admin-kpi-values">
                <div><strong>{{ number_format($stats['students']) }}</strong><span>Students</span></div>
                <div><strong>{{ number_format($stats['lecturers']) }}</strong><span>Lecturers</span></div>
            </div>
        </article>
        <article class="ll-card ll-admin-kpi">
            <span class="ll-admin-kpi-label">Content Metrics</span>
            <div class="ll-admin-kpi-values">
                <div><strong>{{ number_format($stats['uploads']) }}</strong><span>Total uploads</span></div>
                <div><strong>{{ number_format($stats['downloads']) }}</strong><span>Total downloads</span></div>
            </div>
        </article>
        <article class="ll-card ll-admin-kpi">
            <span class="ll-admin-kpi-label">Academic Setup</span>
            <div class="ll-admin-kpi-values">
                <div><strong>{{ number_format($stats['classes']) }}</strong><span>Active course classes</span></div>
            </div>
        </article>
        <article class="ll-card ll-admin-kpi">
            <span class="ll-admin-kpi-label">Security &amp; System</span>
            <div class="ll-admin-kpi-values">
                <div><strong>{{ number_format($stats['approvals']) }}</strong><span>Pending approvals</span></div>
                <div><strong class="ll-admin-status"><span aria-hidden="true"></span>Operational</strong><span>System status</span></div>
            </div>
        </article>
    </section>

    <section class="ll-card ll-admin-tools">
        <h2>Administration tools</h2>
        <p><a class="ll-btn ll-btn-outline" href="{{ route('admin.users.index') }}">User management</a> <a class="ll-btn ll-btn-ghost" href="{{ route('admin.dashboard') }}#settings">System logs &amp; settings</a></p>
        <div id="settings"><h3>System logs</h3><p class="ll-muted">Application logs are available to deployment administrators through the server log channel.</p></div>
    </section>
@endsection
