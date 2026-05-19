@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    <div class="mb-8">
        <a href="{{ route('games.index') }}" class="text-orange-500 hover:underline font-medium text-sm">← Atpakaļ uz spēlēm</a>
        <h1 class="text-4xl font-extrabold text-slate-900 mt-2">Jauna spēle</h1>
        <p class="text-gray-500 mt-1">Aizpildi spēles informāciju un abu komandu sastāvus</p>
    </div>

    <form action="{{ route('games.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-5">
                <h2 class="text-lg font-bold text-white tracking-wide uppercase">Spēles informācija</h2>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nosaukums / Turnīrs</label>
                    <input type="text" name="title"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="piem. Latvijas Basketbola Čempionāts"
                           value="{{ old('title') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Datums</label>
                    <input type="date" name="game_date"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           value="{{ old('game_date') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Vieta</label>
                    <input type="text" name="location"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="piem. Rīgas Olimpiskais sporta centrs"
                           value="{{ old('location') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Mājinieku komanda</label>
                    <input type="text" name="home_team_name" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Komandas nosaukums"
                           value="{{ old('home_team_name') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Viesu komanda</label>
                    <input type="text" name="away_team_name" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Komandas nosaukums"
                           value="{{ old('away_team_name') }}">
                </div>

                <div class="md:col-span-2 pt-2 border-t border-gray-100">
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_public" value="1"
                               class="w-5 h-5 rounded accent-orange-600">
                        <span class="text-sm font-medium text-slate-700">Rādīt šo spēli publiski</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-8 py-5 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm">M</div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Mājinieku sastāvs</h2>
                        <p class="text-white/60 text-xs">Ievadi spēlētāju numurus un vārdus</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-12 gap-2 mb-3 px-1">
                        <div class="col-span-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Nr.</div>
                        <div class="col-span-9 text-xs font-semibold text-gray-400 uppercase tracking-wider">Vārds, Uzvārds</div>
                    </div>
                    <div class="space-y-2">
                        @for($i = 0; $i < 12; $i++)
                            <div class="grid grid-cols-12 gap-2 items-center">
                                <span class="col-span-1 text-xs text-gray-300 font-mono text-right pr-1">{{ $i + 1 }}</span>
                                <input type="text" name="home_players[{{ $i }}][jersey_number]"
                                       placeholder="#"
                                       class="col-span-2 border border-gray-200 rounded-xl px-2 py-2.5 text-center font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                                <input type="text" name="home_players[{{ $i }}][player_name]"
                                       placeholder="Spēlētāja vārds"
                                       class="col-span-9 border border-gray-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-5 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm">V</div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Viesu sastāvs</h2>
                        <p class="text-white/60 text-xs">Ievadi spēlētāju numurus un vārdus</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-12 gap-2 mb-3 px-1">
                        <div class="col-span-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Nr.</div>
                        <div class="col-span-9 text-xs font-semibold text-gray-400 uppercase tracking-wider">Vārds, Uzvārds</div>
                    </div>
                    <div class="space-y-2">
                        @for($i = 0; $i < 12; $i++)
                            <div class="grid grid-cols-12 gap-2 items-center">
                                <span class="col-span-1 text-xs text-gray-300 font-mono text-right pr-1">{{ $i + 1 }}</span>
                                <input type="text" name="away_players[{{ $i }}][jersey_number]"
                                       placeholder="#"
                                       class="col-span-2 border border-gray-200 rounded-xl px-2 py-2.5 text-center font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                                <input type="text" name="away_players[{{ $i }}][player_name]"
                                       placeholder="Spēlētāja vārds"
                                       class="col-span-9 border border-gray-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pb-4">
            <button type="submit"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-4 rounded-xl font-bold text-base shadow-sm transition">
                Izveidot spēli
            </button>
            <a href="{{ route('games.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Atcelt</a>
        </div>
    </form>
</div>
@endsection
