<?php
namespace App\Http\Controllers\Admin; use App\Http\Controllers\Controller; use App\Models\Report; use App\Models\Resource; use Illuminate\Http\Request; use Illuminate\Support\Facades\Storage;
class AdminResourceController extends Controller
{
    public function reports()
    {
        $this->authorize('viewAny', Report::class);
        return view('admin.reports.index', ['reports' => Report::with(['user', 'reportable'])->where('status', Report::STATUS_OPEN)->latest()->paginate(20)]);
    }

    public function keep(Report $report)
    {
        $this->authorize('update', $report);
        $report->update(['status' => Report::STATUS_DISMISSED, 'actioned_by' => request()->user()->id]);
        return back()->with('status', 'Report dismissed; reported content remains published.');
    }

    public function remove(Report $report)
    {
        $this->authorize('update', $report);
        $target = $report->reportable;
        if ($target instanceof Resource) {
            Storage::disk('public')->delete($target->file_path);
            $target->update(['status' => 'removed']);
        } elseif ($target instanceof \App\Models\Comment) {
            $target->delete();
        }
        $report->update(['status' => Report::STATUS_RESOLVED, 'actioned_by' => request()->user()->id]);
        return back()->with('status', 'Reported content removed and report resolved.');
    }

    public function warn(Request $request, Report $report)
    {
        $this->authorize('update', $report);
        $target = $report->reportable;
        $owner = $target instanceof Resource ? $target->user : ($target instanceof \App\Models\Comment ? $target->user : null);
        $report->update(['status' => Report::STATUS_RESOLVED, 'actioned_by' => $request->user()->id]);
        return back()->with('status', 'Warning recorded for '.($owner?->email ?? 'the content owner').'. Configure mail delivery to send external warnings.');
    }
}
