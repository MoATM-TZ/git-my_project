@extends('layouts.app')
@section('title', 'Admin dashboard')
@section('content')
@php
    $pendingApprovals = $stats['pending_approvals'] ?? $stats['approvals'] ?? 0;
    $pendingReports = $stats['pending_reports'] ?? $stats['reports'] ?? 0;
    $totalUploads = $stats['total_uploads'] ?? $stats['uploads'] ?? 0;
    $totalDownloads = $stats['total_downloads'] ?? $stats['downloads'] ?? 0;
    $activeClasses = $stats['active_classes'] ?? $stats['classes'] ?? 0;
@endphp

<div style="max-width: 1100px; margin: 0 auto; padding: 24px; font-family: system-ui, -apple-system, sans-serif;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0;">System Overview</h1>
        <p style="font-size: 14px; color: #64748b; margin: 0;">Monitor platform activity and resolve pending operational tasks.</p>
    </div>

    @if($pendingApprovals > 0)
        <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: space-between;">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
                <span style="font-weight: 600; color: #92400e;">Account Approvals Pending</span>
                <span style="background-color: #fef3c7; color: #92400e; padding: 2px 10px; border-radius: 9999px; font-weight: 700; font-size: 12px;">{{ number_format($pendingApprovals) }}</span>
            </div>
            <a href="{{ route('admin.approvals.index') }}" style="background-color: #d97706; color: white; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none;">Review Requests</a>
        </div>
    @endif

    @if($pendingReports > 0)
        <div style="background-color: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: space-between;">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
                <span style="font-weight: 600; color: #9f1239;">Open Content Reports</span>
                <span style="background-color: #ffe4e6; color: #9f1239; padding: 2px 10px; border-radius: 9999px; font-weight: 700; font-size: 12px;">{{ number_format($pendingReports) }}</span>
            </div>
            <a href="{{ route('admin.reports.index') }}" style="background-color: #e11d48; color: white; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none;">Moderate Reports</a>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 32px;">
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Users</div>
            <div style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 8px 0 4px 0;">{{ number_format($stats['students'] ?? 0) }}</div>
            <div style="font-size: 13px; color: #64748b;">Students</div>
            <div style="font-size: 13px; color: #64748b; margin-top: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9;"><strong style="color: #0f172a;">{{ number_format($stats['lecturers'] ?? 0) }}</strong> Lecturers</div>
        </div>

        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Uploads</div>
            <div style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 8px 0 4px 0;">{{ number_format($totalUploads) }}</div>
            <div style="font-size: 13px; color: #64748b;">Total uploads</div>
            <div style="font-size: 13px; color: #64748b; margin-top: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9;"><strong style="color: #0f172a;">{{ number_format($totalDownloads) }}</strong> Downloads</div>
        </div>

        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Classes</div>
            <div style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 8px 0 4px 0;">{{ number_format($activeClasses) }}</div>
            <div style="font-size: 13px; color: #64748b;">Active course classes</div>
        </div>

        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Pending Queue</div>
            <div style="font-size: 28px; font-weight: 800; color: #4f46e5; margin: 8px 0 4px 0;">{{ number_format($pendingApprovals) }}</div>
            <div style="font-size: 13px; color: #64748b;">Approvals waiting</div>
        </div>
    </div>

    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0 0 16px 0;">Quick Administration Tools</h2>
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <a href="{{ route('admin.users.index') }}" style="background-color: #f8fafc; border: 1px solid #cbd5e1; color: #334155; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none;">User Management</a>
            <a href="{{ route('admin.academic.index') }}" style="background-color: #f8fafc; border: 1px solid #cbd5e1; color: #334155; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none;">Academic Setup</a>
            <a href="{{ route('admin.reports.index') }}" style="background-color: #f8fafc; border: 1px solid #cbd5e1; color: #334155; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none;">Content Moderation</a>
            <a href="{{ route('admin.announcements.index') }}" style="background-color: #f8fafc; border: 1px solid #cbd5e1; color: #334155; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none;">Platform Announcements</a>
            <a href="#system-logs" style="background-color: #f8fafc; border: 1px solid #cbd5e1; color: #334155; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none;">System Logs</a>
        </div>
        <p id="system-logs" style="font-size: 13px; color: #64748b; margin: 20px 0 0; padding-top: 16px; border-top: 1px solid #f1f5f9;">Application logs are available to deployment administrators through the server log channel.</p>
    </div>
</div>
@endsection
