<x-layouts.app>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h2 class="text-3xl font-bold mb-8 text-orange-600 text-center">Basketbola Līgas</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @php
                $leagues = [
                    [
                        'name' => 'EuroLeague',
                        'title' => 'Eiropas Basketbola Līga',
                        'image' => 'images/euro.png',
                        'description' => 'Eiropas basketbola līga ir vadošais turnīrs, kurā sacenšas labākās komandas no dažādām valstīm.',
                    ],
                    [
                        'name' => 'Latvijas LBL',
                        'title' => 'Latvijas Basketbola Līga',
                        'image' => 'images/lbl.png',
                        'description' => 'Latvijas Basketbola līga ir galvenais basketbola turnīrs Latvijā ar labākajām komandām.',
                    ],
                    [
                        'name' => 'NBA',
                        'title' => 'Nacionālā Basketbola Līga',
                        'image' => 'images/nba.png',
                        'description' => 'NBA ir prestižākais basketbola turnīrs pasaulē ar augstākā līmeņa spēlētājiem.',
                    ],
                    [
                        'name' => 'Serie A',
                        'title' => 'Itālijas Serie A',
                        'image' => 'images/seriea.png',
                        'description' => 'Itālijas augstākā basketbola līga, kurā sacenšas valsts vadošās komandas.',
                    ],
                    [
                        'name' => 'ACB',
                        'title' => 'Spānijas ACB līga',
                        'image' => 'images/acb.png',
                        'description' => 'ACB ir Spānijas augstākā profesionālā basketbola līga ar pasaulē atzītiem klubiem.',
                    ],
                ];
            @endphp

            @foreach($leagues as $league)
                <div class="bg-white rounded shadow-md p-4 flex flex-col items-center text-center hover:shadow-lg transition">
                    <img src="{{ asset($league['image']) }}" alt="{{ $league['title'] }}" class="h-24 mb-4 object-contain">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $league['title'] }}</h3>
                    <p class="text-sm text-gray-600 my-2">{{ $league['description'] }}</p>
                    <a href="{{ route('leagues.show', $league['name']) }}"
                       class="mt-auto inline-block bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 transition">
                        Vairāk
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
