<x-layouts.app>

<style>
    .player-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }
    .player-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 48px -10px rgba(0, 0, 0, 0.18);
        border-color: #f97316;
    }
    .player-card-photo {
        transition: transform 0.4s ease;
    }
    .player-card:hover .player-card-photo {
        transform: scale(1.06);
    }
    .player-card-name {
        transition: color 0.2s ease;
    }
    .player-card:hover .player-card-name {
        color: #ea580c;
    }
</style>

<div class="max-w-6xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Spēlētāji</h1>
            <p class="text-gray-400 mt-1 text-sm">{{ $players->count() }} spēlētāj{{ $players->count() === 1 ? 's' : 'i' }} atrast{{ $players->count() === 1 ? 's' : 'i' }}</p>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('players.public') }}" class="flex flex-col sm:flex-row gap-3 mb-8">
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
            </svg>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Meklēt spēlētāju..."
                   class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
        </div>

        <select name="position" class="bg-white border border-gray-200 rounded-2xl px-4 py-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 text-slate-700">
            <option value="">Visas pozīcijas</option>
            @foreach($positions as $pos)
                <option value="{{ $pos }}" {{ ($position ?? '') === $pos ? 'selected' : '' }}>{{ $pos }}</option>
            @endforeach
        </select>

        <select name="team" class="bg-white border border-gray-200 rounded-2xl px-4 py-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 text-slate-700">
            <option value="">Visas komandas</option>
            @foreach($teams as $t)
                <option value="{{ $t }}" {{ ($team ?? '') === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-slate-900 hover:bg-slate-700 text-white px-6 py-3 rounded-2xl font-semibold text-sm shadow-sm transition-all">
            Meklēt
        </button>

        @if($search || $position || $team)
            <a href="{{ route('players.public') }}" class="flex items-center justify-center px-4 py-3 rounded-2xl border border-gray-200 text-gray-500 hover:text-gray-700 text-sm font-medium transition-all bg-white">
                Notīrīt
            </a>
        @endif
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($players as $player)
            <div class="player-card relative bg-white rounded-3xl border-2 border-gray-100 shadow-sm overflow-hidden flex flex-col">

                {{-- Dark header --}}
                <div class="relative h-32 bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800">
                    <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full border border-white/5"></div>
                    <div class="absolute -bottom-8 -left-8 w-40 h-40 rounded-full border border-white/5"></div>
                    @if($player->position)
                        <span class="absolute top-3 left-3 z-10 text-xs font-black text-white/80 bg-white/10 border border-white/20 px-2.5 py-1 rounded-full tracking-wider">
                            {{ $player->position }}
                        </span>
                    @endif
                </div>

                {{-- Photo overlapping header --}}
                <div class="flex justify-center -mt-14 relative z-10">
                    @if($player->image)
                        <div class="player-card-photo w-28 h-28 rounded-full border-4 border-white shadow-xl overflow-hidden bg-slate-800">
                            <img src="{{ asset('storage/' . $player->image) }}"
                                 alt="{{ $player->name }}"
                                 class="w-full h-full object-cover object-top">
                        </div>
                    @else
                        <div class="player-card-photo w-28 h-28 rounded-full border-4 border-white shadow-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-3xl font-black text-white">
                            {{ strtoupper(mb_substr($player->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="pt-3 pb-5 px-5 flex flex-col flex-1 text-center">
                    <h3 class="player-card-name text-lg font-extrabold text-slate-900 leading-tight">
                        {{ $player->name }}
                    </h3>

                    <p class="text-sm text-gray-400 mt-0.5">{{ $player->team ?: '—' }}</p>

                    {{-- Stats pills --}}
                    <div class="flex flex-wrap justify-center gap-2 mt-3">
                        @if($player->height)
                            <span class="text-xs font-semibold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">
                                {{ $player->height }} m
                            </span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="mt-auto pt-4 space-y-2">
                        <a href="{{ route('players.show', $player->id) }}"
                           class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold py-2.5 rounded-xl transition">
                            Skatīt profilu
                        </a>

                        @auth
                            <form method="POST" action="{{ route('players.like', $player) }}">
                                @csrf
                                @php $liked = auth()->user()->likedPlayers->contains($player); @endphp
                                <button type="submit"
                                        class="w-full text-sm font-semibold py-2 rounded-xl border-2 transition
                                               {{ $liked ? 'border-pink-200 bg-pink-50 text-pink-600 hover:bg-pink-100' : 'border-gray-100 text-gray-400 hover:border-pink-200 hover:text-pink-500' }}">
                                    {{ $liked ? '❤️ Atteikt' : '🤍 Patīk' }}
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
</x-layouts.app>
