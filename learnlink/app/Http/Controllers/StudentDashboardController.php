<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $student = $request->user();
        $classIds = $student->enrolledClasses()->pluck('course_classes.id');

        $notices = Announcement::whereIn('course_class_id', $classIds)
            ->with('courseClass')
            ->latest()
            ->take(12)
            ->get();

        $resources = Resource::published()
            ->where(function ($query) use ($classIds, $student): void {
                $query->whereIn('course_class_id', $classIds)
                    ->orWhereHas('user.studentProfile', fn ($profile) =>
                        $profile->where('department', $student->studentProfile?->department)
                    );
            })
            ->withCount('votes')
            ->orderByDesc('downloads_count')
            ->orderByDesc('votes_count')
            ->take(6)
            ->get();

        $uploads = $student->resources();

        return view('student.dashboard', [
            'notices' => $notices,
            'resources' => $resources,
            'stats' => [
                'uploads' => $uploads->count(),
                'likes' => $uploads->withCount([
                    'votes as positive_votes' => fn ($query) => $query->where('value', 1),
                ])->get()->sum('positive_votes'),
                'downloads' => $uploads->sum('downloads_count'),
            ],
            'platformStats' => [
                'resources' => Resource::published()->count(),
                'members' => User::count(),
                'downloads' => (int) Resource::published()->sum('downloads_count'),
            ],
            'classes' => $student->enrolledClasses()->get(),
        ]);
    }
}
