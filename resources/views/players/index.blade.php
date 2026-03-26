<x-layouts.app>
    <div class="max-w-5xl mx-auto py-8">
        <h2 class="text-2xl font-bold mb-4 text-orange-600">Spēlētāji</h2>

        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('players.create') }}" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 mb-4 inline-block">
                    ➕ Pievienot spēlētāju
                </a>
            @endif
        @endauth

        @if(count($players) === 0)
            <p class="text-gray-500">Nav pievienotu spēlētāju.</p>
        @else
            @php
                $toggle = fn($col) => ($sort === $col && $direction === 'asc') ? 'desc' : 'asc';
                $arrow = fn($col) => ($sort === $col ? ($direction === 'asc' ? ' 🔼' : ' 🔽') : '');
            @endphp

            <form method="GET" action="{{ route('players.index') }}" class="mb-4 flex items-center gap-4">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    placeholder="Meklēt pēc vārda..."
                    class="border border-gray-300 p-2 rounded w-full max-w-sm"
                >
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
                    Meklēt
                </button>
            </form>

            <table class="w-full border border-gray-300 dark:border-gray-600 mt-4">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="p-2 text-left">Bilde</th>
                        <th class="p-2 text-left">
                            <a href="{{ route('players.index', ['sort' => 'name', 'direction' => $toggle('name')]) }}">
                                Vārds{!! $arrow('name') !!}
                            </a>
                        </th>
                        <th class="p-2 text-left">
                            <a href="{{ route('players.index', ['sort' => 'position', 'direction' => $toggle('position')]) }}">
                                Pozīcija{!! $arrow('position') !!}
                            </a>
                        </th>
                        <th class="p-2 text-left">
                            <a href="{{ route('players.index', ['sort' => 'height', 'direction' => $toggle('height')]) }}">
                                Augums{!! $arrow('height') !!}
                            </a>
                        </th>
                        <th class="p-2 text-left">
                            <a href="{{ route('players.index', ['sort' => 'team', 'direction' => $toggle('team')]) }}">
                                Komanda{!! $arrow('team') !!}
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
                    @foreach($players as $player)
                        <tr class="border-t border-gray-300 dark:border-gray-600">
                            <td class="p-2">
                                @if($player->image)
                                    <img src="{{ asset('storage/' . $player->image) }}" alt="Spēlētāja bilde" class="h-12 w-12 rounded object-cover">
                                @else
                                    <span class="text-gray-400 text-sm">Nav</span>
                                @endif
                            </td>
                            <td class="p-2">{{ $player->name }}</td>
                            <td class="p-2">{{ $player->position }}</td>
                            <td class="p-2">{{ $player->height }} m</td>
                            <td class="p-2">{{ $player->team }}</td>

                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <td class="p-2">
                                        <div class="flex gap-2">
                                            <a href="{{ route('players.edit', $player) }}" class="text-blue-600 hover:underline">Rediģēt</a>
                                            <form method="POST" action="{{ route('players.destroy', $player) }}">
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
