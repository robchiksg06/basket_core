<x-layouts.app>
    <div class="max-w-5xl mx-auto py-8">
        <h2 class="text-2xl font-bold mb-4 text-orange-600">Komandas</h2>

        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('teams.create') }}" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 mb-4 inline-block">
                    ➕ Pievienot komandu
                </a>
            @endif
        @endauth

        <form method="GET" action="{{ route('teams.index') }}" class="mb-4 flex flex-wrap gap-4 items-center">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Meklēt pēc nosaukuma..."
                class="border border-gray-300 p-2 rounded w-full max-w-sm"
            >

            <select name="league" class="border border-gray-300 p-2 rounded">
                <option value="">-- Visas līgas --</option>
                @foreach($leagues as $leagueOption)
                    <option value="{{ $leagueOption }}" {{ request('league') === $leagueOption ? 'selected' : '' }}>
                        {{ $leagueOption }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
                Meklēt
            </button>
        </form>

        @php
            $toggle = fn($col) => ($sort === $col && $direction === 'asc') ? 'desc' : 'asc';
            $arrow = fn($col) => ($sort === $col ? ($direction === 'asc' ? ' 🔼' : ' 🔽') : '');
        @endphp

        @if(count($teams) === 0)
            <p class="text-gray-500">Nav pievienotu komandu.</p>
        @else
            <table class="w-full border border-gray-300 dark:border-gray-600 mt-4">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="p-2 text-left">
                            <a href="{{ route('teams.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => $toggle('name')])) }}">
                                Nosaukums{!! $arrow('name') !!}
                            </a>
                        </th>
                        <th class="p-2 text-left">
                            <a href="{{ route('teams.index', array_merge(request()->all(), ['sort' => 'country', 'direction' => $toggle('country')])) }}">
                                Valsts{!! $arrow('country') !!}
                            </a>
                        </th>
                        <th class="p-2 text-left">
                            <a href="{{ route('teams.index', array_merge(request()->all(), ['sort' => 'league', 'direction' => $toggle('league')])) }}">
                                Līga{!! $arrow('league') !!}
                            </a>
                        </th>

                        @auth
                            @if(Auth::user()->role === 'admin')
                                <th class="p-2 text-left">Darbības</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                        <tr class="border-t border-gray-300 dark:border-gray-600">
                            <td class="p-2">{{ $team->name }}</td>
                            <td class="p-2">{{ $team->country }}</td>
                            <td class="p-2">{{ $team->league }}</td>

                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <td class="p-2">
                                        <div class="flex gap-2">
                                            <a href="{{ route('teams.edit', $team) }}" class="text-blue-600 hover:underline">Rediģēt</a>
                                            <form method="POST" action="{{ route('teams.destroy', $team) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Tiešām dzēst?')" class="text-red-600 hover:underline">Dzēst</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-layouts.app>
