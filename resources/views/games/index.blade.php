@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Spēles</h1>

        @auth
            <a href="{{ route('games.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                Izveidot spēli
            </a>
        @endauth
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-3">Spēle</th>
                    <th class="text-left px-4 py-3">Datums</th>
                    <th class="text-left px-4 py-3">Statuss</th>
                    <th class="text-left px-4 py-3">Redzamība</th>
                    <th class="text-left px-4 py-3">Rezultāts</th>
                    <th class="text-left px-4 py-3">Darbības</th>
                </tr>
            </thead>
            <tbody>
                @forelse($games as $game)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            {{ $game->home_team_name }} - {{ $game->away_team_name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $game->game_date }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $game->status }}
                        </td>

                        <td class="px-4 py-3">
                            @if($game->is_public)
                                <span class="inline-block bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                                    Publiska
                                </span>
                            @else
                                <span class="inline-block bg-gray-200 text-gray-800 text-sm px-3 py-1 rounded-full">
                                    Privāta
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $game->home_score }} : {{ $game->away_score }}
                        </td>

                        <td class="px-4 py-3">
<div class="flex gap-3">
    <a href="{{ route('games.print', $game) }}" target="_blank" class="bg-gray-800 text-white px-4 py-2 rounded">
        Print protokols
    </a>

   <div class="flex items-center gap-3">
    <a href="{{ route('games.show', $game) }}" class="text-blue-600 font-semibold">
        Atvērt
    </a>

    @auth
        @if(auth()->id() === $game->user_id || (auth()->user()->role ?? null) === 'admin')
            <form action="{{ route('games.visibility', $game) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-yellow-600 font-semibold">
                    {{ $game->is_public ? 'Padarīt privātu' : 'Padarīt publisku' }}
                </button>
            </form>

            <form action="{{ route('games.destroy', $game) }}" method="POST"
                  onsubmit="return confirm('Vai tiešām dzēst šo spēli?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 font-semibold">
                    Dzēst
                </button>
            </form>
        @endif
    @endauth
</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Vēl nav nevienas spēles.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection