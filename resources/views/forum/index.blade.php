<x-layouts.app>
    <div class="max-w-3xl mx-auto py-8">
        <h2 class="text-2xl font-bold mb-6 text-orange-600">Foruma tēmas</h2>

        <a href="{{ route('forum.create') }}" class="mb-4 inline-block bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
            ➕ Jauna tēma
        </a>

        @foreach($threads as $thread)
            <div class="border-b border-gray-300 py-4">
                <a href="{{ route('forum.show', $thread->id) }}" class="text-lg text-blue-600 hover:underline font-semibold">
                    {{ $thread->title }}
                </a>
                <p class="text-sm text-gray-600">Autors: {{ $thread->user->name }} | {{ $thread->created_at->diffForHumans() }}</p>
            </div>
        @endforeach
    </div>
</x-layouts.app>
