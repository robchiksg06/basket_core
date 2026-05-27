<x-layouts.app>
<div class="max-w-3xl mx-auto py-10 px-4 space-y-6">

    {{-- Back --}}
    <a href="{{ route('forum.index') }}" class="text-orange-500 hover:underline font-medium text-sm inline-block">
        ← Atpakaļ uz forumu
    </a>

    {{-- Thread card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-8 py-6">
            <h1 class="text-2xl font-extrabold text-white leading-snug">{{ $thread->title }}</h1>
            <div class="flex items-center gap-3 mt-3 text-white/50 text-xs">
                @if($thread->user->avatarUrl())
                    <img src="{{ $thread->user->avatarUrl() }}" class="w-7 h-7 rounded-lg object-cover flex-shrink-0" alt="">
                @else
                    <span class="w-7 h-7 rounded-lg bg-white/10 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                        {{ strtoupper(mb_substr($thread->user->name, 0, 2)) }}
                    </span>
                @endif
                <span>{{ $thread->user->name }}</span>
                <span>·</span>
                <span>{{ $thread->created_at->diffForHumans() }}</span>
            </div>
        </div>
        <div class="px-8 py-6">
            <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $thread->body }}</p>
        </div>
    </div>

    {{-- Comments --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Komentāri</h2>
            <span class="text-sm text-gray-400">{{ $thread->posts->count() }}</span>
        </div>

        @if($thread->posts->isEmpty())
            <div class="px-8 py-12 text-center">
                <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <p class="text-gray-400 text-sm">Vēl nav neviena komentāra. Esi pirmais!</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($thread->posts as $post)
                    <div class="px-8 py-5 flex gap-4">
                        @if($post->user->avatarUrl())
                            <img src="{{ $post->user->avatarUrl() }}" class="w-9 h-9 rounded-xl object-cover flex-shrink-0" alt="">
                        @else
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center text-xs font-black flex-shrink-0">
                                {{ strtoupper(mb_substr($post->user->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-slate-800">{{ $post->user->name }}</span>
                                <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $post->body }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Reply form --}}
    @auth
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-4">
                <h2 class="text-base font-bold text-white">Raksti atbildi</h2>
            </div>
            <form method="POST" action="{{ route('forum.reply', $thread->id) }}" class="p-6 space-y-4">
                @csrf
                <div class="flex gap-4">
                    @if(auth()->user()->avatarUrl())
                        <img src="{{ auth()->user()->avatarUrl() }}" class="w-9 h-9 rounded-xl object-cover flex-shrink-0" alt="">
                    @else
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center text-xs font-black flex-shrink-0">
                            {{ strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <textarea name="body"
                              rows="3"
                              class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 text-slate-800 text-sm resize-none"
                              placeholder="Raksti savu atbildi..."
                              required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition">
                        Atbildēt
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm px-8 py-6 text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-orange-600 font-semibold hover:underline">Ieiet</a>, lai rakstītu komentāru.
        </div>
    @endauth

</div>
</x-layouts.app>
