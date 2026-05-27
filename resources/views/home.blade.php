<x-layouts.app>

{{-- Hero --}}
<div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-16 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <div class="text-6xl mb-4">🏀</div>
        <h1 class="text-5xl font-extrabold tracking-tight mb-4">BasketCore</h1>
        <p class="text-white/60 text-lg max-w-xl mx-auto">
            Basketbola entuziastu platforma — spēlētāji, komandas, turnīri un vietējo spēļu protokoli vienā vietā.
        </p>
        @guest
            <div class="flex items-center justify-center gap-4 mt-8">
                <a href="{{ route('register') }}"
                   class="bg-orange-600 hover:bg-orange-700 text-white px-7 py-3 rounded-xl font-bold text-sm transition shadow-lg">
                    Sākt tagad
                </a>
                <a href="{{ route('login') }}"
                   class="border border-white/20 hover:bg-white/10 text-white px-7 py-3 rounded-xl font-bold text-sm transition">
                    Ieiet
                </a>
            </div>
        @endguest
        @auth
            <div class="flex items-center justify-center gap-4 mt-8 flex-wrap">
                <a href="{{ route('games.create') }}"
                   class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition shadow-lg">
                    + Jauna spēle
                </a>
                <a href="{{ route('tournaments.create') }}"
                   class="border border-white/20 hover:bg-white/10 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
                    + Jauns turnīrs
                </a>
            </div>
        @endauth
    </div>
</div>

{{-- Two paths --}}
<div class="max-w-4xl mx-auto px-4 py-12 grid grid-cols-1 sm:grid-cols-2 gap-6">

    {{-- Profesionālais --}}
    <div class="bg-white rounded-3xl border-2 border-gray-100 shadow-sm overflow-hidden hover:border-slate-300 hover:shadow-md transition group">
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 px-8 py-6">
            <div class="text-4xl mb-2">🏆</div>
            <h2 class="text-2xl font-extrabold text-white">Profesionālais basketbols</h2>
            <p class="text-white/50 text-sm mt-1">Spēlētāji, komandas un līgas</p>
        </div>
        <div class="p-6 space-y-2">
            <a href="{{ route('players.public') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-orange-50 transition text-slate-700 font-medium text-sm">
                <span class="text-lg">👤</span> Spēlētāji un statistika
            </a>
            <a href="{{ route('teams.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-orange-50 transition text-slate-700 font-medium text-sm">
                <span class="text-lg">👕</span> Profesionālās komandas
            </a>
            <a href="{{ route('leagues.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-orange-50 transition text-slate-700 font-medium text-sm">
                <span class="text-lg">🏅</span> Līgas
            </a>
            <a href="{{ route('players.compare') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-orange-50 transition text-slate-700 font-medium text-sm">
                <span class="text-lg">⚖️</span> Salīdzināt spēlētājus
            </a>
        </div>
    </div>

    {{-- Manas spēles --}}
    <div class="bg-white rounded-3xl border-2 border-gray-100 shadow-sm overflow-hidden hover:border-orange-300 hover:shadow-md transition group">
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 px-8 py-6">
            <div class="text-4xl mb-2">🏀</div>
            <h2 class="text-2xl font-extrabold text-white">Manas spēles</h2>
            <p class="text-white/70 text-sm mt-1">Protokoli un turnīri ar draugiem</p>
        </div>
        <div class="p-6 space-y-2">
            @auth
                <a href="{{ route('games.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-orange-50 transition text-slate-700 font-medium text-sm">
                    <span class="text-lg">📋</span> Manas spēles
                </a>
                <a href="{{ route('games.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-orange-50 transition text-orange-600 font-bold text-sm">
                    <span class="text-lg">+</span> Jauna spēle
                </a>
                <a href="{{ route('tournaments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-orange-50 transition text-slate-700 font-medium text-sm">
                    <span class="text-lg">🥇</span> Mani turnīri
                </a>
                <a href="{{ route('tournaments.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-orange-50 transition text-orange-600 font-bold text-sm">
                    <span class="text-lg">+</span> Jauns turnīrs
                </a>
            @else
                <p class="text-slate-500 text-sm px-4 py-2">Piesakies, lai izveidotu spēles un turnīrus.</p>
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 mx-4 mt-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition">
                    Ieiet
                </a>
            @endauth
        </div>
    </div>

</div>

{{-- Liked players --}}
@auth
    @php $likedPlayers = Auth::user()->likedPlayers; @endphp
    @if($likedPlayers->isNotEmpty())
        <div class="max-w-5xl mx-auto py-12 px-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-extrabold text-slate-900">❤️ Iecienītie spēlētāji</h2>
                <a href="{{ route('players.public') }}" class="text-sm text-orange-500 hover:underline font-medium">
                    Visi spēlētāji →
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($likedPlayers as $player)
                    <a href="{{ route('players.public.show', $player) }}"
                       class="group bg-white rounded-2xl border-2 border-gray-100 shadow-sm overflow-hidden hover:border-orange-300 hover:shadow-md transition flex flex-col">

                        {{-- Dark header --}}
                        <div class="relative h-20 bg-gradient-to-br from-slate-800 to-slate-900">
                            @if($player->position)
                                <span class="absolute top-2 left-2 text-xs font-black text-white/70 bg-white/10 border border-white/20 px-2 py-0.5 rounded-full tracking-wide">
                                    {{ $player->position }}
                                </span>
                            @endif
                        </div>

                        {{-- Photo --}}
                        <div class="flex justify-center -mt-9 relative z-10">
                            @if($player->image)
                                <img src="{{ asset('storage/' . $player->image) }}"
                                     alt="{{ $player->name }}"
                                     class="w-[72px] h-[72px] rounded-full border-4 border-white dark:border-slate-700 shadow-md object-cover object-top group-hover:scale-105 transition">
                            @else
                                <div class="w-[72px] h-[72px] rounded-full border-4 border-white dark:border-slate-700 shadow-md bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center font-black text-white text-xl group-hover:scale-105 transition">
                                    {{ strtoupper(mb_substr($player->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="pt-2 pb-4 px-3 text-center flex-1 flex flex-col">
                            <h3 class="font-extrabold text-slate-900 text-sm leading-tight group-hover:text-orange-600 transition">
                                {{ $player->name }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $player->team ?: '—' }}</p>
                            @if($player->height)
                                <span class="mt-2 text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full inline-block mx-auto">
                                    {{ $player->height }} cm
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        {{-- Nav neviena iecienīta --}}
        <div class="max-w-5xl mx-auto py-12 px-4 text-center">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-10">
                <div class="text-4xl mb-3">🤍</div>
                <p class="font-semibold text-slate-600">Vēl neesi iecienījis nevienu spēlētāju</p>
                <p class="text-sm text-gray-400 mt-1 mb-5">Dodies uz spēlētāju sarakstu un nospied ❤️</p>
                <a href="{{ route('players.public') }}"
                   class="inline-block bg-orange-600 hover:bg-orange-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition">
                    Apskatīt spēlētājus
                </a>
            </div>
        </div>
    @endif
@endauth

@guest
    <div class="max-w-3xl mx-auto py-16 px-4 text-center">
        <p class="text-gray-400 mb-4">Pieregistrējies, lai sekotu saviem iecienītajiem spēlētājiem un izveidotu spēles.</p>
        <a href="{{ route('register') }}" class="inline-block bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
            Reģistrēties bez maksas
        </a>
    </div>
@endguest

</x-layouts.app>
