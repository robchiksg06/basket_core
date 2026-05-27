<?php

namespace App\Http\Controllers;

use App\Models\League;

class LeagueController extends Controller
{
    public function index()
    {
        $leagues = League::all();

        return view('leagues.index', [
            'leagues' => $leagues,
        ]);
    }

    public function show(League $league)
    {
        $league->load(['teams.players']);

        return view('leagues.show', [
            'league' => $league,
            'teams'  => $league->teams,
        ]);
    }
}