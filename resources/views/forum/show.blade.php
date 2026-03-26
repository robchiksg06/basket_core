<x-layouts.app>
    <div class="max-w-3xl mx-auto py-8">
        <a href="{{ route('forum.index') }}" class="inline-block mb-4 px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">
            ← Atpakaļ uz forumu
        </a>        
        <h2 class="text-2xl font-bold text-orange-600 mb-4">{{ $thread->title }}</h2>
        <p class="mb-4 text-gray-800">{{ $thread->body }}</p>
        <p class="text-sm text-gray-500 mb-6">Autors: {{ $thread->user->name }} | {{ $thread->created_at->diffForHumans() }}</p>

        <hr class="my-6">

        <h3 class="text-lg font-semibold mb-4">Komentāri:</h3>

        @forelse($thread->posts as $post)
            <div class="mb-4 border-l-4 border-orange-500 pl-4">
                <p class="text-gray-800">{{ $post->body }}</p>
                <p class="text-sm text-gray-500">— {{ $post->user->name }}, {{ $post->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-gray-500">Nav komentāru vēl.</p>
        @endforelse

        <hr class="my-6">

        <form method="POST" action="{{ route('forum.reply', $thread->id) }}">
            @csrf
            <div class="mb-4">
                <label class="block font-medium">Atbilde</label>
                <textarea name="body" rows="4" class="w-full border p-2 rounded" required></textarea>
            </div>
            <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">Atbildēt</button>
        </form>
    </div>
</x-layouts.app>
