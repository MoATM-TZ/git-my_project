@extends('layouts.app')
@section('title', 'Content Moderation')
@section('content')
@php($activeStatus = request('status', 'pending'))
<div style="max-width: 1280px; margin: 0 auto; padding: 24px; font-family: system-ui, -apple-system, sans-serif; color: #0f172a;">
    <header style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 22px;">
        <div>
            <h1 style="font-size: 26px; font-weight: 700; margin: 0 0 5px;">Content Moderation</h1>
            <p style="font-size: 14px; color: #64748b; margin: 0;">Review reports, remove harmful content, and close duplicate reports together.</p>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <span style="padding: 7px 10px; border-radius: 999px; background: #fffbeb; color: #92400e; font-size: 12px; font-weight: 700;">Pending {{ number_format($counts['pending']) }}</span>
            <span style="padding: 7px 10px; border-radius: 999px; background: #ecfdf5; color: #047857; font-size: 12px; font-weight: 700;">Resolved {{ number_format($counts['resolved']) }}</span>
            <span style="padding: 7px 10px; border-radius: 999px; background: #f1f5f9; color: #475569; font-size: 12px; font-weight: 700;">Dismissed {{ number_format($counts['dismissed']) }}</span>
        </div>
    </header>

    <nav aria-label="Report status" style="display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 14px;">
        @foreach(['pending' => 'Pending Reports', 'resolved' => 'Resolved', 'dismissed' => 'Dismissed'] as $statusValue => $statusLabel)
            <a href="{{ route('admin.reports.index', array_filter(['status' => $statusValue, 'type' => request('type')])) }}" @style(['padding: 9px 13px', 'border-radius: 8px', 'font-size: 13px', 'font-weight: 650', 'text-decoration: none', 'background: #4f46e5' => $activeStatus === $statusValue, 'color: #fff' => $activeStatus === $statusValue, 'background: #fff' => $activeStatus !== $statusValue, 'color: #475569' => $activeStatus !== $statusValue, 'border: 1px solid #e2e8f0' => $activeStatus !== $statusValue])>{{ $statusLabel }} <span style="opacity: .8;">{{ number_format($counts[$statusValue]) }}</span></a>
        @endforeach
    </nav>

    <form method="GET" action="{{ route('admin.reports.index') }}" style="display: flex; flex-wrap: wrap; align-items: center; gap: 9px; margin-bottom: 16px; padding: 13px 15px; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;">
        <input type="hidden" name="status" value="{{ $activeStatus }}">
        <label for="report-type" style="font-size: 13px; font-weight: 600; color: #475569;">Report type</label>
        <select id="report-type" name="type" style="min-width: 170px; padding: 9px 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; font: inherit; font-size: 13px;">
            <option value="">All content</option>
            <option value="resource" @selected(request('type') === 'resource')>Resources</option>
            <option value="comment" @selected(request('type') === 'comment')>Comments</option>
        </select>
        <button type="submit" style="padding: 9px 13px; border: 0; border-radius: 8px; background: #4f46e5; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">Apply filter</button>
        @if(request('type'))<a href="{{ route('admin.reports.index', ['status' => $activeStatus]) }}" style="color: #64748b; font-size: 13px; text-decoration: none;">Clear type</a>@endif
    </form>

    <div style="overflow-x: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 1px 3px rgba(15,23,42,.04);">
        <table style="width: 100%; min-width: 900px; border-collapse: collapse; text-align: left;">
            <thead><tr style="background: #f8fafc; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: .04em;">
                <th style="padding: 13px 15px;">Flagged Item</th><th style="padding: 13px 15px;">Reason &amp; Reporter</th><th style="padding: 13px 15px;">Date Reported</th><th style="padding: 13px 15px;">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($reports as $report)
                    @php
                        $target = $report->reportable;
                        $isResource = $target instanceof \App\Models\Resource;
                        $isComment = $target instanceof \App\Models\Comment;
                        $previewResource = $isResource ? $target : ($isComment ? $target->resource : null);
                        $itemTitle = $isResource ? $target->title : ($isComment ? 'Comment on '.($target->resource?->title ?? 'resource') : 'Deleted content');
                        $excerpt = $isResource ? \Illuminate\Support\Str::limit($target->description ?: $target->original_name, 150) : ($isComment ? \Illuminate\Support\Str::limit($target->body, 150) : 'The reported item is no longer available.');
                    @endphp
                    <tr style="border-top: 1px solid #f1f5f9; vertical-align: top;">
                        <td style="max-width: 440px; padding: 15px;">
                            <div style="font-size: 14px; font-weight: 650; color: #0f172a;">{{ $itemTitle }}</div>
                            <p style="margin: 5px 0 7px; color: #64748b; font-size: 13px; line-height: 1.5;">{{ $excerpt }}</p>
                            @if($previewResource)
                                <a href="{{ route('resources.show', $previewResource) }}" target="_blank" rel="noopener noreferrer" style="color: #4f46e5; font-size: 12px; font-weight: 600; text-decoration: none;">Preview content ↗</a>
                            @endif
                        </td>
                        <td style="padding: 15px;">
                            <div style="margin-bottom: 7px; color: #334155; font-size: 13px; font-weight: 650;">{{ \App\Models\Report::REASONS[$report->reason] ?? ucfirst(str_replace('-', ' ', $report->reason)) }}</div>
                            @if($report->details)<p style="max-width: 260px; margin: 0 0 8px; color: #64748b; font-size: 12px; line-height: 1.45;">{{ \Illuminate\Support\Str::limit($report->details, 120) }}</p>@endif
                            <div style="color: #64748b; font-size: 12px;">Reported by <strong style="color: #334155;">{{ $report->reporter?->name ?? 'Unknown user' }}</strong>@if($report->reporter) · {{ $report->reporter->email }}@endif</div>
                        </td>
                        <td style="padding: 15px; white-space: nowrap; color: #64748b; font-size: 13px;">{{ $report->created_at->diffForHumans() }}</td>
                        <td style="padding: 15px;">
                            @if($report->status === \App\Models\Report::STATUS_OPEN)
                                <div style="display: flex; flex-wrap: wrap; gap: 7px; min-width: 205px;">
                                    <form method="POST" action="{{ route('admin.reports.dismiss', $report) }}" style="margin: 0;">
                                        @csrf @method('PATCH')
                                        <button type="submit" style="padding: 8px 10px; border: 1px solid #a7f3d0; border-radius: 8px; background: #ecfdf5; color: #047857; font-size: 12px; font-weight: 650; cursor: pointer;">Dismiss</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reports.resolve', $report) }}" onsubmit="return confirm('Delete this content and resolve every report for it?')" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="padding: 8px 10px; border: 1px solid #fecaca; border-radius: 8px; background: #fff1f2; color: #be123c; font-size: 12px; font-weight: 650; cursor: pointer;">Delete Content &amp; Resolve</button>
                                    </form>
                                </div>
                            @else
                                <span style="display: inline-block; padding: 5px 9px; border-radius: 999px; background: {{ $report->status === \App\Models\Report::STATUS_RESOLVED ? '#ecfdf5' : '#f1f5f9' }}; color: {{ $report->status === \App\Models\Report::STATUS_RESOLVED ? '#047857' : '#475569' }}; font-size: 12px; font-weight: 650;">{{ ucfirst($report->status) }}</span>
                                @if($report->resolver)<div style="margin-top: 5px; color: #64748b; font-size: 11px;">by {{ $report->resolver->name }}</div>@endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="padding: 42px 16px; text-align: center; color: #64748b; font-size: 14px;">No {{ $activeStatus }} reports match this filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">{{ $reports->links() }}</div>
</div>
@endsection
