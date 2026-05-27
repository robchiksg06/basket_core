<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentGroupMatch;
use App\Models\TournamentMatch;
use App\Models\TournamentTeam;
use App\Models\TournamentMatchVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function index()
    {
        $search = request('search');
        $mine   = request('filter') === 'mine';

        $tournaments = Tournament::withCount('teams')
            ->when($mine,
                fn($q) => $q->where('user_id', Auth::id()),
                fn($q) => $q->where(function ($q) {
                    $q->where('is_public', true)->orWhere('user_id', Auth::id());
                })
            )
            ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->latest()
            ->get();

        return view('tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'format'            => 'required|in:single_elimination,group_knockout',
            'groups_count'      => 'required_if:format,group_knockout|nullable|integer|min:2|max:8',
            'advance_per_group' => 'required_if:format,group_knockout|nullable|integer|min:1|max:4',
            'teams'             => 'required|array|min:2|max:64',
            'teams.*'           => 'nullable|string|max:100',
        ]);

        $teamNames = array_values(array_filter($request->teams, fn($t) => trim($t) !== ''));

        if (count($teamNames) < 2) {
            return back()->withErrors(['teams' => 'Vajag vismaz 2 komandas.'])->withInput();
        }

        $tournament = Tournament::create([
            'name'              => $request->name,
            'description'       => $request->description,
            'status'            => 'draft',
            'user_id'           => Auth::id(),
            'is_public'         => $request->boolean('is_public'),
            'format'            => $request->format,
            'groups_count'      => $request->groups_count,
            'advance_per_group' => $request->advance_per_group,
        ]);

        foreach ($teamNames as $seed => $name) {
            TournamentTeam::create([
                'tournament_id' => $tournament->id,
                'name'          => trim($name),
                'seed'          => $seed + 1,
            ]);
        }

        if ($tournament->format === 'group_knockout') {
            $tournament->generateGroupStage();
        } else {
            $tournament->generateBracket();
        }

        return redirect()->route('tournaments.show', $tournament)
            ->with('success', 'Turnīrs izveidots!');
    }

    public function show(Tournament $tournament)
    {
        $tournament->load(['teams', 'matches.team1', 'matches.team2', 'matches.winner']);
        $rounds      = $tournament->matches->groupBy('round');
        $totalRounds = $rounds->keys()->max() ?? 0;

        $groups = null;
        if ($tournament->format === 'group_knockout') {
            $groups = $tournament->groups()
                ->with(['groupTeams.team', 'matches.team1', 'matches.team2', 'matches.winner'])
                ->get();
        }

        $tournamentStats = \Illuminate\Support\Facades\DB::table('game_players as gp')
            ->join('games as g', 'g.id', '=', 'gp.game_id')
            ->join('tournament_matches as tm', 'tm.id', '=', 'g.tournament_match_id')
            ->leftJoin('game_events as ge', 'ge.game_player_id', '=', 'gp.id')
            ->where('tm.tournament_id', $tournament->id)
            ->where('g.status', 'finished')
            ->groupBy('gp.player_name')
            ->select([
                'gp.player_name',
                \Illuminate\Support\Facades\DB::raw("COUNT(DISTINCT gp.game_id) as games"),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN ge.event_type='shot' AND ge.shot_type='ft'  AND ge.is_made=1 THEN 1
                                                          WHEN ge.event_type='shot' AND ge.shot_type='2pt' AND ge.is_made=1 THEN 2
                                                          WHEN ge.event_type='shot' AND ge.shot_type='3pt' AND ge.is_made=1 THEN 3
                                                          ELSE 0 END) as pts"),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN ge.event_type='shot' AND ge.shot_type='3pt' AND ge.is_made=1 THEN 1 ELSE 0 END) as threes"),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN ge.event_type='rebound' THEN 1 ELSE 0 END) as reb"),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN ge.event_type='assist'  THEN 1 ELSE 0 END) as ast"),
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN ge.event_type='steal'   THEN 1 ELSE 0 END) as stl"),
            ])
            ->orderByDesc('pts')
            ->get();

        $matchVotes = TournamentMatchVote::where('tournament_id', $tournament->id)->get();
        $matchVoteCounts = $matchVotes
            ->groupBy(fn($v) => $v->match_type . ':' . $v->match_id)
            ->map(fn($group) => $group->groupBy('voted_team_id')->map->count());
        $userMatchVotes = Auth::check()
            ? $matchVotes->where('user_id', Auth::id())->keyBy(fn($v) => $v->match_type . ':' . $v->match_id)
            : collect();

        return view('tournaments.show', compact('tournament', 'rounds', 'totalRounds', 'groups', 'matchVoteCounts', 'userMatchVotes', 'tournamentStats'));
    }

    // ── Knockout match result ────────────────────────────────────────────────

    public function result(Request $request, Tournament $tournament, TournamentMatch $match)
    {
        abort_if($tournament->status !== 'active', 403);
        abort_if((bool) $match->winner_id, 403);
        abort_if(!$match->team1_id || !$match->team2_id, 422);

        $request->validate([
            'team1_score' => 'required|integer|min:0',
            'team2_score' => 'required|integer|min:0',
        ]);

        $winnerId = $request->team1_score >= $request->team2_score
            ? $match->team1_id : $match->team2_id;

        $match->update([
            'team1_score' => $request->team1_score,
            'team2_score' => $request->team2_score,
            'winner_id'   => $winnerId,
        ]);

        $tournament->advanceWinner($match);

        if ($tournament->matches()->whereNull('winner_id')->count() === 0) {
            $tournament->update(['status' => 'completed']);
        }

        return redirect()->route('tournaments.show', $tournament)
            ->with('success', 'Rezultāts saglabāts!');
    }

    // ── Group match result ───────────────────────────────────────────────────

    public function groupResult(Request $request, Tournament $tournament, TournamentGroupMatch $match)
    {
        abort_if($tournament->status !== 'active', 403);
        abort_if((bool) $match->winner_id, 403);

        $request->validate([
            'team1_score' => 'required|integer|min:0',
            'team2_score' => 'required|integer|min:0',
        ]);

        $winnerId = $request->team1_score >= $request->team2_score
            ? $match->team1_id : $match->team2_id;

        $match->update([
            'team1_score' => $request->team1_score,
            'team2_score' => $request->team2_score,
            'winner_id'   => $winnerId,
        ]);

        return redirect()->route('tournaments.show', $tournament)
            ->with('success', 'Rezultāts saglabāts!');
    }

    // ── Generate knockout from groups ────────────────────────────────────────

    public function generateKnockout(Tournament $tournament)
    {
        abort_if($tournament->format !== 'group_knockout', 403);
        abort_unless($tournament->groupStageComplete(), 422);

        $tournament->generateKnockoutFromGroups();

        return redirect()->route('tournaments.show', $tournament)
            ->with('success', 'Atzarojums ģenerēts!');
    }

    // ── Visibility / Delete ──────────────────────────────────────────────────

    public function toggleVisibility(Tournament $tournament)
    {
        abort_if($tournament->user_id !== Auth::id() && !Auth::user()?->isAdmin(), 403);
        $tournament->update(['is_public' => !$tournament->is_public]);
        return back()->with('success', $tournament->is_public ? 'Turnīrs tagad ir publisks.' : 'Turnīrs tagad ir privāts.');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        return redirect()->route('tournaments.index')
            ->with('success', 'Turnīrs dzēsts.');
    }
}
