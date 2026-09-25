<?php

namespace App\Policies;

use App\Models\CourseClass;
use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role, ['student', 'lecturer', 'admin'], true);
    }

    public function delete(User $user, Resource $resource): bool
    {
        return $user->role === 'admin' || (int) $resource->user_id === (int) $user->id;
    }

    public function vote(User $user, Resource $resource): bool
    {
        return in_array($user->role, ['student', 'lecturer'], true)
            && (int) $resource->user_id !== (int) $user->id
            && $resource->status === 'published';
    }

    public function uploadToClass(User $user, CourseClass $courseClass): bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->role === 'lecturer') {
            return (int) $courseClass->lecturer_id === (int) $user->id && ! $courseClass->is_archived;
        }
        return $user->role === 'student'
            && $user->enrolledClasses()->whereKey($courseClass->getKey())->exists();
    }
}
