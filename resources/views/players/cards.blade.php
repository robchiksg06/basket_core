<x-layouts.app>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <h2 class="text-3xl font-bold mb-8 text-orange-600 text-center">Spēlētāji</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($players as $player)
                <div class="bg-white rounded shadow hover:shadow-lg transition p-4 text-center flex flex-col items-center">
                    {{-- Bilde --}}
                    @if($player->image)
                        <img src="{{ asset('storage/' . $player->image) }}" alt="{{ $player->name }}" class="h-32 w-32 rounded-full object-cover mb-4">
                    @else
                        <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center text-sm text-gray-500 mb-4">
                            Nav bilde
                        </div>
                    @endif

                    {{-- Info --}}
                    <h3 class="text-lg font-semibold text-gray-800">{{ $player->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $player->position }}</p>
                    <p class="text-sm text-gray-500">{{ $player->team }}</p>
                    <p class="text-sm text-gray-500 mb-2">{{ $player->height }} m</p>

                    {{-- Vairāk poga --}}
                    <a href="{{ route('players.show', $player->id) }}" class="mt-auto bg-orange-600 text-white px-4 py-1 rounded hover:bg-orange-700 transition">
                        Vairāk
                    </a>
                    @auth
                    <form method="POST" action="{{ route('players.like', $player) }}">
                        @csrf
                        <button type="submit" class="mt-2 text-sm text-pink-600 hover:underline">
                            {{ auth()->user()->likedPlayers->contains($player) ? '❤️ Atteikt Like' : '🤍 Like' }}
                        </button>
                    </form>
                    @endauth
                
                    
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
