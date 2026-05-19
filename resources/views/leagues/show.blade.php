<x-layouts.app>
    <div class="max-w-6xl mx-auto py-10 px-4">
        <div class="bg-white rounded-2xl shadow-md p-8 mb-10 border border-gray-200">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                @if($league->logo)
                    <img
                        src="{{ asset('storage/' . $league->logo) }}"
                        alt="{{ $league->name }}"
                        class="w-28 h-28 object-contain rounded-xl bg-gray-50 p-3 border"
                    >
                @endif

                <div class="text-center md:text-left">
                    <h1 class="text-4xl font-extrabold text-orange-600 mb-2">
                        {{ $league->name }}
                    </h1>

                    @if($league->description)
                        <p class="text-gray-600 text-lg max-w-2xl">
                            {{ $league->description }}
                        </p>
                    @endif

                    <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-orange-100 text-orange-700 font-semibold text-sm">
                        Komandas līgā: {{ $teams->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Komandas</h2>

            <a href="{{ route('leagues.index') }}" class="text-orange-600 font-semibold hover:underline">
                ← Atpakaļ uz līgām
            </a>
        </div>

        @if ($teams->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
                Šajā līgā vēl nav pievienota neviena komanda.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($teams as $team)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition duration-300 border border-gray-200 p-6">
                        <div class="flex flex-col items-center text-center">
                            @if($team->logo)
                                <img
                                    src="{{ asset('storage/' . $team->logo) }}"
                                    alt="{{ $team->name }}"
                                    class="w-24 h-24 object-contain mb-4"
                                >
                            @else
                                <div class="w-24 h-24 mb-4 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                    Nav logo
                                </div>
                            @endif

                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                {{ $team->name }}
                            </h3>

                            <p class="text-gray-500">
                                {{ $team->country ?? 'Nav norādīta valsts' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>