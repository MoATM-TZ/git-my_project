<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\Report;
use App\Models\Resource;
use App\Models\User;
use App\Notifications\AccountApprovedNotification;
use App\Notifications\AccountRejectedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'students' => User::where('role', 'student')->count(),
                'lecturers' => User::where('role', 'lecturer')->count(),
                'classes' => CourseClass::where('is_archived', false)->count(),
                'uploads' => Resource::count(),
                'downloads' => Resource::sum('downloads_count'),
                'reports' => Report::where('status', Report::STATUS_OPEN)->count(),
                'approvals' => User::where('approval_status', 'pending')->where('is_active', true)->count(),
            ],
        ]);
    }

    public function approvals(): View
    {
        $users = User::query()
            ->with(['studentProfile', 'lecturerProfile'])
            ->where('approval_status', 'pending')
            ->where('is_active', true)
            ->oldest()
            ->paginate(20);

        return view('admin.approvals.index', compact('users'));
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        $approvedUser = DB::transaction(function () use ($request, $user): User {
            $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            abort_unless($lockedUser->approval_status === 'pending' && $lockedUser->is_active, 409);

            $lockedUser->update([
                'approval_status' => 'approved',
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);

            return $lockedUser;
        });

        $approvedUser->notify(new AccountApprovedNotification());

        return back()->with('status', $approvedUser->name.' has been approved.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        $rejectedUser = DB::transaction(function () use ($user): User {
            $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            abort_unless($lockedUser->approval_status === 'pending' && $lockedUser->is_active, 409);

            $lockedUser->update([
                'approval_status' => 'rejected',
                'approved_by' => null,
                'approved_at' => null,
            ]);

            return $lockedUser;
        });

        $rejectedUser->notify(new AccountRejectedNotification());

        return back()->with('status', $rejectedUser->name.' has been rejected.');
    }
}
