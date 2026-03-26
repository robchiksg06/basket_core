<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameEvent;
use App\Models\GamePlayer;
use Illuminate\Http\Request;

class GameProtocolController extends Controller
{
    public function index()
    {
        $games = Game::query()
            ->when(auth()->check(), function ($query) {
                $query->where(function ($q) {
                    $q->where('is_public', true)
                        ->orWhere('user_id', auth()->id());
                });
            }, function ($query) {
                $query->where('is_public', true);
            })
            ->latest()
            ->get();

        return view('games.index', compact('games'));
    }

    public function create()
    {
        return view('games.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'game_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'home_team_name' => ['required', 'string', 'max:255'],
            'away_team_name' => ['required', 'string', 'max:255'],
            'home_players' => ['required', 'array', 'min:1'],
            'away_players' => ['required', 'array', 'min:1'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        $game = Game::create([
            'title' => $validated['title'] ?? null,
            'game_date' => $validated['game_date'] ?? null,
            'location' => $validated['location'] ?? null,
            'home_team_name' => $validated['home_team_name'],
            'away_team_name' => $validated['away_team_name'],
            'status' => 'live',
            'user_id' => auth()->id(),
            'is_public' => $request->boolean('is_public'),
            'court_x' => $validated['court_x'] ?? null,
            'court_y' => $validated['court_y'] ?? null,
        ]);

        foreach ($request->home_players as $player) {
            if (!empty($player['player_name'])) {
                GamePlayer::create([
                    'game_id' => $game->id,
                    'team_side' => 'home',
                    'player_name' => $player['player_name'],
                    'jersey_number' => $player['jersey_number'] ?? null,
                ]);
            }
        }

        foreach ($request->away_players as $player) {
            if (!empty($player['player_name'])) {
                GamePlayer::create([
                    'game_id' => $game->id,
                    'team_side' => 'away',
                    'player_name' => $player['player_name'],
                    'jersey_number' => $player['jersey_number'] ?? null,
                ]);
            }
        }

        return redirect()->route('games.show', $game)
            ->with('success', 'Spēle veiksmīgi izveidota.');
    }

    public function show(Game $game)
    {
        $this->checkGameAccess($game);

        $game->load([
            'players.events',
            'events.player',
        ]);

        $homePlayers = $game->players->where('team_side', 'home')->values();
        $awayPlayers = $game->players->where('team_side', 'away')->values();

        $quarterScores = $this->buildQuarterScores($game);

        return view('games.show', compact('game', 'homePlayers', 'awayPlayers', 'quarterScores'));
    }

    public function addEvent(Request $request, Game $game)
    {
        $this->checkGameOwnerOrAdmin($game);

        $validated = $request->validate([
            'game_player_id' => ['required', 'exists:game_players,id'],
            'quarter' => ['required', 'integer', 'min:1', 'max:4'],
            'shot_type' => ['required', 'in:ft,2pt,3pt'],
            'is_made' => ['required', 'in:0,1'],
            'court_x' => ['nullable', 'numeric'],
            'court_y' => ['nullable', 'numeric'],
        ]);

        $player = GamePlayer::where('game_id', $game->id)
            ->findOrFail($validated['game_player_id']);

        GameEvent::create([
            'game_id' => $game->id,
            'game_player_id' => $player->id,
            'team_side' => $player->team_side,
            'quarter' => $validated['quarter'],
            'shot_type' => $validated['shot_type'],
            'is_made' => (bool) $validated['is_made'],
            'court_x' => $validated['court_x'] ?? null,
            'court_y' => $validated['court_y'] ?? null,
        ]);

        return redirect()->route('games.show', $game)
            ->with('success', 'Notikums pievienots.');
    }

    public function deleteEvent(Game $game, GameEvent $event)
    {
        $this->checkGameOwnerOrAdmin($game);

        abort_unless($event->game_id === $game->id, 404);

        $event->delete();

        return redirect()->route('games.show', $game)
            ->with('success', 'Notikums dzēsts.');
    }

    public function finish(Game $game)
    {
        $this->checkGameOwnerOrAdmin($game);

        $game->update([
            'status' => 'finished',
        ]);

        return redirect()->route('games.show', $game)
            ->with('success', 'Spēle pabeigta.');
    }

    public function destroy(Game $game)
    {
        $this->checkGameOwnerOrAdmin($game);

        $game->delete();

        return redirect()
            ->route('games.index')
            ->with('success', 'Spēle izdzēsta.');
    }
        public function toggleVisibility(Game $game)
    {
        $this->checkGameOwnerOrAdmin($game);

        $game->update([
            'is_public' => !$game->is_public,
        ]);

        return redirect()
            ->route('games.index')
            ->with('success', 'Spēles redzamība atjaunināta.');
    }

    public function print(Game $game)
    {
        $this->checkGameAccess($game);

        $game->load([
            'players.events',
            'events.player',
        ]);

        $homePlayers = $game->players->where('team_side', 'home')->values();
        $awayPlayers = $game->players->where('team_side', 'away')->values();

        $quarterScores = $this->buildQuarterScores($game);

        return view('games.print', compact('game', 'homePlayers', 'awayPlayers', 'quarterScores'));
    }

    private function checkGameAccess(Game $game): void
    {
        if ($game->is_public) {
            return;
        }

        if (!auth()->check()) {
            abort(403);
        }

        $user = auth()->user();

        if ($game->user_id === $user->id) {
            return;
        }

        if (($user->role ?? null) === 'admin') {
            return;
        }

        abort(403);
    }

    private function checkGameOwnerOrAdmin(Game $game): void
    {
        if (!auth()->check()) {
            abort(403);
        }

        $user = auth()->user();

        if ($game->user_id === $user->id) {
            return;
        }

        if (($user->role ?? null) === 'admin') {
            return;
        }

        abort(403);
    }

    private function buildQuarterScores(Game $game): array
    {
        $quarterScores = [];

        foreach ([1, 2, 3, 4] as $quarter) {
            $quarterScores[$quarter] = [
                'home' => $game->events
                    ->where('quarter', $quarter)
                    ->where('team_side', 'home')
                    ->sum(fn ($event) => $this->eventPoints($event)),
                'away' => $game->events
                    ->where('quarter', $quarter)
                    ->where('team_side', 'away')
                    ->sum(fn ($event) => $this->eventPoints($event)),
            ];
        }

        return $quarterScores;
    }

    private function eventPoints(GameEvent $event): int
    {
        if (!$event->is_made) {
            return 0;
        }

        return match ($event->shot_type) {
            'ft' => 1,
            '2pt' => 2,
            '3pt' => 3,
            default => 0,
        };
    }
}