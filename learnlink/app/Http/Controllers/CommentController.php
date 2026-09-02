<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Services\ReputationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(ReputationService $reputation)
    {
        $this->reputation = $reputation;
    }

    /**
     * @var \App\Services\ReputationService
     */
    protected $reputation;

    /** Persist a new comment (was comment_process.php). */
    public function store(Request $request, Resource $resource)
    {
        if ($resource->status !== 'published') {
            abort(404);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:2000'],
        ]);

        $resource->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $validated['body'],
        ]);

        // Commenters earn a small reputation boost.
        $this->reputation->forComment($request->user()->id);

        return redirect()
            ->route('resources.show', $resource)
            ->with('status', 'Comment added. +1 reputation.');
    }
}
