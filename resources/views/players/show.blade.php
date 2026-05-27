<x-layouts.app>
<div class="max-w-4xl mx-auto py-10 px-4">

    {{-- Back --}}
    @if(request('from_team'))
        <a href="{{ route('teams.show', request('from_team')) }}{{ request('from_league') ? '?from_league='.request('from_league') : '' }}"
           class="text-orange-500 hover:underline font-medium text-sm">← Atpakaļ uz komandu</a>
    @else
        <a href="{{ route('players.public') }}" class="text-orange-500 hover:underline font-medium text-sm">← Atpakaļ uz spēlētājiem</a>
    @endif

    {{-- Player card --}}
    <div class="mt-4 bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-8 py-6 flex items-center gap-6">
            @if($player->image)
                <img src="{{ asset('storage/' . $player->image) }}" alt="{{ $player->name }}"
                     class="h-24 w-24 rounded-full object-cover border-4 border-white/20 flex-shrink-0">
            @else
                <div class="h-24 w-24 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                    <span class="text-4xl text-white/40">🏀</span>
                </div>
            @endif
            <div>
                <h1 class="text-3xl font-extrabold text-white">{{ $player->name }}</h1>
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    @if($player->position)
                        <span class="text-sm font-semibold bg-orange-500 text-white px-3 py-1 rounded-full">
                            {{ $player->position }}
                        </span>
                    @endif
                    @if($player->team)
                        <span class="text-sm text-white/70">{{ $player->team }}</span>
                    @endif
                    @if($player->height)
                        <span class="text-sm text-white/50">{{ $player->height }} cm</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Season stats --}}
    @php $seasons = $player->seasons; @endphp
    @if($seasons->isNotEmpty())
        <div class="mt-8">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Sezonas statistika</h2>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-200">
                                <th class="px-4 py-3 text-left font-semibold text-slate-600">Sezona</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-600">Komanda</th>
                                <th class="px-3 py-3 text-center font-semibold text-slate-600">Sp</th>
                                <th class="px-3 py-3 text-center font-semibold text-orange-600">PPG</th>
                                <th class="px-3 py-3 text-center font-semibold text-slate-600">RPG</th>
                                <th class="px-3 py-3 text-center font-semibold text-slate-600">APG</th>
                                <th class="px-3 py-3 text-center font-semibold text-slate-600">SPG</th>
                                <th class="px-3 py-3 text-center font-semibold text-slate-600">BPG</th>
                                <th class="px-3 py-3 text-center font-semibold text-slate-500">FG%</th>
                                <th class="px-3 py-3 text-center font-semibold text-slate-500">3P%</th>
                                <th class="px-3 py-3 text-center font-semibold text-slate-500">FT%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($seasons as $s)
                                <tr class="hover:bg-orange-50/30 transition">
                                    <td class="px-4 py-3 font-bold text-slate-800">{{ $s->season }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $s->team_name ?? '—' }}</td>
                                    <td class="px-3 py-3 text-center text-slate-500">{{ $s->games_played }}</td>
                                    <td class="px-3 py-3 text-center font-bold text-orange-600">{{ number_format($s->points_per_game, 1) }}</td>
                                    <td class="px-3 py-3 text-center text-slate-600">{{ number_format($s->rebounds_per_game, 1) }}</td>
                                    <td class="px-3 py-3 text-center text-slate-600">{{ number_format($s->assists_per_game, 1) }}</td>
                                    <td class="px-3 py-3 text-center text-slate-600">{{ number_format($s->steals_per_game, 1) }}</td>
                                    <td class="px-3 py-3 text-center text-slate-600">{{ number_format($s->blocks_per_game, 1) }}</td>
                                    <td class="px-3 py-3 text-center text-slate-400">{{ $s->field_goal_pct !== null ? number_format($s->field_goal_pct, 1).'%' : '—' }}</td>
                                    <td class="px-3 py-3 text-center text-slate-400">{{ $s->three_point_pct !== null ? number_format($s->three_point_pct, 1).'%' : '—' }}</td>
                                    <td class="px-3 py-3 text-center text-slate-400">{{ $s->free_throw_pct !== null ? number_format($s->free_throw_pct, 1).'%' : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Legend --}}
            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-xs text-gray-400 px-1">
                <span><strong class="text-gray-500">Sp</strong> — Spēles</span>
                <span><strong class="text-gray-500">PPG</strong> — Punkti/spēle</span>
                <span><strong class="text-gray-500">RPG</strong> — Atlēcieni/spēle</span>
                <span><strong class="text-gray-500">APG</strong> — Piespēles/spēle</span>
                <span><strong class="text-gray-500">SPG</strong> — Pārtverti/spēle</span>
                <span><strong class="text-gray-500">BPG</strong> — Bloki/spēle</span>
            </div>
        </div>
    @else
        <div class="mt-8 bg-white rounded-2xl border border-gray-200 p-8 text-center text-gray-400">
            <div class="text-3xl mb-2">📊</div>
            <p class="font-medium text-slate-500">Statistika vēl nav pievienota</p>
        </div>
    @endif

</div>
</x-layouts.app>
