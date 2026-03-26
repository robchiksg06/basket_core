<x-layouts.app>
    <div class="max-w-md mx-auto py-8">
        <h2 class="text-xl font-bold mb-4 text-orange-600">Rediģēt spēlētāju</h2>

        <form method="POST" action="{{ route('players.update', $player) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')

            <input name="name" value="{{ old('name', $player->name) }}" required class="w-full p-2 border rounded">
            <input name="position" value="{{ old('position', $player->position) }}" class="w-full p-2 border rounded">
            <input name="height" type="number" step="0.01" value="{{ old('height', $player->height) }}" class="w-full p-2 border rounded">
            <input name="team" value="{{ old('team', $player->team) }}" class="w-full p-2 border rounded">

            {{-- Priekšskatījums esošajai bildei --}}
            @if($player->image)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Esošā bilde:</p>
                    <img src="{{ asset('storage/' . $player->image) }}" alt="Spēlētāja bilde" class="h-24 w-24 object-cover rounded mb-2">
                </div>
            @endif

            <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded">

            <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
                Saglabāt izmaiņas
            </button>
        </form>
    </div>
</x-layouts.app>
