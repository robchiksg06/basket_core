@extends('layouts.app')

@section('content')
@php
    $canManageGame = auth()->check()
        && (
            auth()->id() === $game->user_id
            || (auth()->user()->role ?? null) === 'admin'
        );

    $allPlayers = $homePlayers->concat($awayPlayers);

    $shotEvents = $game->events
        ->filter(fn ($event) => !is_null($event->court_x) && !is_null($event->court_y))
        ->map(function ($event) {
            return [
                'id' => $event->id,
                'game_player_id' => $event->game_player_id,
                'player_name' => $event->player?->player_name ?? 'Nezināms',
                'jersey_number' => $event->player?->jersey_number ?? '',
                'team_side' => $event->team_side,
                'quarter' => (string) $event->quarter,
                'shot_type' => $event->shot_type,
                'is_made' => (int) $event->is_made,
                'court_x' => (float) $event->court_x,
                'court_y' => (float) $event->court_y,
            ];
        })
        ->values();
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
            <input type="hidden" name="shot_type" class="shot-type-input" required>

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
                    <div class="w-full border rounded px-3 py-2 bg-gray-50 text-gray-700 shot-type-label">
                        Nav izvēlēts
                    </div>
                </div>
            </div>

            <div class="max-w-[600px] mx-auto">
                    <div
            class="court-picker relative w-full cursor-crosshair rounded-xl overflow-hidden border shadow"
            style="aspect-ratio: 2 / 1;"
        >
            <img
                src="{{ asset('images/court.png') }}"
                class="absolute inset-0 w-full h-full object-cover select-none pointer-events-none"
                alt="Basketbola laukums"
            >



            <div class="shot-marker hidden absolute w-4 h-4 rounded-full bg-blue-600 border-2 border-white shadow-md -translate-x-1/2 -translate-y-1/2"></div>
        </div>
                <div class="text-sm text-gray-700 text-center mt-4">
                    Uzspied uz laukuma, lai atzīmētu metiena vietu
                </div>
               
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <button type="submit" name="is_made" value="1"
                        class="bg-green-600 hover:bg-green-700 text-white rounded-xl px-4 py-3 font-semibold shadow-sm">
                        Iemeta
                    </button>

                    <button type="submit" name="is_made" value="0"
                        class="bg-red-600 hover:bg-red-700 text-white rounded-xl px-4 py-3 font-semibold shadow-sm">
                        Garām
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-3">
                    <button type="submit" name="ft_result" value="1"
                        class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-4 py-3 font-semibold shadow-sm">
                        FT iemeta
                    </button>

                    <button type="submit" name="ft_result" value="0"
                        class="bg-gray-700 hover:bg-gray-800 text-white rounded-xl px-4 py-3 font-semibold shadow-sm">
                        FT garām
                    </button>
                </div>
            </div>
        </form>
    </div>
@endif

