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
            <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center text-gray-400">
                <div class="text-4xl mb-3">🏀</div>
                <p class="font-medium">Šajā līgā vēl nav pievienota neviena komanda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($teams as $team)
                    <a href="{{ route('teams.show', $team) }}?from_league={{ $league->id }}"
                       class="group bg-white rounded-2xl border-2 border-gray-100 shadow-sm hover:border-orange-300 hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col">

                        {{-- Header --}}
                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 px-6 py-5 flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center flex-shrink-0">
                                @if($team->logo)
                                    <img src="{{ asset('storage/' . $team->logo) }}"
                                         alt="{{ $team->name }}"
                                         class="w-12 h-12 object-contain drop-shadow">
                                @else
                                    <span class="text-xl font-black text-white">
                                        {{ strtoupper(mb_substr($team->name, 0, 2)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg font-extrabold text-white leading-tight truncate group-hover:text-orange-300 transition">
                                    {{ $team->name }}
                                </h3>
                                @if($team->country)
                                    <p class="text-white/50 text-xs mt-0.5">{{ $team->country }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <span class="text-xl font-extrabold text-orange-600">{{ $team->players->count() }}</span>
                                <span class="text-gray-400">spēlētāj{{ $team->players->count() === 1 ? 's' : 'i' }}</span>
                            </div>
                            <span class="text-xs text-orange-500 font-semibold group-hover:underline">
                                Skatīt sastāvu →
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>