<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;



class PlayerController extends Controller
{
    public function index(Request $request)
    {
        // Ja parasts lietotājs, novirza uz kartiņu skatu
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect()->route('players.public');
        }
    
        $sort      = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        $search    = $request->input('search');
    
        $players = Player::query();
    
        if ($search) {
            $players->where('name', 'LIKE', $search . '%');
        }
    
        $players = $players->orderBy($sort, $direction)->get();
    
        return view('players.index', compact('players', 'sort', 'direction', 'search'));
    }
    

    public function create()
    {
        return view('players.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'height' => 'nullable|numeric',
            'team' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('players', 'public');
        }
        Player::create($validated);

        return redirect()->route('players.index')->with('success', 'Spēlētājs pievienots!');
    }

    public function edit(Player $player)
    {
        return view('players.edit', compact('player'));
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'height' => 'nullable|numeric',
            'team' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Dzēš iepriekšējo bildi, ja ir
            if ($player->image) {
                Storage::disk('public')->delete($player->image);
            }
    
            $validated['image'] = $request->file('image')->store('players', 'public');
        }

        $player->update($validated);

        return redirect()->route('players.index')->with('success', 'Spēlētājs atjaunināts!');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Spēlētājs dzēsts!');
    }

    public function compare(Request $request)
    {
        $players = Player::orderBy('name')->get(['id', 'name', 'position', 'team', 'image', 'height']);

        $player1 = $request->filled('player1') ? Player::with('seasons')->find($request->player1) : null;
        $player2 = $request->filled('player2') ? Player::with('seasons')->find($request->player2) : null;

        return view('players.compare', compact('players', 'player1', 'player2'));
    }

    public function publicView(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('players.index');
        }

        $search   = $request->input('search');
        $position = $request->input('position');
        $team     = $request->input('team');

        $players = Player::query()
            ->when($search,   fn($q) => $q->where('name', 'LIKE', '%' . $search . '%'))
            ->when($position, fn($q) => $q->where('position', $position))
            ->when($team,     fn($q) => $q->where('team', $team))
            ->orderBy('name')
            ->get();

        $positions = Player::whereNotNull('position')->where('position', '!=', '')->distinct()->orderBy('position')->pluck('position');
        $teams     = Player::whereNotNull('team')->where('team', '!=', '')->distinct()->orderBy('team')->pluck('team');

        return view('players.cards', compact('players', 'positions', 'teams', 'search', 'position', 'team'));
    }
    
          

}
