<x-layouts.app>

<style>
    .team-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }
    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
        border-color: #f97316;
    }
    .team-card-banner {
        transition: background 0.4s ease;
        background: linear-gradient(135deg, #1e293b, #0f172a, #1e293b);
    }
    .team-card:hover .team-card-banner {
        background: linear-gradient(135deg, #1e293b, #0f172a, #9a3412);
    }
    .team-card-logo {
        transition: transform 0.4s ease;
    }
    .team-card:hover .team-card-logo {
        transform: scale(1.12);
    }
    .team-card-name {
        transition: color 0.2s ease;
    }
    .team-card:hover .team-card-name {
        color: #ea580c;
    }
    .team-card-arrow {
        transition: transform 0.2s ease;
    }
    .team-card:hover .team-card-arrow {
        transform: translateX(5px);
    }
</style>

<div class="max-w-6xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Komandas</h1>
            <p class="text-gray-400 mt-1 text-sm">{{ count($teams) }} komanda{{ count($teams) === 1 ? '' : 's' }} atrasta{{ count($teams) === 1 ? '' : 's' }}</p>
        </div>
        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('teams.create') }}"
                   class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-5 py-3 rounded-xl font-bold shadow-md transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Pievienot komandu
                </a>
            @endif
        @endauth
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('teams.index') }}" class="flex flex-col sm:flex-row gap-3 mb-8">
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Meklēt komandu..."
                   class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
        </div>
        <select name="league" class="bg-white border border-gray-200 rounded-2xl px-4 py-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 text-slate-700">
            <option value="">Visas līgas</option>
            @foreach($leagues as $leagueId => $leagueName)
                <option value="{{ $leagueId }}" {{ request('league') == $leagueId ? 'selected' : '' }}>{{ $leagueName }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-slate-900 hover:bg-slate-700 text-white px-6 py-3 rounded-2xl font-semibold text-sm shadow-sm transition-all">
            Meklēt
        </button>
    </form>

    {{-- Sort --}}
    @php
        $toggle = fn($col) => ($sort === $col && $direction === 'asc') ? 'desc' : 'asc';
        $arrow  = fn($col) => $sort === $col ? ($direction === 'asc' ? ' ↑' : ' ↓') : '';
    @endphp
    <div class="flex gap-2 mb-8 text-sm">
        <a href="{{ route('teams.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => $toggle('name')])) }}"
           class="px-4 py-1.5 rounded-full font-semibold border transition-all {{ $sort === 'name' ? 'bg-orange-600 text-white border-orange-600' : 'border-gray-200 text-slate-500 bg-white' }}">
            Nosaukums{!! $arrow('name') !!}
        </a>
        <a href="{{ route('teams.index', array_merge(request()->all(), ['sort' => 'country', 'direction' => $toggle('country')])) }}"
           class="px-4 py-1.5 rounded-full font-semibold border transition-all {{ $sort === 'country' ? 'bg-orange-600 text-white border-orange-600' : 'border-gray-200 text-slate-500 bg-white' }}">
            Valsts{!! $arrow('country') !!}
        </a>
    </div>

    {{-- Cards --}}
    @if(count($teams) === 0)
        <div class="bg-white rounded-3xl border border-dashed border-gray-300 p-20 text-center">
            <p class="text-gray-400 text-lg font-medium">Nav atrasta neviena komanda</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($teams as $team)
                <div class="team-card relative bg-white rounded-3xl border-2 border-gray-100 shadow-sm overflow-hidden cursor-pointer">

                    <a href="{{ route('teams.show', $team) }}" class="absolute inset-0 z-10"></a>

                    {{-- Banner --}}
                    <div class="team-card-banner h-36 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute -top-8 -right-8 w-36 h-36 rounded-full border border-white/5"></div>
                        <div class="absolute -bottom-10 -left-10 w-44 h-44 rounded-full border border-white/5"></div>

                        @if($team->logo)
                            <img src="{{ asset('storage/' . $team->logo) }}"
                                 alt="{{ $team->name }}"
                                 class="team-card-logo relative z-10 max-h-24 max-w-[140px] object-contain drop-shadow-lg">
                        @else
                            <div class="team-card-logo relative z-10 w-20 h-20 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center text-3xl font-black shadow-xl">
                                {{ strtoupper(mb_substr($team->name, 0, 2)) }}
                            </div>
                        @endif

                        @if($team->country)
                            <span class="absolute top-3 right-3 z-20 text-white/90 text-xs font-semibold px-2.5 py-1 rounded-full border border-white/20" style="background:rgba(255,255,255,0.15);">
                                {{ $team->country }}
                            </span>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="p-5">
                        <h3 class="team-card-name text-xl font-extrabold text-slate-900 truncate leading-tight">
                            {{ $team->name }}
                        </h3>

                        <div class="mt-3 space-y-2 text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="truncate">{{ $team->country ?: '—' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                <span class="truncate">{{ $team->leagues->pluck('name')->join(', ') ?: 'Nav līgas' }}</span>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-orange-500 flex items-center gap-1">
                                Skatīt profilu
                                <svg class="team-card-arrow w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>

                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <div class="relative z-20 flex items-center gap-3 text-xs">
                                        <a href="{{ route('teams.edit', $team) }}" class="text-slate-400 hover:text-blue-600 font-semibold transition">Rediģēt</a>
                                        <form method="POST" action="{{ route('teams.destroy', $team) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Tiešām dzēst komandu?')" class="text-slate-400 hover:text-red-500 font-semibold transition">Dzēst</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
</x-layouts.app>
