<x-layouts.app>
    <div class="max-w-md mx-auto py-8">
        <h2 class="text-xl font-bold mb-4 text-orange-600">Pievienot spēlētāju</h2>
        <form method="POST" action="{{ route('players.store') }}" enctype="multipart/form-data">
        
            @csrf
            <input name="name" placeholder="Vārds" required class="w-full p-2 border rounded">
            <input name="position" placeholder="Pozīcija" class="w-full p-2 border rounded">
            <input name="height" type="number" step="0.01" placeholder="Augums (m)" class="w-full p-2 border rounded">
            <input name="team" placeholder="Komanda" class="w-full p-2 border rounded">
            <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded">
            <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">Saglabāt</button>
        </form>
    </div>
</x-layouts.app>
