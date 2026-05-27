<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentVoteController extends Controller
{
    public function vote(Request $request, Tournament $tournament)
    {
        $request->validate([
            'tournament_team_id' => ['required', 'exists:tournament_teams,id'],
        ]);

        // Pārliecinās ka komanda pieder šim turnīram
        $teamBelongs = $tournament->teams()->where('id', $request->tournament_team_id)->exists();
        abort_unless($teamBelongs, 422);

        TournamentVote::updateOrCreate(
            ['tournament_id' => $tournament->id, 'user_id' => Auth::id()],
            ['tournament_team_id' => $request->tournament_team_id]
        );

        return back()->with('success', 'Tavs balsojums ir saglabāts!');
    }
}
