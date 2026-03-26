<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function show($league)
{
    // Atrod komandas, kas pieder šai līgai
    $teams = \App\Models\Team::where('league', $league)->get();

    // Atgriež view ar komandām
    return view('leagues.show', [
        'league' => $league,
        'teams' => $teams,
    ]);
}

}


