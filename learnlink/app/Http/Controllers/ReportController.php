<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Resource;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /** File a moderation report (was report_process.php). */
    public function store(Request $request, Resource $resource)
    {
        $this->authorize('create', Report::class);
        if ($resource->status !== 'published') {
            abort(404);
        }

        $validated = $request->validate([
            'reason'  => ['required', 'string', 'in:'.implode(',', array_keys(Report::REASONS))],
            'details' => ['nullable', 'string', 'max:2000'],
        ]);

        $resource->reports()->create([
            'user_id'     => $request->user()->id,
            'reason'      => $validated['reason'],
            'details'     => $validated['details'] ?? null,
        ]);

        return redirect()
            ->route('resources.show', $resource)
            ->with('status', 'Thank you. The report has been submitted for review.');
    }

    public function storeComment(Request $request, Comment $comment)
    {
        $this->authorize('create', Report::class);
        $validated = $request->validate([
            'reason' => ['required', 'string', 'in:'.implode(',', array_keys(Report::REASONS))],
            'details' => ['nullable', 'string', 'max:2000'],
        ]);
        $comment->reports()->create([
            'user_id' => $request->user()->id,
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
        ]);
        return back()->with('status', 'Thank you. The report has been submitted for review.');
    }
}
