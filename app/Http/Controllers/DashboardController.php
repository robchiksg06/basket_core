<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $recentGames = Game::where('user_id', $user->id)
            ->whereNull('tournament_match_id')
            ->latest()
            ->limit(5)
            ->get();

        $activeTournaments = Tournament::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->limit(4)
            ->get();

        $completedTournaments = Tournament::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->limit(3)
            ->get();

        $totalGames       = Game::where('user_id', $user->id)->whereNull('tournament_match_id')->count();
        $totalTournaments = Tournament::where('user_id', $user->id)->count();
        $likedPlayers     = $user->likedPlayers()->count();
        $followingIds     = $user->following()->pluck('users.id');
        $followingCount   = $followingIds->count();
        $followersCount   = $user->followers()->count();

        $feedGames = Game::with('user')
            ->whereIn('user_id', $followingIds)
            ->where('is_public', true)
            ->whereNull('tournament_match_id')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'recentGames',
            'activeTournaments',
            'completedTournaments',
            'totalGames',
            'totalTournaments',
            'likedPlayers',
            'followingCount',
            'followersCount',
            'feedGames',
        ));
    }
}
