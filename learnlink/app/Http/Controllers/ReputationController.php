<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Was the legacy reputation.php standings page: shows the top contributors
 * by reputation and (for signed-in users) their own earnings history.
 */
class ReputationController extends Controller
{
    public function index(Request $request)
    {
        $leaderboard = User::withCount(['resources', 'comments'])
            ->orderByDesc('reputation_points')
            ->limit(20)
            ->get(['id', 'name', 'reputation_points']);

        $myHistory = $request->user()
            ? $request->user()->reputations()->with('resource:id,title,slug')->latest('created_at')->limit(25)->get()
            : collect();

        return view('reputation.index', [
            'leaderboard' => $leaderboard,
            'myHistory'   => $myHistory,
        ]);
    }
}
