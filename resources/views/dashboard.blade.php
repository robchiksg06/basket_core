<x-layouts.app>
<div class="max-w-5xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Sveiks, {{ Auth::user()->name }} 👋</h1>
        <p class="text-gray-400 mt-1 text-sm">Tavs personīgais BasketCore panelis</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-10">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-5 text-center">
            <div class="text-3xl font-extrabold text-orange-600">{{ $totalGames }}</div>
            <div class="text-sm text-gray-500 mt-1 font-medium">Spēles</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-5 text-center">
            <div class="text-3xl font-extrabold text-orange-600">{{ $totalTournaments }}</div>
            <div class="text-sm text-gray-500 mt-1 font-medium">Turnīri</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-5 text-center">
            <div class="text-3xl font-extrabold text-orange-600">{{ $likedPlayers }}</div>
            <div class="text-sm text-gray-500 mt-1 font-medium">Iecienītie</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-5 text-center">
            <div class="text-3xl font-extrabold text-slate-700">{{ $followingCount }}</div>
            <div class="text-sm text-gray-500 mt-1 font-medium">Sekoju</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-5 text-center">
            <div class="text-3xl font-extrabold text-slate-700">{{ $followersCount }}</div>
            <div class="text-sm text-gray-500 mt-1 font-medium">Sekotāji</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Pēdējās spēles --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800 text-lg">📋 Pēdējās spēles</h2>
                <a href="{{ route('games.index') }}" class="text-xs text-orange-500 hover:underline font-medium">Visas →</a>
            </div>

            @if($recentGames->isEmpty())
                <div class="px-6 py-10 text-center">
                    <div class="text-3xl mb-2">🏀</div>
                    <p class="text-slate-500 font-medium text-sm">Nav nevienas spēles</p>
                    <a href="{{ route('games.create') }}"
                       class="inline-block mt-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-xl font-bold text-xs transition">
                        + Izveidot spēli
                    </a>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($recentGames as $game)
                        <a href="{{ route('games.show', $game) }}"
                           class="flex items-center justify-between px-6 py-3.5 hover:bg-orange-50/30 transition">
                            <div class="min-w-0">
                                <div class="font-semibold text-slate-800 text-sm truncate">
                                    {{ $game->home_team_name }} vs {{ $game->away_team_name }}
                                </div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    {{ $game->game_date ? \Carbon\Carbon::parse($game->game_date)->format('d.m.Y') : 'Nav datuma' }}
                                </div>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0 ml-3">
                                @if($game->status === 'finished')
                                    <span class="text-sm font-bold text-slate-700">
                                        {{ $game->home_score }} : {{ $game->away_score }}
                                    </span>
                                @endif
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    {{ $game->status === 'live' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $game->status === 'live' ? 'Notiek' : 'Pabeigta' }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="px-6 py-3 border-t border-gray-50">
                    <a href="{{ route('games.create') }}"
                       class="block text-center text-xs text-orange-500 hover:underline font-semibold">
                        + Jauna spēle
                    </a>
                </div>
            @endif
        </div>

        {{-- Turnīri --}}
        <div class="space-y-6">

            {{-- Aktīvie --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-bold text-slate-800 text-lg">🏆 Aktīvie turnīri</h2>
                    <a href="{{ route('tournaments.index') }}" class="text-xs text-orange-500 hover:underline font-medium">Visi →</a>
                </div>

                @if($activeTournaments->isEmpty())
                    <div class="px-6 py-8 text-center">
                        <p class="text-slate-400 text-sm">Nav aktīvu turnīru</p>
                        <a href="{{ route('tournaments.create') }}"
                           class="inline-block mt-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-xl font-bold text-xs transition">
                            + Izveidot turnīru
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($activeTournaments as $t)
                            <a href="{{ route('tournaments.show', $t) }}"
                               class="flex items-center justify-between px-6 py-3.5 hover:bg-orange-50/30 transition">
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-800 text-sm truncate">{{ $t->name }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        {{ $t->format === 'group_knockout' ? 'Grupas + Atzarojums' : 'Single Elimination' }}
                                    </div>
                                </div>
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-orange-100 text-orange-700 flex-shrink-0 ml-3">
                                    Aktīvs
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Pabeigti --}}
            @if($completedTournaments->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-slate-800 text-lg">✓ Pabeigti turnīri</h2>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($completedTournaments as $t)
                            <a href="{{ route('tournaments.show', $t) }}"
                               class="flex items-center justify-between px-6 py-3.5 hover:bg-gray-50 transition">
                                <div class="font-semibold text-slate-600 text-sm truncate">{{ $t->name }}</div>
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-green-100 text-green-700 flex-shrink-0 ml-3">
                                    Pabeigts
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Feed — sekoto lietotāju spēles --}}
    <div class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-lg">👥 Sekoto spēles</h2>
            @if($followingCount > 0)
                <span class="text-xs text-gray-400">{{ $followingCount }} lietotāj{{ $followingCount === 1 ? 's' : 'i' }}</span>
            @endif
        </div>

        @if($followingCount === 0)
            <div class="px-6 py-10 text-center">
                <div class="text-4xl mb-3">👤</div>
                <p class="font-semibold text-slate-500 text-sm">Vēl neseko nevienam</p>
                <p class="text-xs text-gray-400 mt-1">Atver kāda cita publisku spēli un nospied "Sekot"</p>
                <a href="{{ route('games.index') }}"
                   class="inline-block mt-4 bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded-xl font-bold text-xs transition">
                    Pārlūkot spēles
                </a>
            </div>
        @elseif($feedGames->isEmpty())
            <div class="px-6 py-10 text-center">
                <div class="text-4xl mb-3">📭</div>
                <p class="font-semibold text-slate-500 text-sm">Sekotajiem vēl nav publisku spēļu</p>
                <p class="text-xs text-gray-400 mt-1">Jaunākās spēles parādīsies šeit</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($feedGames as $game)
                    <a href="{{ route('games.show', $game) }}"
                       class="flex items-center gap-4 px-6 py-4 hover:bg-orange-50/30 transition">

                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            @if($game->user?->avatarUrl())
                                <img src="{{ $game->user->avatarUrl() }}"
                                     class="w-9 h-9 rounded-xl object-cover border border-gray-200"
                                     alt="{{ $game->user->name }}">
                            @else
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white text-xs font-black">
                                    {{ strtoupper(mb_substr($game->user?->name ?? '?', 0, 2)) }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="min-w-0 flex-1">
                            <div class="font-semibold text-slate-800 text-sm truncate">
                                {{ $game->home_team_name }} vs {{ $game->away_team_name }}
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ $game->user?->name }} ·
                                {{ $game->game_date ? \Carbon\Carbon::parse($game->game_date)->format('d.m.Y') : 'Nav datuma' }}
                                @if($game->location) · {{ $game->location }} @endif
                            </div>
                        </div>

                        {{-- Score / status --}}
                        <div class="flex items-center gap-3 flex-shrink-0">
                            @if($game->status === 'finished')
                                <span class="text-sm font-black text-slate-700">
                                    {{ $game->home_score }} : {{ $game->away_score }}
                                </span>
                            @endif
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                {{ $game->status === 'live' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $game->status === 'live' ? '● Notiek' : 'Pabeigta' }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Quick actions --}}
    <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <a href="{{ route('games.create') }}"
           class="flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-xl font-bold text-sm transition">
            🏀 Jauna spēle
        </a>
        <a href="{{ route('tournaments.create') }}"
           class="flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-3 rounded-xl font-bold text-sm transition">
            🏆 Jauns turnīrs
        </a>
        <a href="{{ route('players.public') }}"
           class="flex items-center justify-center gap-2 bg-white hover:bg-gray-50 border border-gray-200 text-slate-700 px-4 py-3 rounded-xl font-bold text-sm transition">
            👤 Spēlētāji
        </a>
        <a href="{{ route('leagues.index') }}"
           class="flex items-center justify-center gap-2 bg-white hover:bg-gray-50 border border-gray-200 text-slate-700 px-4 py-3 rounded-xl font-bold text-sm transition">
            🏅 Līgas
        </a>
        <a href="{{ route('teams.index') }}"
           class="flex items-center justify-center gap-2 bg-white hover:bg-gray-50 border border-gray-200 text-slate-700 px-4 py-3 rounded-xl font-bold text-sm transition">
            👕 Komandas
        </a>
        <a href="{{ route('forum.index') }}"
           class="flex items-center justify-center gap-2 bg-white hover:bg-gray-50 border border-gray-200 text-slate-700 px-4 py-3 rounded-xl font-bold text-sm transition">
            💬 Forums
        </a>
    </div>

</div>
</x-layouts.app>
