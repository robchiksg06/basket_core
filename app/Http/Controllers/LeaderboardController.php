<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $tab = $request->get('tab', 'pts');

        $all = DB::table('game_players as gp')
            ->join('games as g', 'g.id', '=', 'gp.game_id')
            ->leftJoin('game_events as ge', 'ge.game_player_id', '=', 'gp.id')
            ->leftJoin('tournament_matches as tm', 'tm.id', '=', 'g.tournament_match_id')
            ->leftJoin('tournaments as t', 't.id', '=', 'tm.tournament_id')
            ->where('g.status', 'finished')
            ->where(function ($q) {
                $q->where('g.is_public', true)
                  ->orWhere('t.is_public', true);
            })
            ->groupBy('gp.player_name')
            ->select([
                'gp.player_name',
                DB::raw("COUNT(DISTINCT gp.game_id) as games"),
                DB::raw("SUM(CASE WHEN ge.event_type='shot' AND ge.shot_type='ft'  AND ge.is_made=1 THEN 1
                               WHEN ge.event_type='shot' AND ge.shot_type='2pt' AND ge.is_made=1 THEN 2
                               WHEN ge.event_type='shot' AND ge.shot_type='3pt' AND ge.is_made=1 THEN 3
                               ELSE 0 END) as pts"),
                DB::raw("SUM(CASE WHEN ge.event_type='shot' AND ge.shot_type='3pt' AND ge.is_made=1 THEN 1 ELSE 0 END) as threes"),
                DB::raw("SUM(CASE WHEN ge.event_type='rebound' THEN 1 ELSE 0 END) as reb"),
                DB::raw("SUM(CASE WHEN ge.event_type='assist'  THEN 1 ELSE 0 END) as ast"),
                DB::raw("SUM(CASE WHEN ge.event_type='steal'   THEN 1 ELSE 0 END) as stl"),
            ])
            ->get();

        $sortKey = match($tab) {
            'threes' => 'threes',
            'reb'    => 'reb',
            'ast'    => 'ast',
            'stl'    => 'stl',
            default  => 'pts',
        };

        $leaders = $all
            ->filter(fn($r) => $r->$sortKey > 0)
            ->sortByDesc($sortKey)
            ->values()
            ->take(15);

        return view('leaderboard', compact('leaders', 'tab'));
    }
}
