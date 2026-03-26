<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $league = $request->input('league');
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
    
        // Iegūstam unikālās līgas izvēlnei
        $leagues = Team::select('league')->distinct()->pluck('league');
    
        // Meklēšana un filtrēšana
        $teams = Team::query()
            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($league, fn($query) => $query->where('league', $league))
            ->orderBy($sort, $direction)
            ->get();
    
        return view('teams.index', compact('teams', 'search', 'sort', 'direction', 'leagues'));
    }
    

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'league' => 'nullable|string|max:255',
        ]);

        Team::create($validated);

        return redirect()->route('teams.index')->with('success', 'Komanda pievienota!');
    }

    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'league' => 'nullable|string|max:255',
        ]);

        $team->update($validated);

        return redirect()->route('teams.index')->with('success', 'Komanda atjaunināta!');
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Komanda dzēsta!');
    }
}


