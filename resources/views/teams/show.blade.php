<x-layouts.app>
<div class="max-w-5xl mx-auto py-10 px-4 space-y-8">

    {{-- Hero --}}
    <div class="relative bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800 rounded-3xl overflow-hidden shadow-xl">
        {{-- Decorative elements --}}
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-orange-500/10 -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full bg-white/5 translate-y-1/2 -translate-x-1/3"></div>

        {{-- Back link --}}
        <div class="relative z-10 px-8 pt-6">
            @if(request('from_league'))
                <a href="{{ route('leagues.show', request('from_league')) }}"
                   class="inline-flex items-center gap-1.5 text-white/50 hover:text-white text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Atpakaļ uz līgu
                </a>
            @else
                <a href="{{ route('teams.index') }}"
                   class="inline-flex items-center gap-1.5 text-white/50 hover:text-white text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Atpakaļ uz komandām
                </a>
            @endif
        </div>

        {{-- Main content --}}
        <div class="relative z-10 px-8 py-8 flex flex-col sm:flex-row items-center sm:items-end gap-8">
            {{-- Logo --}}
            <div class="w-32 h-32 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center flex-shrink-0 shadow-2xl">
                @if($team->logo)
                    <img src="{{ asset('storage/' . $team->logo) }}"
                         alt="{{ $team->name }}"
                         class="w-24 h-24 object-contain drop-shadow-lg">
                @else
                    <span class="text-4xl font-black text-white">
                        {{ strtoupper(mb_substr($team->name, 0, 2)) }}
                    </span>
                @endif
            </div>

            {{-- Name + tags --}}
            <div class="flex-1 text-center sm:text-left">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight">
                    {{ $team->name }}
                </h1>
                <div class="flex flex-wrap gap-2 mt-4 justify-center sm:justify-start">
                    @if($team->country)
                        <span class="inline-flex items-center gap-1.5 bg-white/10 border border-white/20 text-white/80 text-xs font-semibold px-3 py-1.5 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $team->country }}
                        </span>
                    @endif
                    @foreach($team->leagues as $league)
                        <span class="inline-flex items-center bg-orange-500/20 border border-orange-400/30 text-orange-300 text-xs font-bold px-3 py-1.5 rounded-full">
                            🏆 {{ $league->name }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex gap-6 text-center pb-1">
                <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-4">
                    <div class="text-4xl font-black text-orange-400">{{ $team->players->count() }}</div>
                    <div class="text-xs font-semibold uppercase tracking-widest text-white/50 mt-1">Spēlētāji</div>
                </div>
                <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-4">
                    <div class="text-4xl font-black text-white">{{ $team->leagues->count() }}</div>
                    <div class="text-xs font-semibold uppercase tracking-widest text-white/50 mt-1">Līgas</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Players --}}
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900 mb-5">
            Spēlētāji
            <span class="text-base font-medium text-gray-400 ml-2">({{ $team->players->count() }})</span>
        </h2>

        @if($team->players->isEmpty())
            <div class="bg-white rounded-3xl border border-dashed border-gray-300 p-16 text-center">
                <p class="text-gray-400 text-base font-medium">Šai komandai vēl nav pievienots neviens spēlētājs</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($team->players as $player)
                    <a href="{{ route('players.public.show', $player) }}?from_team={{ $team->id }}{{ request('from_league') ? '&from_league='.request('from_league') : '' }}"
                       class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                                hover:shadow-xl hover:-translate-y-1 hover:border-orange-200
                                transition-all duration-300 ease-out flex items-stretch">

                        {{-- Left accent bar --}}
                        <div class="w-1 bg-gradient-to-b from-orange-400 to-orange-600 flex-shrink-0
                                    group-hover:w-1.5 transition-all duration-300"></div>

                        <div class="flex items-center gap-4 px-5 py-4 flex-1">
                            {{-- Avatar --}}
                            @if($player->image)
                                <img src="{{ asset('storage/' . $player->image) }}"
                                     alt="{{ $player->name }}"
                                     class="w-14 h-14 rounded-xl object-cover flex-shrink-0 shadow-sm border border-gray-100">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-slate-700 to-slate-900 text-white
                                            flex items-center justify-center text-lg font-black flex-shrink-0
                                            group-hover:from-orange-500 group-hover:to-orange-700
                                            transition-all duration-300 shadow-sm">
                                    {{ strtoupper(mb_substr($player->name, 0, 2)) }}
                                </div>
                            @endif

                            {{-- Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 truncate text-base leading-tight">
                                    {{ $player->name }}
                                </div>

                                <div class="flex flex-wrap gap-1.5 mt-1.5">
                                    @if($player->position)
                                        <span class="text-xs font-semibold bg-orange-50 text-orange-600 border border-orange-100 px-2 py-0.5 rounded-full">
                                            {{ $player->position }}
                                        </span>
                                    @endif
                                    @if($player->height)
                                        <span class="text-xs text-gray-400 font-medium px-2 py-0.5 bg-gray-50 rounded-full border border-gray-100">
                                            {{ $player->height }} m
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
</x-layouts.app>
