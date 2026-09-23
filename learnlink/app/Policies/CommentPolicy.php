<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function create(User $user): bool
    {
        return $user->is_active && in_array($user->role, ['student', 'lecturer'], true);
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->role === 'admin'
            || (int) $comment->user_id === (int) $user->id
            || ($user->role === 'lecturer'
                && (int) ($comment->resource?->courseClass?->lecturer_id ?? 0) === (int) $user->id);
    }
}
