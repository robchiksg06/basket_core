<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameEvent;
use App\Models\GamePlayer;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;

class GameProtocolController extends Controller
{
    public function index()
    {
        $games = Game::query()
            ->whereNull('tournament_match_id')
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
        $tournamentMatch = null;
        if (request('tournament_match_id')) {
            $tournamentMatch = TournamentMatch::with(['team1', 'team2', 'tournament'])->find(request('tournament_match_id'));
        }
        return view('games.create', compact('tournamentMatch'));
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
            'tournament_match_id' => ['nullable', 'integer', 'exists:tournament_matches,id'],
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
            'tournament_match_id' => $validated['tournament_match_id'] ?? null,
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
            'user',
        ]);

        $homePlayers = $game->players->where('team_side', 'home')->values();
        $awayPlayers = $game->players->where('team_side', 'away')->values();

        $quarterScores = $this->buildQuarterScores($game);

        $isFollowing = auth()->check() && auth()->id() !== $game->user_id
            ? auth()->user()->following()->where('following_id', $game->user_id)->exists()
            : false;

        return view('games.show', compact('game', 'homePlayers', 'awayPlayers', 'quarterScores', 'isFollowing'));
    }

    public function addEvent(Request $request, Game $game)
    {
        $this->checkGameOwnerOrAdmin($game);

        $player = GamePlayer::where('game_id', $game->id)
            ->findOrFail($request->game_player_id);

        $eventType = $request->input('event_type', 'shot');

        $baseRules = [
            'game_player_id' => ['required', 'exists:game_players,id'],
            'quarter' => ['required', 'integer', 'min:1', 'max:4'],
            'event_type' => ['required', 'in:shot,rebound,assist,steal,turnover,foul'],
            'event_subtype' => ['nullable', 'in:offensive,defensive'],
            'shot_type' => ['nullable', 'in:ft,2pt,3pt'],
            'is_made' => ['nullable', 'in:0,1'],
            'court_x' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'court_y' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];

        $validated = $request->validate($baseRules);

        $eventData = [
            'game_id' => $game->id,
            'game_player_id' => $player->id,
            'team_side' => $player->team_side,
            'quarter' => $validated['quarter'],
            'event_type' => $eventType,
            'event_subtype' => null,
            'shot_type' => null,
            'is_made' => null,
            'court_x' => null,
            'court_y' => null,
        ];

        if ($eventType === 'shot') {
            $shotValidated = $request->validate([
                'shot_type' => ['required', 'in:ft,2pt,3pt'],
                'is_made' => ['required', 'in:0,1'],
                'court_x' => ['required_if:shot_type,2pt,3pt', 'nullable', 'numeric', 'min:0', 'max:100'],
                'court_y' => ['required_if:shot_type,2pt,3pt', 'nullable', 'numeric', 'min:0', 'max:100'],
            ]);

            $eventData['shot_type'] = $shotValidated['shot_type'];
            $eventData['is_made'] = (bool) $shotValidated['is_made'];
            $eventData['court_x'] = $shotValidated['shot_type'] === 'ft' ? null : $shotValidated['court_x'];
            $eventData['court_y'] = $shotValidated['shot_type'] === 'ft' ? null : $shotValidated['court_y'];
        }

        if ($eventType === 'rebound') {
            $eventData['event_subtype'] = $validated['event_subtype'] ?? null;
        }

        GameEvent::create($eventData);

        return redirect()
            ->route('games.show', $game)
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

        $game->load('events');
        $game->update(['status' => 'finished']);

        if ($game->tournament_match_id) {
            $match = TournamentMatch::find($game->tournament_match_id);
            if ($match && !$match->winner_id && $match->team1_id && $match->team2_id) {
                $homeScore = $game->home_score;
                $awayScore = $game->away_score;
                $winnerId = $homeScore >= $awayScore ? $match->team1_id : $match->team2_id;

                $match->update([
                    'team1_score' => $homeScore,
                    'team2_score' => $awayScore,
                    'winner_id'   => $winnerId,
                ]);

                $match->tournament->advanceWinner($match);

                if ($match->tournament->matches()->whereNull('winner_id')->count() === 0) {
                    $match->tournament->update(['status' => 'completed']);
                }

                return redirect()->route('tournaments.show', $match->tournament_id)
                    ->with('success', 'Spēle pabeigta! Rezultāts ierakstīts turnīrā.');
            }
        }

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

        if ($game->tournament_match_id) {
            $match = \App\Models\TournamentMatch::find($game->tournament_match_id);
            if ($match && $match->tournament?->is_public) {
                return;
            }
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
        if (($event->event_type ?? 'shot') !== 'shot') {
            return 0;
        }

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