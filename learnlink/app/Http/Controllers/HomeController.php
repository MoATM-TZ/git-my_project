<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    /** Landing dashboard — was the legacy index.php. */
    public function __invoke(): View
    {
        $recent = Resource::published()
            ->with('user:id,name')
            ->latest()
            ->limit(6)
            ->get();

        $popular = Resource::published()
            ->with('user:id,name')
            ->orderByDesc('download_count')
            ->limit(6)
            ->get();

        // Compute a live score for the "trending" rail from the vote table.
        $trending = Resource::published()
            ->with(['user:id,name', 'votes:resource_id,value'])
            ->latest()
            ->limit(50)
            ->get()
            ->sortByDesc(fn (Resource $r) => $r->score())
            ->take(6)
            ->values();

        $topContributors = User::withCount('resources')
            ->orderByDesc('reputation_points')
            ->limit(5)
            ->get(['id', 'name', 'reputation_points']);

        $categories = Resource::published()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->pluck('total', 'category');

        return view('home', [
            'recent'          => $recent,
            'popular'         => $popular,
            'trending'        => $trending,
            'topContributors' => $topContributors,
            'categories'      => $categories,
        ]);
    }
}
