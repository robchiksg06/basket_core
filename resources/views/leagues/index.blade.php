<x-layouts.app>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h2 class="text-3xl font-bold mb-8 text-orange-600 text-center">Basketbola Līgas</h2>

        @if($leagues->isEmpty())
            <div class="text-center text-gray-600">
                Šobrīd nav pievienota neviena līga.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($leagues as $league)
                    <div class="bg-white rounded shadow-md p-4 flex flex-col items-center text-center hover:shadow-lg transition">
                        @if($league->logo)
                            <img
                                src="{{ asset('storage/' . $league->logo) }}"
                                alt="{{ $league->name }}"
                                class="h-24 mb-4 object-contain"
                            >
                        @else
                            <div class="h-24 mb-4 flex items-center justify-center text-gray-400">
                                Nav logo
                            </div>
                        @endif

                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $league->name }}
                        </h3>

                        <p class="text-sm text-gray-600 my-2">
                            {{ $league->description }}
                        </p>

                        <a href="{{ route('leagues.show', $league) }}"
                           class="mt-auto inline-block bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 transition">
                            Vairāk
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>