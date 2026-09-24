<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Report;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'resolved', 'dismissed'])],
            'type' => ['nullable', Rule::in(['resource', 'comment'])],
        ]);

        $query = Report::query()->with([
            'reporter',
            'resolver',
            'reportable' => function (MorphTo $morphTo): void {
                $morphTo->morphWith([Comment::class => ['resource']]);
            },
        ]);

        $status = $filters['status'] ?? 'pending';
        $query->where('status', $status === 'pending' ? Report::STATUS_OPEN : $status);

        if (! empty($filters['type'])) {
            $query->where('reportable_type', $filters['type'] === 'resource' ? Resource::class : Comment::class);
        }

        return view('admin.reports.index', [
            'reports' => $query->latest()->paginate(15)->withQueryString(),
            'counts' => [
                'pending' => Report::where('status', Report::STATUS_OPEN)->count(),
                'resolved' => Report::where('status', Report::STATUS_RESOLVED)->count(),
                'dismissed' => Report::where('status', Report::STATUS_DISMISSED)->count(),
            ],
            'activeStatus' => $status,
        ]);
    }

    public function dismiss(Report $report): RedirectResponse
    {
        $this->authorize('update', $report);
        $now = now();
        $adminId = auth()->id();
        $report->update([
            'status' => Report::STATUS_DISMISSED,
            'resolved_at' => $now,
            'resolved_by' => $adminId,
            'actioned_by' => $adminId,
        ]);

        return back()->with('status', 'Report dismissed; the flagged content remains available.');
    }

    public function resolve(Report $report): RedirectResponse
    {
        $this->authorize('update', $report);
        $target = $report->reportable;
        abort_if($target === null, 404, 'The reported content no longer exists.');

        $reportedType = $report->reportable_type;
        $reportedId = $report->reportable_id;
        $filePath = $target instanceof Resource ? $target->file_path : null;
        $now = now();
        $adminId = auth()->id();

        DB::transaction(function () use ($target, $reportedType, $reportedId, $now, $adminId): void {
            $target->delete();

            Report::query()
                ->where('reportable_type', $reportedType)
                ->where('reportable_id', $reportedId)
                ->update([
                    'status' => Report::STATUS_RESOLVED,
                    'resolved_at' => $now,
                    'resolved_by' => $adminId,
                    'actioned_by' => $adminId,
                    'updated_at' => $now,
                ]);
        });

        if ($filePath) {
            Storage::disk('public')->delete($filePath);
        }

        return back()->with('status', 'Reported content deleted and all duplicate reports resolved.');
    }
}
