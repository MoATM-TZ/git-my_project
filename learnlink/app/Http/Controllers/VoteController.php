<?php

namespace App\Http\Controllers;

use App\Models\Reputation;
use App\Models\Resource;
use App\Models\Vote;
use App\Services\ReputationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Replaces vote_process.php. One vote per (user, resource):
 *   - voting the same direction again removes the vote
 *   - voting the opposite direction flips it
 * The owner's cached reputation is adjusted by the exact delta of each
 * transition so users.reputation_points always mirrors the ledger.
 */
class VoteController extends Controller
{
    public function __construct(ReputationService $reputation)
    {
        $this->reputation = $reputation;
    }

    /**
     * @var \App\Services\ReputationService
     */
    protected $reputation;

    public function store(Request $request, Resource $resource)
    {
        $this->authorize('vote', $resource);
        $request->validate([
            'direction' => ['required', 'in:up,down'],
        ]);

        if ($resource->status !== 'published') {
            abort(404);
        }

        $value = $request->input('direction') === 'up' ? Vote::UP : Vote::DOWN;

        [$score, $myVote] = DB::transaction(function () use ($request, $resource, $value) {
            $existing = Vote::where('user_id', $request->user()->id)
                ->where('resource_id', $resource->id)
                ->first();

            if (!$existing) {
                // Fresh vote.
                Vote::create([
                    'user_id'     => $request->user()->id,
                    'resource_id' => $resource->id,
                    'value'       => $value,
                ]);

                if ($value === Vote::UP) {
                    $this->reputation->forUpvote($resource);
                } else {
                    $this->reputation->forDownvote($resource);
                }
            } elseif ((int) $existing->value === $value) {
                // Same direction again -> retract the vote and refund points.
                $refund = $value === Vote::UP ? -ReputationService::UPVOTE_POINTS : -ReputationService::DOWNVOTE_POINTS;
                $this->reputation->award($resource->user_id, $refund, Reputation::REASON_ADJUSTMENT, $resource->id);
                $existing->delete();
                $value = 0;
            } else {
                // Flip: undo the old delta, apply the new one.
                $undoOld = $existing->value === Vote::UP ? -ReputationService::UPVOTE_POINTS : -ReputationService::DOWNVOTE_POINTS;
                $this->reputation->award($resource->user_id, $undoOld, Reputation::REASON_ADJUSTMENT, $resource->id);

                if ($value === Vote::UP) {
                    $this->reputation->forUpvote($resource);
                } else {
                    $this->reputation->forDownvote($resource);
                }

                $existing->update(['value' => $value]);
            }

            $freshScore = $resource->votes()
                ->selectRaw('COALESCE(SUM(value), 0) as total')
                ->value('total');

            return [(int) $freshScore, (int) $value];
        });

        if ($request->expectsJson()) {
            return response()->json([
                'score'   => $score,
                'my_vote' => $myVote,
            ]);
        }

        return redirect()->route('resources.show', $resource);
    }
}
