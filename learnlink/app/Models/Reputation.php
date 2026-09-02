<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reputation extends Model
{
    public const REASON_UPLOADED = 'resource_uploaded';
    public const REASON_UPVOTE = 'upvote_received';
    public const REASON_DOWNVOTE = 'downvote_received';
    public const REASON_COMMENT = 'comment_posted';
    public const REASON_ADJUSTMENT = 'manual_adjustment';

    /** Ledger rows are immutable; only created_at exists in the table. */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'resource_id',
        'points',
        'reason',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
