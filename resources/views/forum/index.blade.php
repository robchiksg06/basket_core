<x-layouts.app>
<div class="max-w-4xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Forums</h1>
            <p class="text-gray-500 mt-1">Apspried basketbolu, komandas un aktualitātes</p>
        </div>
        @auth
            <a href="{{ route('forum.create') }}"
               class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-5 py-3 rounded-xl font-bold shadow-sm transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Jauna tēma
            </a>
        @endauth
    </div>

    {{-- Thread list --}}
    @if($threads->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-orange-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <p class="text-slate-700 font-semibold text-lg">Vēl nav nevienas tēmas</p>
            <p class="text-gray-400 text-sm mt-1">Esi pirmais, kurš uzsāk diskusiju!</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($threads as $thread)
                <a href="{{ route('forum.show', $thread->id) }}"
                   class="group flex items-start gap-4 bg-white hover:bg-orange-50/50 border border-gray-200 hover:border-orange-300 rounded-2xl px-6 py-5 shadow-sm transition duration-200 block">

                    {{-- Avatar --}}
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center text-sm font-black flex-shrink-0 shadow-sm">
                        {{ strtoupper(mb_substr($thread->user->name, 0, 2)) }}
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <h2 class="text-base font-bold text-slate-900 group-hover:text-orange-600 transition truncate">
                                {{ $thread->title }}
                            </h2>
                            <span class="text-xs text-gray-400 flex-shrink-0 mt-0.5">
                                {{ $thread->created_at->diffForHumans() }}
                            </span>
                        </div>

                        @if($thread->body)
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                {{ Str::limit($thread->body, 120) }}
                            </p>
                        @endif

                        <div class="flex items-center gap-4 mt-2.5 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $thread->user->name }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                {{ $thread->posts->count() }} {{ $thread->posts->count() === 1 ? 'atbilde' : 'atbildes' }}
                            </span>
                        </div>
                    </div>

                    {{-- Arrow --}}
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-orange-400 flex-shrink-0 mt-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif

</div>
</x-layouts.app>
