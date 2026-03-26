<x-layouts.app>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <h2 class="text-2xl font-bold text-orange-600 mb-6">Mani iecienītie spēlētāji</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($players as $player)
                <div class="bg-white p-4 rounded shadow text-center">
                    @if($player->image)
                        <img src="{{ asset('storage/' . $player->image) }}" class="h-24 w-24 mx-auto rounded-full mb-2" />
                    @endif
                    <h3 class="font-semibold">{{ $player->name }}</h3>
                    <p class="text-sm">{{ $player->team }}</p>
                </div>
            @empty
                <p class="text-gray-500">Nav iecienītu spēlētāju vēl.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>
