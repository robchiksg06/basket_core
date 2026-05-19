<?php

namespace App\Http\Controllers;

use App\Models\League;
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

        $leagues = League::orderBy('name')->pluck('name', 'id');

        $teamsQuery = Team::with('leagues')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($league, function ($query) use ($league) {
                $query->whereHas('leagues', function ($q) use ($league) {
                    $q->where('leagues.id', $league);
                });
            });

        if (in_array($sort, ['name', 'country'])) {
            $teamsQuery->orderBy($sort, $direction);
        } else {
            $teamsQuery->orderBy('name', 'asc');
        }

        $teams = $teamsQuery->get();

        return view('teams.index', compact('teams', 'search', 'sort', 'direction', 'leagues', 'league'));
    }

    public function show(Team $team)
    {
        $team->load(['leagues', 'players']);

        return view('teams.show', compact('team'));
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