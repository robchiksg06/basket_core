<x-layouts.app>
    {{-- Ievads --}}
    <div class="flex flex-wrap gap-6 mb-10">
        <div class="flex-1 min-w-[280px]">
            <h2 class="text-3xl font-bold text-orange-600 mb-4">BasketCore</h2>
            <p class="mb-4">
                Laipni lūgti basketbola entuziastu platformā! Šeit varat apskatīt spēlētājus, komandas, līgas un iecienīt savus favorītus.
            </p>
            <img src="{{ asset('images/basket.jpeg') }}" alt="Basketball image"
                class="rounded-xl shadow-md max-w-full h-auto">
        </div>

        <aside class="flex-1 min-w-[250px] bg-gray-100 dark:bg-gray-700 p-4 rounded-xl">
            <h3 class="text-xl font-semibold mb-2">Raksti</h3>
            <ul class="list-disc list-inside space-y-1">
                <li><a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Labākie spēlētāji 2024</a></li>
                <li><a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">NBA sezona pārskatā</a></li>
                <li><a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Latvijas basketbola zvaigznes</a></li>
            </ul>
        </aside>
    </div>

    {{-- Tikai ja ielogots --}}
    @if(Auth::check() && Auth::user()->likedPlayers)
    <div class="max-w-6xl mx-auto py-8 px-4">
        <h2 class="text-2xl font-bold text-orange-600 mb-6 text-center">Mani iecienītie spēlētāji</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse(Auth::user()->likedPlayers as $player)

                    <div class="bg-white p-4 rounded shadow text-center">
                        @if($player->image)
                            <img src="{{ asset('storage/' . $player->image) }}" class="h-24 w-24 mx-auto rounded-full mb-2" />
                        @else
                            <div class="h-24 w-24 bg-gray-200 mx-auto rounded-full flex items-center justify-center text-gray-500 text-sm mb-2">
                                Nav bilde
                            </div>
                        @endif

                        <h3 class="font-semibold">{{ $player->name }}</h3>
                        <p class="text-sm">{{ $player->team }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Nav iecienītu spēlētāju vēl.</p>
                @endforelse
            </div>
        </div>
    @endauth
</x-layouts.app>
