<x-layouts.app>
<div class="max-w-6xl mx-auto py-10 px-4">

    <form method="GET" action="{{ route('tournaments.index') }}" class="mb-6">
        <div class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Meklēt turnīru pēc nosaukuma..."
                   class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            <button type="submit"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                Meklēt
            </button>
            @if(request('search'))
                <a href="{{ route('tournaments.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-500 hover:bg-gray-50 text-sm transition">
                    ✕
                </a>
            @endif
        </div>
    </form>

    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('tournaments.index', array_merge(request()->query(), ['filter' => null])) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition
               {{ request('filter') !== 'mine' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Visi turnīri
        </a>
        <a href="{{ route('tournaments.index', array_merge(request()->query(), ['filter' => 'mine'])) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition
               {{ request('filter') === 'mine' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Mani turnīri
        </a>
    </div>

    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900">🏆 Turnīri</h1>
            <p class="text-gray-500 mt-1 text-sm">Single elimination turnīri ar atzarojumu</p>
        </div>
        <a href="{{ route('tournaments.create') }}"
           class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-bold shadow-sm transition">
            + Jauns turnīrs
        </a>
    </div>

    @if($tournaments->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="text-7xl mb-6">🏆</div>
            @if(request('search'))
                <h3 class="text-xl font-bold text-slate-700 mb-2">Nekas netika atrasts</h3>
                <p class="text-gray-400 mb-6 text-sm">Turnīrs ar nosaukumu "{{ request('search') }}" neeksistē.</p>
                <a href="{{ route('tournaments.index') }}" class="text-orange-600 hover:underline text-sm">Skatīt visus turnīrus</a>
            @else
                <h3 class="text-xl font-bold text-slate-700 mb-2">Vēl nav neviena turnīra</h3>
                <p class="text-gray-400 mb-6 text-sm">Izveido pirmo turnīru un uzcel savu atzarojumu!</p>
                <a href="{{ route('tournaments.create') }}"
                   class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-semibold transition">
                    Izveidot turnīru
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tournaments as $t)
                <a href="{{ route('tournaments.show', $t) }}"
                   class="group block bg-white rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 overflow-hidden">
                    <div class="h-2 {{ $t->status === 'completed' ? 'bg-green-400' : ($t->status === 'active' ? 'bg-orange-500' : 'bg-gray-300') }}"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-bold text-slate-800 group-hover:text-orange-600 transition leading-tight">
                                {{ $t->name }}
                            </h3>
                            <span class="ml-2 flex-shrink-0 text-xs px-2.5 py-1 rounded-full font-semibold
                                {{ $t->status === 'completed' ? 'bg-green-100 text-green-700' :
                                   ($t->status === 'active'    ? 'bg-orange-100 text-orange-700' :
                                                                  'bg-gray-100 text-gray-500') }}">
                                {{ $t->status === 'draft' ? 'Sagatave' : ($t->status === 'active' ? 'Aktīvs' : 'Pabeigts') }}
                            </span>
                        </div>
                        @if($t->description)
                            <p class="text-sm text-gray-400 mb-4 line-clamp-2">{{ $t->description }}</p>
                        @endif
                        <div class="flex items-center gap-4 text-sm text-gray-400 border-t border-gray-100 pt-4 mt-4">
                            <span>🏀 {{ $t->teams_count }} komandas</span>
                            <span class="text-gray-200">|</span>
                            <span>{{ $t->created_at->format('d.m.Y') }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
</x-layouts.app>