<div class="bg-white rounded shadow p-6 mb-6">
    <h2 class="text-2xl font-bold mb-4">Metienu karte</h2>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div>
            <label class="block font-semibold mb-1">Spēlētājs</label>
            <select id="filter-player" class="w-full border rounded px-3 py-2">
                <option value="">Visi spēlētāji</option>
                @foreach($allPlayers as $player)
                    <option value="{{ $player->id }}">
                        #{{ $player->jersey_number }} {{ $player->player_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Komanda</label>
            <select id="filter-team" class="w-full border rounded px-3 py-2">
                <option value="">Abas komandas</option>
                <option value="home">{{ $game->home_team_name }}</option>
                <option value="away">{{ $game->away_team_name }}</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Ceturtdaļa</label>
            <select id="filter-quarter" class="w-full border rounded px-3 py-2">
                <option value="">Visas ceturtdaļas</option>
                <option value="1">Q1</option>
                <option value="2">Q2</option>
                <option value="3">Q3</option>
                <option value="4">Q4</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Rezultāts</label>
            <select id="filter-result" class="w-full border rounded px-3 py-2">
                <option value="">Visi</option>
                <option value="1">Iemesti</option>
                <option value="0">Garām</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Metiena tips</label>
            <select id="filter-shot-type" class="w-full border rounded px-3 py-2">
                <option value="">Visi tipi</option>
                <option value="ft">1PT</option>
                <option value="2pt">2PT</option>
                <option value="3pt">3PT</option>
            </select>
        </div>
    </div>

    <div class="max-w-[600px] mx-auto">
        <div
            id="shots-court"
            class="relative w-full rounded-xl overflow-hidden border shadow"
            style="aspect-ratio: 2 / 1;"
        >
            <img
                src="{{ asset('images/court.png') }}"
                class="absolute inset-0 w-full h-full object-cover select-none pointer-events-none"
                alt="Basketbola laukums"
            >

            <div id="shots-layer" class="absolute inset-0"></div>
        </div>

        <div class="flex items-center justify-center gap-6 mt-4 text-sm text-gray-700">
            <div class="flex items-center gap-2">
                <span class="inline-block w-4 h-4 rounded-full bg-green-600 border border-white"></span>
                <span>Iemests</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="relative inline-block w-4 h-4">
                    <span class="absolute left-1/2 top-1/2 w-4 h-[2px] bg-red-600" style="transform: translate(-50%, -50%) rotate(45deg);"></span>
                    <span class="absolute left-1/2 top-1/2 w-4 h-[2px] bg-red-600" style="transform: translate(-50%, -50%) rotate(-45deg);"></span>
                </span>
                <span>Garām</span>
            </div>
        </div>

        <div id="shots-summary" class="text-center text-sm text-gray-600 mt-3">
            -
        </div>
    </div>
</div>

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

<div
    id="shot-tooltip"
    class="hidden fixed z-50 min-w-[180px] max-w-[220px] rounded-lg bg-gray-900 text-white text-xs px-3 py-2 shadow-lg pointer-events-none"
