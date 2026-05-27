@php
    $slotH  = 90;
    $matchH = 58;
    $rw     = 200;
    $rg     = 56;

    $r1Count     = $rounds->get(1)?->count() ?? 1;
    $totalH      = $r1Count * $slotH;
    $totalW      = $totalRounds * $rw + ($totalRounds - 1) * $rg;

    $roundLabels = [];
    for ($r = 1; $r <= $totalRounds; $r++) {
        $roundLabels[$r] = match(true) {
            $r === $totalRounds     => 'Fināls',
            $r === $totalRounds - 1 => 'Pusfināls',
            $r === $totalRounds - 2 => 'Ceturtdaļfināls',
            default                 => 'Kārta ' . $r,
        };
    }
@endphp

<div class="max-w-full overflow-x-auto pb-4">
    <div class="mx-auto px-4" style="width: {{ $totalW + 32 }}px">

        {{-- Round labels --}}
        <div class="flex mb-3" style="width: {{ $totalW }}px; gap: {{ $rg }}px">
            @foreach($rounds as $round => $_)
                <div class="text-center text-xs font-bold text-gray-400 uppercase tracking-widest"
                     style="width: {{ $rw }}px; flex-shrink: 0">
                    {{ $roundLabels[$round] }}
                </div>
            @endforeach
        </div>

        {{-- Bracket --}}
        <div class="relative" style="height: {{ $totalH }}px; width: {{ $totalW }}px">
            @foreach($rounds as $round => $matches)
                @php
                    $sH   = $slotH * pow(2, $round - 1);
                    $xL   = ($round - 1) * ($rw + $rg);
                    $xR   = $xL + $rw;
                    $xMid = $xR + $rg / 2;
                    $isLast = $round == $totalRounds;
                @endphp

                @foreach($matches as $i => $match)
                    @php
                        $yMid  = $i * $sH + $sH / 2;
                        $yTop  = $yMid - $matchH / 2;
                        $isEven = $i % 2 === 0;
                        $won   = (bool) $match->winner_id;
                        $isBye = $won && (!$match->team1_id || !$match->team2_id);
                    @endphp

                    {{-- Match card --}}
                    <div class="absolute bg-white rounded-xl border-2 overflow-hidden shadow-sm
                        {{ $match->isPending() ? 'border-orange-300' : ($won ? 'border-gray-200' : 'border-dashed border-gray-200') }}"
                         style="left:{{ $xL }}px; top:{{ $yTop }}px; width:{{ $rw }}px; height:{{ $matchH }}px">

                        @foreach([
                            ['team'=>$match->team1,'score'=>$match->team1_score,'id'=>$match->team1_id],
                            ['team'=>$match->team2,'score'=>$match->team2_score,'id'=>$match->team2_id],
                        ] as $side)
                            @php
                                $isW = $won && $match->winner_id == $side['id'];
                                $isL = $won && $match->winner_id != $side['id'] && $side['id'];
                            @endphp
                            <div class="flex items-center justify-between px-3 border-b last:border-b-0 {{ $isW ? 'bg-orange-50' : '' }}"
                                 style="height:{{ $matchH/2 }}px">
                                <span class="text-xs font-semibold truncate max-w-[130px]
                                    {{ $isW ? 'text-orange-700' : ($isL ? 'text-gray-300' : 'text-slate-700') }}">
                                    @if($side['team'])
                                        {{ $isW ? '🏆 ' : '' }}{{ $side['team']->name }}
                                    @else
                                        <span class="text-gray-300 italic">—</span>
                                    @endif
                                </span>
                                @if($won && $side['score'] !== null)
                                    <span class="text-sm font-bold ml-1 {{ $isW ? 'text-orange-600' : 'text-gray-300' }}">
                                        {{ $side['score'] }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Connector lines --}}
                    @if(!$isLast)
                        @php $lc = $won ? '#f97316' : '#e5e7eb'; @endphp
                        <div class="absolute" style="background:{{ $lc }};left:{{ $xR }}px;top:{{ $yMid-1 }}px;width:{{ $rg/2 }}px;height:2px"></div>
                        @if($isEven)
                            <div class="absolute" style="background:#e5e7eb;left:{{ $xMid-1 }}px;top:{{ $yMid }}px;width:2px;height:{{ $sH }}px"></div>
                            <div class="absolute" style="background:#e5e7eb;left:{{ $xMid }}px;top:{{ $yMid+$sH/2-1 }}px;width:{{ $rg/2 }}px;height:2px"></div>
                        @endif
                    @endif

                    {{-- Action below card --}}
                    @php $isOwner = auth()->id() === $tournament->user_id || auth()->user()?->isAdmin(); @endphp
                    @if($match->isPending() && $tournament->status === 'active' && $isOwner)
                        <div class="absolute" style="left:{{ $xL }}px;top:{{ $yTop+$matchH+4 }}px;width:{{ $rw }}px">
                            <a href="{{ route('games.create', ['tournament_match_id' => $match->id]) }}"
                               class="flex items-center justify-center gap-1 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition w-full">
                                🏀 Sākt protokolu
                            </a>
                        </div>
                    @elseif($won && !$isBye)
                        @php $lg = \App\Models\Game::where('tournament_match_id', $match->id)->first(); @endphp
                        @if($lg)
                            <div class="absolute text-center" style="left:{{ $xL }}px;top:{{ $yTop+$matchH+4 }}px;width:{{ $rw }}px">
                                <a href="{{ route('games.show', $lg) }}" class="text-xs text-orange-500 hover:underline">📋 Skatīt protokolu</a>
                            </div>
                        @endif
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
</div>

{{-- Pending matches panel --}}
@php
    $pendingMatches = collect();
    foreach ($rounds as $round => $matches) {
        foreach ($matches as $match) {
            if ($match->isPending() && $tournament->status === 'active') {
                $pendingMatches->push(['round' => $round, 'match' => $match, 'label' => $roundLabels[$round]]);
            }
        }
    }
@endphp

@if($pendingMatches->isNotEmpty())
    <div class="max-w-6xl mx-auto mt-12">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Gaidāmās spēles</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($pendingMatches as $pm)
                @php
                    $m = $pm['match'];
                    $matchKey = 'knockout:' . $m->id;
                    $mvCounts = $matchVoteCounts->get($matchKey, collect());
                    $mvTotal = $mvCounts->sum();
                    $userMv = $userMatchVotes->get($matchKey);
                @endphp
                <div class="bg-white rounded-2xl border border-orange-200 shadow-sm overflow-hidden">
                    <div class="bg-orange-50 px-4 py-2 border-b border-orange-100">
                        <span class="text-xs font-semibold text-orange-600 uppercase tracking-wide">{{ $pm['label'] }}</span>
                    </div>
                    <div class="px-4 py-4">

                        {{-- Per-match vote --}}
                        @if($m->team1_id && $m->team2_id)
                            <div class="mb-4">
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                                    🗳️ Kurš uzvarēs?
                                    @if($mvTotal > 0)
                                        <span class="normal-case font-normal text-gray-300 ml-1">{{ $mvTotal }} balsi</span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach([
                                        ['team' => $m->team1, 'id' => $m->team1_id],
                                        ['team' => $m->team2, 'id' => $m->team2_id],
                                    ] as $side)
                                        @php
                                            $sideCount = $mvCounts->get($side['id'], 0);
                                            $sidePct   = $mvTotal > 0 ? round($sideCount / $mvTotal * 100) : 0;
                                            $isMyVote  = $userMv && $userMv->voted_team_id == $side['id'];
                                        @endphp
                                        <form method="POST" action="{{ route('tournaments.match-vote', $tournament) }}">
                                            @csrf
                                            <input type="hidden" name="match_id" value="{{ $m->id }}">
                                            <input type="hidden" name="match_type" value="knockout">
                                            <input type="hidden" name="voted_team_id" value="{{ $side['id'] }}">
                                            <button type="submit"
                                                    class="relative w-full overflow-hidden rounded-xl border-2 px-3 py-2.5 text-center text-xs font-bold transition
                                                        {{ $isMyVote
                                                            ? 'border-orange-400 bg-orange-500 text-white'
                                                            : 'border-gray-200 bg-gray-50 text-slate-700 hover:border-orange-300 hover:bg-orange-50' }}">
                                                @if($mvTotal > 0 && !$isMyVote)
                                                    <span class="absolute inset-y-0 left-0 bg-orange-100 transition-all rounded-xl"
                                                          style="width: {{ $sidePct }}%"></span>
                                                @endif
                                                <span class="relative">
                                                    {{ $side['team']?->name }}
                                                    @if($mvTotal > 0)
                                                        <span class="block text-xs mt-0.5 {{ $isMyVote ? 'text-orange-100' : 'text-gray-400' }}">{{ $sidePct }}%</span>
                                                    @endif
                                                    @if($isMyVote)
                                                        <span class="block text-orange-100 text-xs mt-0.5">✓ Tavs balsojums</span>
                                                    @endif
                                                </span>
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Owner actions --}}
                        @if($isOwner)
                            <div class="space-y-2 border-t border-gray-100 pt-3">
                                <a href="{{ route('games.create', ['tournament_match_id' => $m->id]) }}"
                                   class="flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition w-full">
                                    🏀 Sākt protokolu
                                </a>
                                <details class="text-xs">
                                    <summary class="text-gray-400 cursor-pointer hover:text-gray-600 text-center py-1">vai ievadīt tikai rezultātu</summary>
                                    <form method="POST" action="{{ route('tournaments.result', [$tournament, $m]) }}" class="mt-2">
                                        @csrf
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 text-center">
                                                <div class="text-xs text-gray-400 mb-1 truncate">{{ $m->team1?->name }}</div>
                                                <input type="number" name="team1_score" min="0" placeholder="0"
                                                       class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center font-bold focus:outline-none focus:ring-1 focus:ring-orange-400" required>
                                            </div>
                                            <span class="text-gray-300 font-bold text-lg">:</span>
                                            <div class="flex-1 text-center">
                                                <div class="text-xs text-gray-400 mb-1 truncate">{{ $m->team2?->name }}</div>
                                                <input type="number" name="team2_score" min="0" placeholder="0"
                                                       class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center font-bold focus:outline-none focus:ring-1 focus:ring-orange-400" required>
                                            </div>
                                        </div>
                                        <button class="w-full mt-2 bg-slate-700 hover:bg-slate-800 text-white text-xs py-1.5 rounded-lg transition font-semibold">
                                            Saglabāt rezultātu
                                        </button>
                                    </form>
                                </details>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
