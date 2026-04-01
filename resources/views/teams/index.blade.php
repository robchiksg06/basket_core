<x-layouts.app>
    <div class="max-w-6xl mx-auto py-10 px-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h2 class="text-3xl font-bold text-orange-600">Komandas</h2>

            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('teams.create') }}"
                       class="inline-flex items-center justify-center bg-orange-600 text-white px-5 py-2.5 rounded-xl hover:bg-orange-700 transition">
                        ➕ Pievienot komandu
                    </a>
                @endif
            @endauth
        </div>

        <form method="GET" action="{{ route('teams.index') }}" class="mb-8 flex flex-col md:flex-row gap-4 md:items-center">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Meklēt pēc nosaukuma..."
                class="border border-gray-300 p-3 rounded-xl w-full md:max-w-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
            >

            <select name="league" class="border border-gray-300 p-3 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">-- Visas līgas --</option>
                @foreach($leagues as $leagueOption)
                    <option value="{{ $leagueOption }}" {{ request('league') === $leagueOption ? 'selected' : '' }}>
                        {{ $leagueOption }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="bg-orange-600 text-white px-6 py-3 rounded-xl hover:bg-orange-700 transition font-semibold">
                Meklēt
            </button>
        </form>

        @php
            $toggle = fn($col) => ($sort === $col && $direction === 'asc') ? 'desc' : 'asc';
            $arrow = fn($col) => ($sort === $col ? ($direction === 'asc' ? ' 🔼' : ' 🔽') : '');
        @endphp

        <div class="flex flex-wrap gap-3 mb-6 text-sm">
            <a href="{{ route('teams.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => $toggle('name')])) }}"
               class="px-4 py-2 rounded-full border border-gray-300 hover:bg-gray-100">
                Nosaukums{!! $arrow('name') !!}
            </a>

            <a href="{{ route('teams.index', array_merge(request()->all(), ['sort' => 'country', 'direction' => $toggle('country')])) }}"
               class="px-4 py-2 rounded-full border border-gray-300 hover:bg-gray-100">
                Valsts{!! $arrow('country') !!}
            </a>

            <a href="{{ route('teams.index', array_merge(request()->all(), ['sort' => 'league', 'direction' => $toggle('league')])) }}"
               class="px-4 py-2 rounded-full border border-gray-300 hover:bg-gray-100">
                Līga{!! $arrow('league') !!}
            </a>
        </div>

        @if(count($teams) === 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">
                <p class="text-gray-500 text-lg">Nav pievienotu komandu.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($teams as $team)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-200">
                        <div class="h-44 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-6">
                            @if($team->logo)
                                <img
                                    src="{{ asset('storage/' . $team->logo) }}"
                                    alt="{{ $team->name }} logo"
                                    class="max-h-28 max-w-full object-contain"
                                >
                            @else
                                <div class="w-24 h-24 rounded-full bg-orange-600 text-white flex items-center justify-center text-2xl font-bold shadow">
                                    {{ strtoupper(mb_substr($team->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>

                        <div class="p-5">
                            <h3 class="text-xl font-bold text-slate-800 mb-3">{{ $team->name }}</h3>

                            <div class="space-y-2 text-sm text-gray-600">
                                <p>
                                    <span class="font-semibold text-gray-800">Valsts:</span>
                                    {{ $team->country }}
                                </p>
                                <p>
                                    <span class="font-semibold text-gray-800">Līga:</span>
                                    {{ $team->league }}
                                </p>
                            </div>

                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <div class="mt-5 flex items-center gap-4">
                                        <a href="{{ route('teams.edit', $team) }}"
                                           class="text-blue-600 hover:underline font-medium">
                                            Rediģēt
                                        </a>

                                        <form method="POST" action="{{ route('teams.destroy', $team) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Tiešām dzēst?')"
                                                    class="text-red-600 hover:underline font-medium">
                                                Dzēst
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>