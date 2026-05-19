@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Spēles</h1>
            <p class="text-gray-400 text-sm mt-0.5">{{ $games->count() }} spēle{{ $games->count() === 1 ? '' : 's' }}</p>
        </div>
        @auth
            <a href="{{ route('games.create') }}"
               class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Jauna spēle
            </a>
        @endauth
    </div>

    @forelse($games as $game)
        @php
            $statusLabel = match($game->status) {
                'live'     => 'Notiek',
                'finished' => 'Pabeigta',
                default    => 'Gaidāma',
            };
            $statusClass = match($game->status) {
                'live'     => 'bg-green-100 text-green-700',
                'finished' => 'bg-gray-100 text-gray-600',
                default    => 'bg-blue-50 text-blue-600',
            };
        @endphp

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-4 overflow-hidden">

            {{-- Score header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-4 flex items-center justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="text-xs text-white/50 uppercase tracking-wider mb-1">Mājinieki</div>
                    <div class="text-white font-bold text-base truncate">{{ $game->home_team_name }}</div>
                </div>

                <div class="text-center flex-shrink-0">
                    <div class="text-2xl font-black text-white tabular-nums">
                        {{ $game->home_score }} <span class="text-white/40 font-light">:</span> {{ $game->away_score }}
                    </div>
                </div>

                <div class="flex-1 min-w-0 text-right">
                    <div class="text-xs text-white/50 uppercase tracking-wider mb-1">Viesi</div>
                    <div class="text-orange-400 font-bold text-base truncate">{{ $game->away_team_name }}</div>
                </div>
            </div>

            {{-- Meta row --}}
            <div class="px-5 py-3 flex flex-wrap items-center gap-x-4 gap-y-1.5 border-b border-gray-100 text-sm">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>
                @if($game->game_date)
                    <span class="text-gray-400 text-xs">📅 {{ $game->game_date }}</span>
                @endif
                @if($game->location)
                    <span class="text-gray-400 text-xs">📍 {{ $game->location }}</span>
                @endif
                <span class="text-gray-400 text-xs">
                    {{ $game->is_public ? '🌐 Publiska' : '🔒 Privāta' }}
                </span>
            </div>

            {{-- Actions --}}
            <div class="px-5 py-3 flex flex-wrap gap-2">
                <a href="{{ route('games.show', $game) }}"
                   class="inline-flex items-center gap-1.5 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Atvērt
                </a>
                <a href="{{ route('games.print', $game) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    🖨 Drukāt
                </a>
                @auth
                    @if(auth()->id() === $game->user_id || (auth()->user()->role ?? null) === 'admin')
                        <form action="{{ route('games.visibility', $game) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                                {{ $game->is_public ? '🔒 Privāta' : '🌐 Publiska' }}
                            </button>
                        </form>
                        <form action="{{ route('games.destroy', $game) }}" method="POST"
                              onsubmit="return confirm('Vai tiešām dzēst šo spēli?')">
                            @csrf @method('DELETE')
                            <button class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                                Dzēst
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>

    @empty
        <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-16 text-center">
            <p class="text-gray-400 text-lg">Vēl nav nevienas spēles.</p>
            @auth
                <a href="{{ route('games.create') }}" class="mt-4 inline-block text-orange-600 font-semibold hover:underline">
                    Izveidot pirmo spēli →
                </a>
            @endauth
        </div>
    @endforelse

</div>
@endsection
