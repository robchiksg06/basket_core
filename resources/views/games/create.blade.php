@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Jauna spēle</h1>

    <form action="{{ route('games.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="bg-white rounded shadow p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Nosaukums / turnīrs</label>
                <input type="text" name="title" class="w-full border rounded px-3 py-2" value="{{ old('title') }}">
            </div>

            <div>
                <label class="block font-semibold mb-1">Datums</label>
                <input type="date" name="game_date" class="w-full border rounded px-3 py-2" value="{{ old('game_date') }}">
            </div>

            <div>
                <label class="block font-semibold mb-1">Mājinieku komanda</label>
                <input type="text" name="home_team_name" required class="w-full border rounded px-3 py-2" value="{{ old('home_team_name') }}">
            </div>

            <div>
                <label class="block font-semibold mb-1">Viesu komanda</label>
                <input type="text" name="away_team_name" required class="w-full border rounded px-3 py-2" value="{{ old('away_team_name') }}">
            </div>

            <div class="md:col-span-2">
                <label class="block font-semibold mb-1">Vieta</label>
                <input type="text" name="location" class="w-full border rounded px-3 py-2" value="{{ old('location') }}">
            </div>
            <div class="md:col-span-2">
    <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_public" value="1">
        <span>Rādīt šo spēli publiski</span>
    </label>
</div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded shadow p-6">
                <h2 class="text-xl font-bold mb-4">Mājinieku sastāvs</h2>

                @for($i = 0; $i < 12; $i++)
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <input type="text" name="home_players[{{ $i }}][jersey_number]" placeholder="Nr." class="border rounded px-3 py-2">
                        <input type="text" name="home_players[{{ $i }}][player_name]" placeholder="Spēlētāja vārds" class="border rounded px-3 py-2 col-span-2">
                    </div>
                @endfor
            </div>

            <div class="bg-white rounded shadow p-6">
                <h2 class="text-xl font-bold mb-4">Viesu sastāvs</h2>

                @for($i = 0; $i < 12; $i++)
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <input type="text" name="away_players[{{ $i }}][jersey_number]" placeholder="Nr." class="border rounded px-3 py-2">
                        <input type="text" name="away_players[{{ $i }}][player_name]" placeholder="Spēlētāja vārds" class="border rounded px-3 py-2 col-span-2">
                    </div>
                @endfor
            </div>
        </div>

        <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded font-semibold">
            Izveidot spēli
        </button>
    </form>
@endsection