<x-layouts.app>
<div class="max-w-3xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-4xl font-extrabold text-slate-900">🏆 TOP saraksts</h1>
        <p class="text-gray-400 text-sm mt-1">Statistika no visām publiskajām pabeigtajām spēlēm</p>
    </div>

    {{-- Tabs --}}
    @php
        $tabs = [
            'pts'    => ['label' => '🏀 Punkti',           'col' => 'pts'],
            'threes' => ['label' => '🎯 3-punktu metieni', 'col' => 'threes'],
            'reb'    => ['label' => '💪 Atlēkušās',        'col' => 'reb'],
            'ast'    => ['label' => '🤝 Piespēles',        'col' => 'ast'],
            'stl'    => ['label' => '⚡ Pārtverti',        'col' => 'stl'],
        ];
    @endphp

    <div class="flex gap-1 border-b border-gray-200 mb-6 overflow-x-auto">
        @foreach($tabs as $key => $meta)
            <a href="{{ route('leaderboard', ['tab' => $key]) }}"
               class="whitespace-nowrap px-4 py-3 text-sm font-semibold border-b-2 -mb-px transition
                   {{ $tab === $key
                       ? 'border-orange-500 text-orange-600'
                       : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                {{ $meta['label'] }}
            </a>
        @endforeach
    </div>

    {{-- Table --}}
    @if($leaders->isEmpty())
        <div class="text-center py-20">
            <div class="text-5xl mb-4">📋</div>
            <p class="text-lg font-semibold text-slate-500">Vēl nav pietiekami datu</p>
            <p class="text-sm text-gray-400 mt-1">Pabeidz kādu publisku spēli ar protokolu, lai šeit parādītos statistika.</p>
            <a href="{{ route('games.create') }}"
               class="mt-6 inline-block bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-semibold transition">
                + Jauna spēle
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            {{-- Column header --}}
            <div class="grid grid-cols-12 px-5 py-3 bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                <div class="col-span-1">#</div>
                <div class="col-span-7">Spēlētājs</div>
                <div class="col-span-2 text-center">Sp.</div>
                <div class="col-span-2 text-right text-orange-500">
                    {{ $tabs[$tab]['label'] }}
                </div>
            </div>

            @foreach($leaders as $i => $row)
                @php
                    $col = $tabs[$tab]['col'];
                    $val = $row->$col;
                    $isTop3 = $i < 3;
                    $medal = match($i) { 0 => '🥇', 1 => '🥈', 2 => '🥉', default => null };
                @endphp
                <div class="grid grid-cols-12 items-center px-5 py-4
                    {{ $i === 0 ? 'bg-orange-50 border-b border-orange-100' : 'border-b border-gray-50' }}
                    {{ $i === count($leaders) - 1 ? 'border-b-0' : '' }}">

                    {{-- Rank --}}
                    <div class="col-span-1">
                        @if($medal)
                            <span class="text-lg leading-none">{{ $medal }}</span>
                        @else
                            <span class="text-sm font-semibold text-gray-400">{{ $i + 1 }}</span>
                        @endif
                    </div>

                    {{-- Name --}}
                    <div class="col-span-7">
                        <span class="font-bold {{ $i === 0 ? 'text-orange-700 text-base' : 'text-slate-800 text-sm' }}">
                            {{ $row->player_name }}
                        </span>
                    </div>

                    {{-- Games --}}
                    <div class="col-span-2 text-center text-sm text-gray-400">
                        {{ $row->games }}
                    </div>

                    {{-- Stat --}}
                    <div class="col-span-2 text-right">
                        <span class="font-extrabold text-lg {{ $i === 0 ? 'text-orange-600' : 'text-slate-700' }}">
                            {{ $val }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Mini stat breakdown for top player --}}
        @php $top = $leaders->first(); @endphp
        @if($top)
            <div class="mt-6 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-5 text-white">
                <div class="text-xs font-semibold uppercase tracking-wider opacity-75 mb-1">Labākais šajā kategorijā</div>
                <div class="text-2xl font-extrabold mb-3">{{ $top->player_name }}</div>
                <div class="grid grid-cols-5 gap-3 text-center">
                    @foreach([
                        ['val' => $top->pts,    'label' => 'Punkti'],
                        ['val' => $top->threes, 'label' => '3-punk.'],
                        ['val' => $top->reb,    'label' => 'Atlēk.'],
                        ['val' => $top->ast,    'label' => 'Piespēles'],
                        ['val' => $top->stl,    'label' => 'Pārtv.'],
                    ] as $s)
                        <div class="bg-white/20 rounded-xl py-2.5">
                            <div class="text-xl font-extrabold">{{ $s['val'] }}</div>
                            <div class="text-xs opacity-75 mt-0.5">{{ $s['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

</div>
</x-layouts.app>
