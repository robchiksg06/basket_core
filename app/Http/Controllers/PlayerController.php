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
    
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        $search = $request->get('search');
    
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

    public function publicView()
    {
        // Ja ielogojies un esi admins, redirect uz tabulu
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('players.index');
        }
    
        $players = Player::orderBy('name')->get();
        return view('players.cards', compact('players'));
    }
    
          

}
