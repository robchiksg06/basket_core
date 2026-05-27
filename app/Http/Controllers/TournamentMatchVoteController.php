<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\TournamentGroupMatch;
use App\Models\TournamentMatchVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentMatchVoteController extends Controller
{
    public function vote(Request $request, Tournament $tournament)
    {
        $request->validate([
            'match_id'       => ['required', 'integer'],
            'match_type'     => ['required', 'in:knockout,group'],
            'voted_team_id'  => ['required', 'exists:tournament_teams,id'],
        ]);

        // Verify the match belongs to this tournament and the team is one of the two sides
        if ($request->match_type === 'knockout') {
            $match = TournamentMatch::where('id', $request->match_id)
                ->where('tournament_id', $tournament->id)
                ->firstOrFail();
            abort_unless(
                in_array($request->voted_team_id, [$match->team1_id, $match->team2_id]),
                422
            );
        } else {
            $match = TournamentGroupMatch::where('id', $request->match_id)
                ->whereHas('group', fn($q) => $q->where('tournament_id', $tournament->id))
                ->firstOrFail();
            abort_unless(
                in_array($request->voted_team_id, [$match->team1_id, $match->team2_id]),
                422
            );
        }

        TournamentMatchVote::updateOrCreate(
            [
                'match_id'   => $request->match_id,
                'match_type' => $request->match_type,
                'user_id'    => Auth::id(),
            ],
            [
                'tournament_id'  => $tournament->id,
                'voted_team_id'  => $request->voted_team_id,
            ]
        );

        return back()->with('success', 'Balsojums saglabāts!');
    }
}
