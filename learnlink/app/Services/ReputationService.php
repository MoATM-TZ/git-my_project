<?php

namespace App\Services;

use App\Models\Reputation;
use App\Models\Resource;
use App\Models\User;

class ReputationService
{
    /** Points awarded for a new resource upload. */
    public const UPLOAD_POINTS = 10;

    /** Points awarded to a resource owner when someone upvotes. */
    public const UPVOTE_POINTS = 2;

    /** Points deducted from a resource owner when someone downvotes. */
    public const DOWNVOTE_POINTS = -1;

    /** Points awarded to a user for posting a comment. */
    public const COMMENT_POINTS = 1;

    /**
     * Record a ledged entry and keep the cached users.reputation_points
     * column in sync. Wrap in a transaction when precision matters.
     */
    public function award(int $userId, int $points, string $reason, ?int $resourceId = null): Reputation
    {
        $entry = Reputation::create([
            'user_id'     => $userId,
            'resource_id' => $resourceId,
            'points'      => $points,
            'reason'      => $reason,
        ]);

        User::whereKey($userId)->increment('reputation_points', $points);

        return $entry;
    }

    /** Convenience wrapper for uploads. */
    public function forUpload(Resource $resource): Reputation
    {
        return $this->award(
            $resource->user_id,
            self::UPLOAD_POINTS,
            Reputation::REASON_UPLOADED,
            $resource->id,
        );
    }

    /** Convenience wrapper for a received upvote. */
    public function forUpvote(Resource $resource): Reputation
    {
        return $this->award(
            $resource->user_id,
            self::UPVOTE_POINTS,
            Reputation::REASON_UPVOTE,
            $resource->id,
        );
    }

    /** Convenience wrapper for a received downvote. */
    public function forDownvote(Resource $resource): Reputation
    {
        return $this->award(
            $resource->user_id,
            self::DOWNVOTE_POINTS,
            Reputation::REASON_DOWNVOTE,
            $resource->id,
        );
    }

    /** Convenience wrapper for posting a comment. */
    public function forComment(int $userId): Reputation
    {
        return $this->award($userId, self::COMMENT_POINTS, Reputation::REASON_COMMENT);
    }
}
