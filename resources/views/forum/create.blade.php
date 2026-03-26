<x-layouts.app>
    <div class="max-w-3xl mx-auto py-8">
        <h2 class="text-2xl font-bold mb-6 text-orange-600">Izveidot jaunu tēmu</h2>

        <form method="POST" action="{{ route('forum.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-medium">Virsraksts</label>
                <input name="title" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium">Saturs</label>
                <textarea name="body" rows="5" class="w-full border p-2 rounded" required></textarea>
            </div>
            <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">Publicēt</button>
        </form>
    </div>
</x-layouts.app>
