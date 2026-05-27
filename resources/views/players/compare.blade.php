<x-layouts.app>
<div class="max-w-5xl mx-auto py-10 px-4">

    <div class="mb-8">
        <a href="{{ route('players.public') }}" class="text-orange-500 hover:underline text-sm font-medium">← Spēlētāji</a>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-2">Spēlētāju salīdzināšana</h1>
        <p class="text-gray-400 text-sm mt-1">Izvēlies divus spēlētājus un salīdzini viņu statistiku</p>
    </div>

    {{-- Selector --}}
    <form method="GET" action="{{ route('players.compare') }}" id="compare-form" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach([1, 2] as $n)
                @php
                    $selectedId = request('player' . $n);
                    $selectedPlayer = $selectedId ? $players->firstWhere('id', $selectedId) : null;
                @endphp
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Spēlētājs #{{ $n }}</label>
                    <div class="relative" data-autocomplete="player{{ $n }}">
                        <input type="text"
                               class="ac-input w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="Raksti vārdu vai uzvārdu..."
                               value="{{ $selectedPlayer ? $selectedPlayer->name . ($selectedPlayer->team ? ' ('.$selectedPlayer->team.')' : '') : '' }}"
                               autocomplete="off">
                        <input type="hidden" name="player{{ $n }}" class="ac-value" value="{{ $selectedId }}">
                        <ul class="ac-list absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg hidden max-h-56 overflow-y-auto"></ul>
                    </div>
                </div>
            @endforeach
        </div>
    </form>

    @php
        $playersJson = $players->map(fn($p) => [
            'id'   => $p->id,
            'name' => $p->name,
            'team' => $p->team ?? '',
        ])->values();
    @endphp
    <script>
        const allPlayers = @json($playersJson);

        document.querySelectorAll('[data-autocomplete]').forEach(wrapper => {
            const input  = wrapper.querySelector('.ac-input');
            const hidden = wrapper.querySelector('.ac-value');
            const list   = wrapper.querySelector('.ac-list');

            function renderList(query) {
                const q = query.toLowerCase().trim();
                const matches = q.length < 1 ? [] : allPlayers.filter(p =>
                    p.name.toLowerCase().includes(q) || p.team.toLowerCase().includes(q)
                ).slice(0, 10);

                list.innerHTML = '';
                if (matches.length === 0) { list.classList.add('hidden'); return; }

                matches.forEach(p => {
                    const li = document.createElement('li');
                    li.className = 'px-4 py-2.5 cursor-pointer hover:bg-orange-50 text-sm flex items-center justify-between gap-2 border-b border-gray-50 last:border-0';
                    li.innerHTML = `<span class="font-semibold text-slate-800">${p.name}</span>${p.team ? `<span class="text-xs text-gray-400">${p.team}</span>` : ''}`;
                    li.addEventListener('mousedown', () => {
                        input.value  = p.name + (p.team ? ` (${p.team})` : '');
                        hidden.value = p.id;
                        list.classList.add('hidden');
                        document.getElementById('compare-form').submit();
                    });
                    list.appendChild(li);
                });
                list.classList.remove('hidden');
            }

            input.addEventListener('input', () => {
                hidden.value = '';
                renderList(input.value);
            });

            input.addEventListener('focus', () => {
                if (input.value) renderList(input.value);
            });

            input.addEventListener('blur', () => {
                setTimeout(() => list.classList.add('hidden'), 150);
            });

            // Tīrīšanas poga ar Escape
            input.addEventListener('keydown', e => {
                if (e.key === 'Escape') { list.classList.add('hidden'); }
                if (e.key === 'Enter' && hidden.value) {
                    e.preventDefault();
                    document.getElementById('compare-form').submit();
                }
            });
        });
    </script>

    @if($player1 || $player2)

        {{-- Player cards --}}
        <div class="grid grid-cols-2 gap-4 mb-8">
            @foreach([$player1, $player2] as $idx => $p)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    @if($p)
                        <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-5 py-4 flex items-center gap-4">
                            @if($p->image)
                                <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}"
                                     class="w-14 h-14 rounded-full object-cover border-2 border-white/20 flex-shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 text-xl font-black text-white/60">
                                    {{ strtoupper(mb_substr($p->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <div class="text-white font-extrabold text-lg leading-tight">{{ $p->name }}</div>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    @if($p->position)
                                        <span class="text-xs font-bold bg-orange-500 text-white px-2 py-0.5 rounded-full">{{ $p->position }}</span>
                                    @endif
                                    @if($p->team)
                                        <span class="text-white/60 text-xs">{{ $p->team }}</span>
                                    @endif
                                    @if($p->height)
                                        <span class="text-white/40 text-xs">{{ $p->height }} cm</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="px-5 py-3 text-sm text-gray-500">
                            {{ $p->seasons->count() }} sezon{{ $p->seasons->count() === 1 ? 'a' : 'as' }} statistikā
                        </div>
                    @else
                        <div class="bg-slate-100 px-5 py-8 text-center text-gray-400">
                            <div class="text-3xl mb-2">👤</div>
                            <p class="text-sm">Nav izvēlēts spēlētājs #{{ $idx + 1 }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($player1 && $player2)
            @php
                $stats = ['points_per_game', 'rebounds_per_game', 'assists_per_game', 'steals_per_game', 'blocks_per_game', 'field_goal_pct', 'three_point_pct', 'free_throw_pct'];
                $labels = ['PPG', 'RPG', 'APG', 'SPG', 'BPG', 'FG%', '3P%', 'FT%'];
                $fullLabels = ['Punkti/spēle', 'Atlēcieni/spēle', 'Piespēles/spēle', 'Pārtverti/spēle', 'Bloki/spēle', 'Metiens %', 'Trīspunktu %', 'Soda metiens %'];

                $avg = function($player, $stat) {
                    $seasons = $player->seasons->whereNotNull($stat)->where($stat, '>', 0);
                    if ($seasons->isEmpty()) return null;
                    return round($seasons->avg($stat), 1);
                };
            @endphp

            {{-- Career averages --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                    <h2 class="text-white font-bold text-lg">Karjeras vidējie rādītāji</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Rādītājs</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-orange-600 uppercase">{{ $player1->name }}</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-600 uppercase">{{ $player2->name }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($stats as $i => $stat)
                                @php
                                    $v1 = $avg($player1, $stat);
                                    $v2 = $avg($player2, $stat);
                                    $p1wins = $v1 !== null && $v2 !== null && $v1 > $v2;
                                    $p2wins = $v1 !== null && $v2 !== null && $v2 > $v1;
                                @endphp
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-5 py-3 text-slate-600 font-medium">
                                        <span class="font-bold text-slate-800">{{ $labels[$i] }}</span>
                                        <span class="text-gray-400 text-xs ml-1">{{ $fullLabels[$i] }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        @if($v1 !== null)
                                            <span class="text-base font-bold {{ $p1wins ? 'text-orange-600' : 'text-slate-500' }}">
                                                {{ $v1 }}{{ in_array($stat, ['field_goal_pct','three_point_pct','free_throw_pct']) ? '%' : '' }}
                                            </span>
                                            @if($p1wins)
                                                <span class="text-orange-400 text-xs ml-1">▲</span>
                                            @endif
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        @if($v2 !== null)
                                            <span class="text-base font-bold {{ $p2wins ? 'text-orange-600' : 'text-slate-500' }}">
                                                {{ $v2 }}{{ in_array($stat, ['field_goal_pct','three_point_pct','free_throw_pct']) ? '%' : '' }}
                                            </span>
                                            @if($p2wins)
                                                <span class="text-orange-400 text-xs ml-1">▲</span>
                                            @endif
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Season by season --}}
            @php
                $allSeasons = $player1->seasons->pluck('season')
                    ->merge($player2->seasons->pluck('season'))
                    ->unique()->sort()->reverse()->values();
            @endphp

            @if($allSeasons->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4">
                        <h2 class="text-white font-bold text-lg">Pa sezonām</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 bg-slate-50">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Sezona</th>
                                    <th class="px-3 py-3 text-center text-xs font-semibold text-gray-400 uppercase" colspan="{{ count($labels) + 2 }}">
                                        {{ $player1->name }}
                                    </th>
                                    <th class="px-3 py-3 text-center text-xs font-semibold text-gray-400 uppercase border-l border-gray-200" colspan="{{ count($labels) + 2 }}">
                                        {{ $player2->name }}
                                    </th>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <th class="px-4 py-2 text-left text-xs text-gray-400"></th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">Sp</th>
                                    <th class="px-2 py-2 text-center text-xs text-orange-500 font-bold">PPG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">RPG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">APG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">SPG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">BPG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">FG%</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400 border-l border-gray-200">Sp</th>
                                    <th class="px-2 py-2 text-center text-xs text-orange-500 font-bold">PPG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">RPG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">APG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">SPG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">BPG</th>
                                    <th class="px-2 py-2 text-center text-xs text-gray-400">FG%</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($allSeasons as $season)
                                    @php
                                        $s1 = $player1->seasons->firstWhere('season', $season);
                                        $s2 = $player2->seasons->firstWhere('season', $season);
                                    @endphp
                                    <tr class="hover:bg-orange-50/20">
                                        <td class="px-4 py-2.5 font-bold text-slate-700">{{ $season }}</td>
                                        {{-- Player 1 --}}
                                        @if($s1)
                                            <td class="px-2 py-2.5 text-center text-gray-500 text-xs">{{ $s1->games_played }}</td>
                                            <td class="px-2 py-2.5 text-center font-bold {{ $s2 && $s1->points_per_game > $s2->points_per_game ? 'text-orange-600' : 'text-slate-600' }}">{{ number_format($s1->points_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-500">{{ number_format($s1->rebounds_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-500">{{ number_format($s1->assists_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-500">{{ number_format($s1->steals_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-500">{{ number_format($s1->blocks_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-400">{{ $s1->field_goal_pct !== null ? number_format($s1->field_goal_pct, 1).'%' : '—' }}</td>
                                        @else
                                            <td colspan="7" class="px-4 py-2.5 text-center text-gray-200 text-xs italic">nav datu</td>
                                        @endif
                                        {{-- Player 2 --}}
                                        @if($s2)
                                            <td class="px-2 py-2.5 text-center text-gray-500 text-xs border-l border-gray-100">{{ $s2->games_played }}</td>
                                            <td class="px-2 py-2.5 text-center font-bold {{ $s1 && $s2->points_per_game > $s1->points_per_game ? 'text-orange-600' : 'text-slate-600' }}">{{ number_format($s2->points_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-500">{{ number_format($s2->rebounds_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-500">{{ number_format($s2->assists_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-500">{{ number_format($s2->steals_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-500">{{ number_format($s2->blocks_per_game, 1) }}</td>
                                            <td class="px-2 py-2.5 text-center text-slate-400">{{ $s2->field_goal_pct !== null ? number_format($s2->field_goal_pct, 1).'%' : '—' }}</td>
                                        @else
                                            <td colspan="7" class="px-4 py-2.5 text-center text-gray-200 text-xs italic border-l border-gray-100">nav datu</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        @elseif($player1 || $player2)
            <div class="text-center py-12 text-gray-400">
                <div class="text-4xl mb-3">👈</div>
                <p class="text-slate-500 font-medium">Izvēlies otro spēlētāju salīdzināšanai</p>
            </div>
        @endif

    @else
        <div class="text-center py-16 text-gray-400">
            <div class="text-5xl mb-4">⚖️</div>
            <p class="text-slate-500 font-medium text-lg">Izvēlies divus spēlētājus augstāk</p>
            <p class="text-sm mt-1">Viņu statistika tiks salīdzināta blakus</p>
        </div>
    @endif

</div>
</x-layouts.app>
