<x-layouts.app>
    <div class="max-w-xl mx-auto py-8 px-4 text-center">
        <h2 class="text-2xl font-bold text-orange-600 mb-4">{{ $player->name }}</h2>

        @if($player->image)
            <img src="{{ asset('storage/' . $player->image) }}" alt="{{ $player->name }}" class="h-48 w-48 mx-auto rounded-full object-cover mb-4">
        @endif

        <p><strong>Pozīcija:</strong> {{ $player->position }}</p>
        <p><strong>Komanda:</strong> {{ $player->team }}</p>
        <p><strong>Augums:</strong> {{ $player->height }} m</p>

        <a href="{{ route('players.public') }}" class="inline-block mt-6 text-orange-600 hover:underline">← Atpakaļ</a>
    </div>
</x-layouts.app>
