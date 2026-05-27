<x-layouts.app>
<div class="py-10 px-4">

    {{-- Header --}}
    <div class="max-w-6xl mx-auto mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('tournaments.index') }}" class="text-orange-500 hover:underline font-medium text-sm">← Turnīri</a>
            <div class="flex items-center gap-3 mt-1 flex-wrap">
                <h1 class="text-4xl font-extrabold text-slate-900">{{ $tournament->name }}</h1>
                <span class="text-sm px-3 py-1 rounded-full font-semibold
                    {{ $tournament->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                    {{ $tournament->status === 'completed' ? '✓ Pabeigts' : '● Aktīvs' }}
                </span>
                <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-medium">
                    {{ $tournament->format === 'group_knockout' ? 'Grupas + Atzarojums' : 'Single Elimination' }}
                </span>
            </div>
            @if($tournament->description)
                <p class="text-gray-500 text-sm mt-1">{{ $tournament->description }}</p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->id() === $tournament->user_id || auth()->user()?->isAdmin())
                <form method="POST" action="{{ route('tournaments.visibility', $tournament) }}">
                    @csrf @method('PATCH')
                    <button class="px-4 py-2 rounded-xl border text-sm font-medium transition
                        {{ $tournament->is_public ? 'border-green-200 text-green-700 hover:bg-green-50' : 'border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                        {{ $tournament->is_public ? '🌐 Publisks' : '🔒 Privāts' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('tournaments.destroy', $tournament) }}"
                      onsubmit="return confirm('Dzēst šo turnīru?')">
                    @csrf @method('DELETE')
                    <button class="px-4 py-2 rounded-xl border border-red-200 text-red-500 hover:bg-red-50 transition text-sm font-medium">Dzēst</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Champion --}}
    @if($tournament->status === 'completed')
        @php $champion = $rounds->get($totalRounds)?->first()?->winner @endphp
        @if($champion)
            <div class="max-w-6xl mx-auto mb-10">
                <div class="bg-gradient-to-r from-yellow-400 to-orange-400 rounded-2xl p-6 flex items-center gap-5 shadow-md">
                    <span class="text-5xl">🏆</span>
                    <div>
                        <div class="text-yellow-900/70 text-sm font-semibold uppercase tracking-wide">Turnīra uzvarētājs</div>
                        <div class="text-3xl font-extrabold text-white">{{ $champion->name }}</div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- ════════════════════════════════════════════════════════════════
         GROUP KNOCKOUT FORMAT
    ════════════════════════════════════════════════════════════════ --}}
    @if($tournament->format === 'group_knockout')
        @php
            $hasKnockout = $rounds->isNotEmpty();
            $groupDone   = $tournament->groupStageComplete();
            $activeTab   = request('tab', $hasKnockout && $groupDone ? 'knockout' : 'groups');
        @endphp

        {{-- Tabs --}}
        <div class="max-w-6xl mx-auto mb-6 flex gap-1 border-b border-gray-200">
            <a href="{{ route('tournaments.show', $tournament) }}?tab=groups"
               class="px-5 py-3 text-sm font-semibold border-b-2 -mb-px transition
                   {{ $activeTab === 'groups' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                Grupas posms
            </a>
            <a href="{{ route('tournaments.show', $tournament) }}?tab=knockout"
               class="px-5 py-3 text-sm font-semibold border-b-2 -mb-px transition
                   {{ $activeTab === 'knockout' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                Atzarojums
                @if(!$hasKnockout)
                    <span class="ml-1 text-xs text-gray-400">(vēl nav)</span>
                @endif
            </a>
        </div>

        {{-- ── GROUPS TAB ── --}}
        @if($activeTab === 'groups')
            @php $isOwner = auth()->id() === $tournament->user_id || auth()->user()?->isAdmin(); @endphp
            @if($groupDone && !$hasKnockout && $isOwner)
                <div class="max-w-6xl mx-auto mb-6">
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-5 flex items-center justify-between gap-4">
                        <div>
                            <div class="font-bold text-green-800">✅ Grupas posms pabeigts!</div>
                            <div class="text-sm text-green-700 mt-0.5">
                                Top {{ $tournament->advance_per_group }} no katras grupas turpina uz atzarojumu.
                            </div>
                        </div>
                        <form method="POST" action="{{ route('tournaments.generate-knockout', $tournament) }}">
                            @csrf
                            <button class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition whitespace-nowrap">
                                Ģenerēt atzarojumu →
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($groups as $group)
                    @php $standings = $group->standings(); @endphp
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        {{-- Group header --}}
                        <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4">
                            <h3 class="text-lg font-extrabold text-white">Grupa {{ $group->name }}</h3>
                        </div>

                        {{-- Standings table --}}
                        <div class="px-4 pt-4 pb-2">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-xs font-semibold text-gray-400 uppercase">
                                        <th class="text-left pb-2 pl-1">#</th>
                                        <th class="text-left pb-2">Komanda</th>
                                        <th class="text-center pb-2">Sp</th>
                                        <th class="text-center pb-2">U</th>
                                        <th class="text-center pb-2">Z</th>
                                        <th class="text-center pb-2">+/-</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($standings as $i => $row)
                                        @php $advances = $i < $tournament->advance_per_group; @endphp
                                        <tr class="{{ $advances ? 'bg-orange-50/50' : '' }}">
                                            <td class="py-2 pl-1 text-gray-400 text-xs">{{ $i + 1 }}</td>
                                            <td class="py-2 font-semibold {{ $advances ? 'text-orange-700' : 'text-slate-700' }}">
                                                {{ $advances ? '→ ' : '' }}{{ $row['team']->name }}
                                            </td>
                                            <td class="py-2 text-center text-gray-500">{{ $row['played'] }}</td>
                                            <td class="py-2 text-center font-bold {{ $advances ? 'text-orange-600' : 'text-slate-600' }}">{{ $row['wins'] }}</td>
                                            <td class="py-2 text-center text-gray-400">{{ $row['losses'] }}</td>
                                            <td class="py-2 text-center text-gray-500">
                                                @php $diff = $row['points_for'] - $row['points_against']; @endphp
                                                <span class="{{ $diff > 0 ? 'text-green-600' : ($diff < 0 ? 'text-red-500' : 'text-gray-400') }}">
                                                    {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Group matches --}}
                        <div class="border-t border-gray-100 px-4 py-3 space-y-3">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Spēles</div>
                            @foreach($group->matches as $m)
                                @php
                                    $gmKey    = 'group:' . $m->id;
                                    $gmCounts = $matchVoteCounts->get($gmKey, collect());
                                    $gmTotal  = $gmCounts->sum();
                                    $userGmv  = $userMatchVotes->get($gmKey);
                                @endphp
                                <div class="rounded-xl border {{ $m->winner_id ? 'border-gray-200 bg-gray-50' : 'border-orange-200 bg-orange-50/30' }} px-3 py-2">
                                    {{-- Score row --}}
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex-1 space-y-1">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-semibold {{ $m->winner_id && $m->winner_id === $m->team1_id ? 'text-orange-700' : ($m->winner_id ? 'text-gray-400' : 'text-slate-700') }}">
                                                    {{ $m->winner_id === $m->team1_id ? '✓ ' : '' }}{{ $m->team1->name }}
                                                </span>
                                                @if($m->winner_id)
                                                    <span class="font-bold text-sm {{ $m->winner_id === $m->team1_id ? 'text-orange-600' : 'text-gray-400' }}">{{ $m->team1_score }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-semibold {{ $m->winner_id && $m->winner_id === $m->team2_id ? 'text-orange-700' : ($m->winner_id ? 'text-gray-400' : 'text-slate-700') }}">
                                                    {{ $m->winner_id === $m->team2_id ? '✓ ' : '' }}{{ $m->team2->name }}
                                                </span>
                                                @if($m->winner_id)
                                                    <span class="font-bold text-sm {{ $m->winner_id === $m->team2_id ? 'text-orange-600' : 'text-gray-400' }}">{{ $m->team2_score }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if(!$m->winner_id && $tournament->status === 'active' && $isOwner)
                                            <form method="POST"
                                                  action="{{ route('tournaments.group-result', [$tournament, $m]) }}"
                                                  class="flex items-center gap-1 flex-shrink-0">
                                                @csrf
                                                <input type="number" name="team1_score" min="0" placeholder="0"
                                                       class="w-12 border border-gray-300 rounded-lg px-1 py-1 text-sm text-center font-bold focus:outline-none focus:ring-1 focus:ring-orange-400"
                                                       required>
                                                <span class="text-gray-300">:</span>
                                                <input type="number" name="team2_score" min="0" placeholder="0"
                                                       class="w-12 border border-gray-300 rounded-lg px-1 py-1 text-sm text-center font-bold focus:outline-none focus:ring-1 focus:ring-orange-400"
                                                       required>
                                                <button class="bg-orange-600 hover:bg-orange-700 text-white text-xs px-2 py-1.5 rounded-lg transition font-semibold">
                                                    OK
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- Per-match vote (only for pending matches) --}}
                                    @if(!$m->winner_id)
                                        <div class="mt-2 pt-2 border-t border-orange-100">
                                            <div class="text-xs text-gray-400 mb-1.5">
                                                🗳️ Kurš uzvarēs?
                                                @if($gmTotal > 0)
                                                    <span class="text-gray-300 ml-1">{{ $gmTotal }} balsi</span>
                                                @endif
                                            </div>
                                            <div class="grid grid-cols-2 gap-1.5">
                                                @foreach([
                                                    ['team' => $m->team1, 'id' => $m->team1_id],
                                                    ['team' => $m->team2, 'id' => $m->team2_id],
                                                ] as $side)
                                                    @php
                                                        $sc = $gmCounts->get($side['id'], 0);
                                                        $sp = $gmTotal > 0 ? round($sc / $gmTotal * 100) : 0;
                                                        $isMv = $userGmv && $userGmv->voted_team_id == $side['id'];
                                                    @endphp
                                                    <form method="POST" action="{{ route('tournaments.match-vote', $tournament) }}">
                                                        @csrf
                                                        <input type="hidden" name="match_id" value="{{ $m->id }}">
                                                        <input type="hidden" name="match_type" value="group">
                                                        <input type="hidden" name="voted_team_id" value="{{ $side['id'] }}">
                                                        <button type="submit"
                                                                class="relative w-full overflow-hidden rounded-lg border px-2 py-1.5 text-center text-xs font-semibold transition
                                                                    {{ $isMv
                                                                        ? 'border-orange-400 bg-orange-500 text-white'
                                                                        : 'border-gray-200 bg-white text-slate-600 hover:border-orange-300 hover:bg-orange-50' }}">
                                                            @if($gmTotal > 0 && !$isMv)
                                                                <span class="absolute inset-y-0 left-0 bg-orange-100 rounded-lg"
                                                                      style="width:{{ $sp }}%"></span>
                                                            @endif
                                                            <span class="relative">{{ $side['team']?->name }}
                                                                @if($gmTotal > 0)
                                                                    <span class="{{ $isMv ? 'text-orange-100' : 'text-gray-400' }}"> {{ $sp }}%</span>
                                                                @endif
                                                            </span>
                                                        </button>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        {{-- ── KNOCKOUT TAB ── --}}
        @else
            @if(!$hasKnockout)
                <div class="max-w-6xl mx-auto text-center py-20 text-gray-400">
                    <div class="text-5xl mb-4">⏳</div>
                    <p class="text-lg font-semibold text-slate-600">Atzarojums vēl nav ģenerēts</p>
                    <p class="text-sm mt-2">Vispirms jāpabeidz grupas posms.</p>
                    <a href="{{ route('tournaments.show', $tournament) }}?tab=groups"
                       class="mt-6 inline-block bg-orange-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-orange-700 transition">
                        Doties uz grupām
                    </a>
                </div>
            @else
                @include('tournaments._bracket', compact('tournament', 'rounds', 'totalRounds'))
            @endif
        @endif

    {{-- ════════════════════════════════════════════════════════════════
         SINGLE ELIMINATION FORMAT
    ════════════════════════════════════════════════════════════════ --}}
    @else
        @include('tournaments._bracket', compact('tournament', 'rounds', 'totalRounds'))
    @endif


    {{-- ════════════════════════════════════════════════════════════════
         TURNĪRA STATISTIKA
    ════════════════════════════════════════════════════════════════ --}}
    @if($tournamentStats->isNotEmpty())
        <div class="max-w-6xl mx-auto mt-12">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                    <span class="text-2xl">📊</span>
                    <div>
                        <h2 class="text-lg font-extrabold text-white">Turnīra statistika</h2>
                        <p class="text-slate-400 text-xs mt-0.5">No visām pabeigtajām spēlēm ar protokolu</p>
                    </div>
                </div>

                {{-- Top scorer highlight --}}
                @php $top = $tournamentStats->first(); @endphp
                @if($top && $top->pts > 0)
                    <div class="bg-orange-50 border-b border-orange-100 px-6 py-4 flex items-center gap-4">
                        <span class="text-3xl">🥇</span>
                        <div>
                            <div class="text-xs font-semibold text-orange-500 uppercase tracking-wide">Turnīra labākais rezultātguvējs</div>
                            <div class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $top->player_name }}</div>
                        </div>
                        <div class="ml-auto text-right">
                            <div class="text-3xl font-black text-orange-600">{{ $top->pts }}</div>
                            <div class="text-xs text-gray-400">punkti</div>
                        </div>
                    </div>
                @endif

                {{-- Stats table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs text-slate-400 uppercase tracking-wider border-b border-gray-100">
                                <th class="text-left py-3 px-4 font-semibold w-8">#</th>
                                <th class="text-left py-3 px-4 font-semibold">Spēlētājs</th>
                                <th class="text-center py-3 px-3 font-semibold">Sp.</th>
                                <th class="text-center py-3 px-3 font-semibold text-orange-500">PTS</th>
                                <th class="text-center py-3 px-3 font-semibold">3PT</th>
                                <th class="text-center py-3 px-3 font-semibold">REB</th>
                                <th class="text-center py-3 px-3 font-semibold">AST</th>
                                <th class="text-center py-3 px-3 font-semibold">STL</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($tournamentStats->take(15) as $i => $row)
                                <tr class="{{ $i === 0 ? 'bg-orange-50/50' : 'hover:bg-gray-50' }} transition">
                                    <td class="py-3 px-4 text-gray-400 text-xs font-semibold">
                                        {{ match($i) { 0 => '🥇', 1 => '🥈', 2 => '🥉', default => $i + 1 } }}
                                    </td>
                                    <td class="py-3 px-4 font-semibold {{ $i === 0 ? 'text-orange-700' : 'text-slate-800' }}">
                                        {{ $row->player_name }}
                                    </td>
                                    <td class="py-3 px-3 text-center text-gray-400 text-xs">{{ $row->games }}</td>
                                    <td class="py-3 px-3 text-center font-black {{ $i === 0 ? 'text-orange-600 text-base' : 'text-slate-700' }}">
                                        {{ $row->pts }}
                                    </td>
                                    <td class="py-3 px-3 text-center text-gray-500">{{ $row->threes }}</td>
                                    <td class="py-3 px-3 text-center text-gray-500">{{ $row->reb }}</td>
                                    <td class="py-3 px-3 text-center text-gray-500">{{ $row->ast }}</td>
                                    <td class="py-3 px-3 text-center text-gray-500">{{ $row->stl }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
</x-layouts.app>
