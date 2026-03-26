<x-layouts.app>
    <div class="max-w-xl mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6 text-orange-600">Pievienot komandu</h2>

        <form method="POST" action="{{ route('teams.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-gray-700 font-semibold mb-1">Nosaukums:</label>
                <input type="text" id="name" name="name" required
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-orange-500">
            </div>

            <div>
                <label for="country" class="block text-gray-700 font-semibold mb-1">Valsts:</label>
                <input type="text" id="country" name="country"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-orange-500">
            </div>

            <div>
                <label for="league" class="block text-gray-700 font-semibold mb-1">Līga:</label>
                <select id="league" name="league"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-orange-500">
                    <option value="">-- Izvēlies līgu --</option>
                    <option value="EuroLeague">EuroLeague</option>
                    <option value="Latvijas LBL">Latvijas LBL</option>
                    <option value="NBA">NBA</option>
                    <option value="Serie A">Serie A</option>
                    <option value="ACB">ACB</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700">
                    Saglabāt
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
