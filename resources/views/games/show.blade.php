@extends('layouts.app')

@section('content')
@php
    $canManageGame = auth()->check()
        && (
            auth()->id() === $game->user_id
            || (auth()->user()->role ?? null) === 'admin'
        );

    $allPlayers = $homePlayers->concat($awayPlayers);
@endphp

    <div class="bg-white rounded shadow p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">
                    {{ $game->home_team_name }} {{ $game->home_score }} : {{ $game->away_score }} {{ $game->away_team_name }}
                </h1>
                <p class="text-gray-600 mt-2">
                    {{ $game->title }} |
                    {{ $game->game_date }} |
                    {{ $game->location }} |
                    Statuss: {{ $game->status }} |
                    {{ $game->is_public ? 'Publiska spēle' : 'Privāta spēle' }}
                </p>
            </div>

            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('games.print', $game) }}" target="_blank" class="bg-gray-800 text-white px-4 py-2 rounded">
                    Print protokols
                </a>

                @if($canManageGame)
                    <form action="{{ route('games.visibility', $game) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="bg-yellow-500 text-white px-4 py-2 rounded">
                            {{ $game->is_public ? 'Padarīt privātu' : 'Padarīt publisku' }}
                        </button>
                    </form>
                @endif

                @if($canManageGame && $game->status !== 'finished')
                    <form action="{{ route('games.finish', $game) }}" method="POST">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded">
                            Pabeigt spēli
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if(!$canManageGame)
        <div class="mb-6 rounded bg-blue-100 border border-blue-300 text-blue-800 px-4 py-3">
            Šī ir publiska spēle skatīšanās režīmā. Spēles notikumus var rediģēt tikai autors.
        </div>
    @endif

    @if($canManageGame)
        <div class="bg-white rounded shadow p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Pievienot metienu</h2>

            <form action="{{ route('games.events.store', $game) }}" method="POST" class="shot-form space-y-4">
                @csrf

                <input type="hidden" name="court_x" class="court-x-input">
                <input type="hidden" name="court_y" class="court-y-input">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold mb-1">Spēlētājs</label>
                        <select name="game_player_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">Izvēlies spēlētāju</option>

                            <optgroup label="{{ $game->home_team_name }}">
                                @foreach($homePlayers as $player)
                                    <option value="{{ $player->id }}">
                                        #{{ $player->jersey_number }} {{ $player->player_name }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="{{ $game->away_team_name }}">
                                @foreach($awayPlayers as $player)
                                    <option value="{{ $player->id }}">
                                        #{{ $player->jersey_number }} {{ $player->player_name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Ceturtdaļa</label>
                        <select name="quarter" class="w-full border rounded px-3 py-2" required>
                            <option value="1">Q1</option>
                            <option value="2">Q2</option>
                            <option value="3">Q3</option>
                            <option value="4">Q4</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Metiena tips</label>
                        <select name="shot_type" class="w-full border rounded px-3 py-2" required>
                            <option value="ft">1PT</option>
                            <option value="2pt">2PT</option>
                            <option value="3pt">3PT</option>
                        </select>
                    </div>
                </div>

                <div class="court-picker relative w-full max-w-[420px] mx-auto rounded-xl overflow-hidden cursor-crosshair border border-amber-300 bg-[#f5ead7]" style="aspect-ratio: 1 / 1.25;">
                    <svg viewBox="0 0 420 520" class="absolute inset-0 w-full h-full">
                        <rect x="0" y="0" width="420" height="520" fill="#f5ead7"/>
                        <rect x="35" y="25" width="350" height="460" rx="8" fill="none" stroke="#b45309" stroke-width="4"/>

                        <rect x="145" y="25" width="130" height="150" fill="none" stroke="#b45309" stroke-width="4"/>
                        <rect x="170" y="25" width="80" height="55" fill="none" stroke="#b45309" stroke-width="4"/>

                        <circle cx="210" cy="58" r="6" fill="#dc2626"/>
                        <circle cx="210" cy="175" r="38" fill="none" stroke="#b45309" stroke-width="4"/>
                        <path d="M 170 118 A 40 40 0 0 0 250 118" fill="none" stroke="#b45309" stroke-width="4"/>

                        <line x1="85" y1="485" x2="85" y2="110" stroke="#b45309" stroke-width="4"/>
                        <line x1="335" y1="485" x2="335" y2="110" stroke="#b45309" stroke-width="4"/>
                        <path d="M 85 110 A 125 125 0 0 1 335 110" fill="none" stroke="#b45309" stroke-width="4"/>
                    </svg>

                    <div class="shot-marker hidden absolute w-3 h-3 rounded-full bg-blue-600 border border-white shadow -translate-x-1/2 -translate-y-1/2"></div>
                </div>

                <div class="text-sm text-gray-600 text-center">
                    Spied uz laukuma, lai atzīmētu metiena vietu
                </div>

                <div class="grid grid-cols-2 gap-4 max-w-[420px] mx-auto">
                    <button type="submit" name="is_made" value="1" class="bg-green-600 text-white rounded px-4 py-3 font-semibold">
                        Iemeta
                    </button>

                    <button type="submit" name="is_made" value="0" class="bg-red-600 text-white rounded px-4 py-3 font-semibold">
                        Garām
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">Ceturtdaļu rezultāts</h2>
        <table class="w-full border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border px-3 py-2">Komanda</th>
                    <th class="border px-3 py-2">Q1</th>
                    <th class="border px-3 py-2">Q2</th>
                    <th class="border px-3 py-2">Q3</th>
                    <th class="border px-3 py-2">Q4</th>
                    <th class="border px-3 py-2">Kopā</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-3 py-2 font-semibold">{{ $game->home_team_name }}</td>
                    <td class="border px-3 py-2">{{ $quarterScores[1]['home'] }}</td>
                    <td class="border px-3 py-2">{{ $quarterScores[2]['home'] }}</td>
                    <td class="border px-3 py-2">{{ $quarterScores[3]['home'] }}</td>
                    <td class="border px-3 py-2">{{ $quarterScores[4]['home'] }}</td>
                    <td class="border px-3 py-2 font-bold">{{ $game->home_score }}</td>
                </tr>
                <tr>
                    <td class="border px-3 py-2 font-semibold">{{ $game->away_team_name }}</td>
                    <td class="border px-3 py-2">{{ $quarterScores[1]['away'] }}</td>
                    <td class="border px-3 py-2">{{ $quarterScores[2]['away'] }}</td>
                    <td class="border px-3 py-2">{{ $quarterScores[3]['away'] }}</td>
                    <td class="border px-3 py-2">{{ $quarterScores[4]['away'] }}</td>
                    <td class="border px-3 py-2 font-bold">{{ $game->away_score }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">Visi notikumi</h2>

        <table class="w-full border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border px-3 py-2">Laiks</th>
                    <th class="border px-3 py-2">Q</th>
                    <th class="border px-3 py-2">Komanda</th>
                    <th class="border px-3 py-2">Spēlētājs</th>
                    <th class="border px-3 py-2">Metiens</th>
                    <th class="border px-3 py-2">Rezultāts</th>
                    <th class="border px-3 py-2">Vieta</th>
                    <th class="border px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($game->events->sortByDesc('id') as $event)
                    <tr>
                        <td class="border px-3 py-2">{{ $event->created_at->format('H:i:s') }}</td>
                        <td class="border px-3 py-2">{{ $event->quarter }}</td>
                        <td class="border px-3 py-2">
                            {{ $event->team_side === 'home' ? $game->home_team_name : $game->away_team_name }}
                        </td>
                        <td class="border px-3 py-2">
                            #{{ $event->player->jersey_number }} {{ $event->player->player_name }}
                        </td>
                        <td class="border px-3 py-2">{{ strtoupper($event->shot_type) }}</td>
                        <td class="border px-3 py-2">
                            {{ $event->is_made ? 'Sekmīgs' : 'Nesekmīgs' }}
                        </td>
                        <td class="border px-3 py-2">
                            @if(!is_null($event->court_x) && !is_null($event->court_y))
                                {{ $event->court_x }}, {{ $event->court_y }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="border px-3 py-2">
                            @if($canManageGame)
                                <form action="{{ route('games.events.delete', [$game, $event]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 font-semibold">Dzēst</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="border px-3 py-4 text-center text-gray-500">
                            Vēl nav neviena notikuma.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-2xl font-bold mb-4">{{ $game->home_team_name }} statistika</h2>
            @include('games.partials.stats-table', ['players' => $homePlayers])
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-2xl font-bold mb-4">{{ $game->away_team_name }} statistika</h2>
            @include('games.partials.stats-table', ['players' => $awayPlayers])
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.shot-form').forEach(function (form) {
            const court = form.querySelector('.court-picker');
            const marker = form.querySelector('.shot-marker');
            const xInput = form.querySelector('.court-x-input');
            const yInput = form.querySelector('.court-y-input');

            if (!court || !marker || !xInput || !yInput) return;

            court.addEventListener('click', function (event) {
                const rect = court.getBoundingClientRect();

                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;

                const xPercent = Math.max(0, Math.min(100, (x / rect.width) * 100));
                const yPercent = Math.max(0, Math.min(100, (y / rect.height) * 100));

                xInput.value = xPercent.toFixed(2);
                yInput.value = yPercent.toFixed(2);

                marker.style.left = xPercent + '%';
                marker.style.top = yPercent + '%';
                marker.classList.remove('hidden');
            });
        });
    });
    </script>
@endsection