>
    <div id="tooltip-player" class="font-semibold text-sm"></div>
    <div id="tooltip-meta" class="text-gray-200 mt-1"></div>
    <div id="tooltip-result" class="mt-1 font-medium"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.shot-form').forEach(function (form) {
        const court = form.querySelector('.court-picker');
        const marker = form.querySelector('.shot-marker');
        const xInput = form.querySelector('.court-x-input');
        const yInput = form.querySelector('.court-y-input');
        const shotTypeInput = form.querySelector('.shot-type-input');
        const shotTypeLabel = form.querySelector('.shot-type-label');

        if (!court || !marker || !xInput || !yInput || !shotTypeInput || !shotTypeLabel) return;

        function detectShotType(xPercent, yPercent) {
            const leftLineX = 7.5;
            const rightLineX = 92.5;
            const arcTopY = 5.2;
            const arcCurve = 0.028;

            if (xPercent <= leftLineX || xPercent >= rightLineX) {
                return '3pt';
            }

            const dx = xPercent - 50;
            const yArc = arcTopY + (arcCurve * dx * dx);

            if (yPercent <= yArc) return '3pt';
            if (Math.abs(yPercent - yArc) < 0.5) return '3pt';

            return '2pt';
        }

        function setShotType(type) {
            shotTypeInput.value = type;

            if (type === '2pt') {
                shotTypeLabel.textContent = '2PT';
            } else if (type === '3pt') {
                shotTypeLabel.textContent = '3PT';
            } else if (type === 'ft') {
                shotTypeLabel.textContent = '1PT';
            } else {
                shotTypeLabel.textContent = 'Nav izvēlēts';
            }
        }

        court.addEventListener('click', function (event) {
            const rect = court.getBoundingClientRect();

            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            const xPercent = Math.max(0, Math.min(100, (x / rect.width) * 100));
            const yPercent = Math.max(0, Math.min(100, (y / rect.height) * 100));

            xInput.value = xPercent.toFixed(2);
            yInput.value = yPercent.toFixed(2);

            const detectedType = detectShotType(xPercent, yPercent);
            setShotType(detectedType);

            marker.style.left = `${xPercent}%`;
            marker.style.top = `${yPercent}%`;
            marker.classList.remove('hidden');
        });

        form.querySelectorAll('button[name="ft_result"]').forEach(function (button) {
            button.addEventListener('click', function () {
                xInput.value = '';
                yInput.value = '';
                marker.classList.add('hidden');
                setShotType('ft');

                let hiddenIsMade = form.querySelector('.ft-hidden-is-made');

                if (!hiddenIsMade) {
                    hiddenIsMade = document.createElement('input');
                    hiddenIsMade.type = 'hidden';
                    hiddenIsMade.name = 'is_made';
                    hiddenIsMade.className = 'ft-hidden-is-made';
                    form.appendChild(hiddenIsMade);
                }

                hiddenIsMade.value = button.value;
            });
        });

        form.addEventListener('submit', function (event) {
            const submitter = event.submitter;

            if (!submitter) return;

            const isFtButton = submitter.name === 'ft_result';
            const isRegularShotButton = submitter.name === 'is_made';

            if (isRegularShotButton) {
                if (!xInput.value || !yInput.value) {
                    event.preventDefault();
                    alert('Lūdzu, uzspied uz laukuma, lai atzīmētu metiena vietu.');
                    return;
                }

                if (!shotTypeInput.value || shotTypeInput.value === 'ft') {
                    const detectedType = detectShotType(
                        parseFloat(xInput.value),
                        parseFloat(yInput.value)
                    );
                    setShotType(detectedType);
                }

                const ftHidden = form.querySelector('.ft-hidden-is-made');
                if (ftHidden) {
                    ftHidden.remove();
                }
            }

            if (isFtButton) {
                setShotType('ft');
            }
        });
    });

    const shots = @json($shotEvents);

    const playerFilter = document.getElementById('filter-player');
    const teamFilter = document.getElementById('filter-team');
    const quarterFilter = document.getElementById('filter-quarter');
    const resultFilter = document.getElementById('filter-result');
    const shotTypeFilter = document.getElementById('filter-shot-type');
    const shotsLayer = document.getElementById('shots-layer');
    const shotsSummary = document.getElementById('shots-summary');

    const shotTooltip = document.getElementById('shot-tooltip');
    const tooltipPlayer = document.getElementById('tooltip-player');
    const tooltipMeta = document.getElementById('tooltip-meta');
    const tooltipResult = document.getElementById('tooltip-result');

    function matchesFilters(shot) {
        const playerValue = playerFilter?.value || '';
        const teamValue = teamFilter?.value || '';
        const quarterValue = quarterFilter?.value || '';
        const resultValue = resultFilter?.value || '';
        const shotTypeValue = shotTypeFilter?.value || '';

        if (playerValue && String(shot.game_player_id) !== playerValue) return false;
        if (teamValue && shot.team_side !== teamValue) return false;
        if (quarterValue && String(shot.quarter) !== quarterValue) return false;
        if (resultValue && String(shot.is_made) !== resultValue) return false;
        if (shotTypeValue && shot.shot_type !== shotTypeValue) return false;

        return true;
    }

    function teamLabel(teamSide) {
        if (teamSide === 'home') return @json($game->home_team_name);
        if (teamSide === 'away') return @json($game->away_team_name);
        return 'Nezināma komanda';
    }

    function shotTypeLabel(shotType) {
        if (shotType === 'ft') return '1PT';
        if (shotType === '2pt') return '2PT';
        if (shotType === '3pt') return '3PT';
        return shotType;
    }

    function resultLabel(isMade) {
        return Number(isMade) === 1 ? 'Iemeta' : 'Garām';
    }

    function showTooltip(shot, event) {
        if (!shotTooltip) return;

        tooltipPlayer.textContent = `#${shot.jersey_number} ${shot.player_name}`;
        tooltipMeta.textContent = `${teamLabel(shot.team_side)} • Q${shot.quarter} • ${shotTypeLabel(shot.shot_type)}`;
        tooltipResult.textContent = resultLabel(shot.is_made);
        tooltipResult.className = `mt-1 font-medium ${Number(shot.is_made) === 1 ? 'text-green-400' : 'text-red-400'}`;

        const offset = 14;

        shotTooltip.classList.remove('hidden');

        const tooltipRect = shotTooltip.getBoundingClientRect();

        let x = event.clientX + offset;
        let y = event.clientY - offset;

        if (x + tooltipRect.width > window.innerWidth) {
            x = event.clientX - tooltipRect.width - offset;
        }

        if (y < 0) {
            y = event.clientY + offset;
        }

        shotTooltip.style.left = `${x}px`;
        shotTooltip.style.top = `${y}px`;
    }

    function moveTooltip(event) {
        if (!shotTooltip || shotTooltip.classList.contains('hidden')) return;

        const offset = 14;
        const tooltipRect = shotTooltip.getBoundingClientRect();

        let x = event.clientX + offset;
        let y = event.clientY - offset;

        if (x + tooltipRect.width > window.innerWidth) {
            x = event.clientX - tooltipRect.width - offset;
        }

        if (y < 0) {
            y = event.clientY + offset;
        }

        shotTooltip.style.left = `${x}px`;
        shotTooltip.style.top = `${y}px`;
    }

    function hideTooltip() {
        if (!shotTooltip) return;
        shotTooltip.classList.add('hidden');
    }

    function createShotElement(shot) {
        const isMade = Number(shot.is_made) === 1;

        const wrapper = document.createElement('div');
        wrapper.className = 'absolute -translate-x-1/2 -translate-y-1/2 cursor-pointer';
        wrapper.style.left = `${shot.court_x}%`;
        wrapper.style.top = `${shot.court_y}%`;

        wrapper.addEventListener('mouseenter', function (event) {
            showTooltip(shot, event);
        });

        wrapper.addEventListener('mousemove', function (event) {
            moveTooltip(event);
        });

        wrapper.addEventListener('mouseleave', function () {
            hideTooltip();
        });

        if (isMade) {
            const dot = document.createElement('div');
            dot.className = 'w-4 h-4 rounded-full bg-green-600 border-2 border-white shadow-md';
            wrapper.appendChild(dot);
        } else {
            const miss = document.createElement('div');
            miss.className = 'relative w-4 h-4';

            const line1 = document.createElement('span');
            line1.className = 'absolute left-1/2 top-1/2 w-4 h-[2px] bg-red-600 origin-center';
            line1.style.transform = 'translate(-50%, -50%) rotate(45deg)';

            const line2 = document.createElement('span');
            line2.className = 'absolute left-1/2 top-1/2 w-4 h-[2px] bg-red-600 origin-center';
            line2.style.transform = 'translate(-50%, -50%) rotate(-45deg)';

            miss.appendChild(line1);
            miss.appendChild(line2);
            wrapper.appendChild(miss);
        }

        return wrapper;
    }

    function renderShots() {
        if (!shotsLayer) return;

        shotsLayer.innerHTML = '';
        hideTooltip();

        const filteredShots = shots.filter(matchesFilters);

        filteredShots.forEach(function (shot) {
            shotsLayer.appendChild(createShotElement(shot));
        });

        const made = filteredShots.filter(shot => Number(shot.is_made) === 1).length;
        const missed = filteredShots.filter(shot => Number(shot.is_made) === 0).length;

        if (shotsSummary) {
            shotsSummary.textContent = `Redzami ${filteredShots.length} metieni — ${made} iemesti, ${missed} garām`;
        }
    }

    [playerFilter, teamFilter, quarterFilter, resultFilter, shotTypeFilter].forEach(function (el) {
        if (!el) return;
        el.addEventListener('change', renderShots);
    });

    renderShots();
});
</script>
@endsection