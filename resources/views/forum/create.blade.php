<x-layouts.app>
<div class="max-w-2xl mx-auto py-10 px-4">

    <div class="mb-6">
        <a href="{{ route('forum.index') }}" class="text-orange-500 hover:underline font-medium text-sm">← Atpakaļ uz forumu</a>
        <h1 class="text-4xl font-extrabold text-slate-900 mt-2">Jauna tēma</h1>
        <p class="text-gray-500 mt-1">Uzsāc diskusiju basketbola kopienā</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-5">
            <h2 class="text-lg font-bold text-white tracking-wide uppercase">Tēmas informācija</h2>
        </div>

        <form method="POST" action="{{ route('forum.store') }}" class="p-8 space-y-5">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Virsraksts</label>
                <input name="title"
                       value="{{ old('title') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 text-slate-800"
                       placeholder="Par ko vēlies runāt?"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Saturs</label>
                <textarea name="body"
                          rows="6"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 text-slate-800 resize-none"
                          placeholder="Raksti savu domu šeit..."
                          required>{{ old('body') }}</textarea>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        class="bg-orange-600 hover:bg-orange-700 text-white px-7 py-3 rounded-xl font-bold shadow-sm transition">
                    Publicēt
                </button>
                <a href="{{ route('forum.index') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm">Atcelt</a>
            </div>
        </form>
    </div>

</div>
</x-layouts.app>
