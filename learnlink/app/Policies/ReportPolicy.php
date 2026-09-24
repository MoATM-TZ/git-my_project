<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Determine whether the user can view any reports.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || (bool) $user->is_admin;
    }

    /**
     * Determine whether the user can view a specific report.
     */
    public function view(User $user, Report $report): bool
    {
        return $user->role === 'admin' || (bool) $user->is_admin;
    }

    /**
     * Determine whether the user can create reports.
     */
    public function create(User $user): bool
    {
        return $user->is_active && $user->approval_status === 'approved';
    }

    /**
     * Determine whether the user can update/action a report.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->role === 'admin' || (bool) $user->is_admin;
    }

    /**
     * Determine whether the user can delete a report.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->role === 'admin' || (bool) $user->is_admin;
    }
}
