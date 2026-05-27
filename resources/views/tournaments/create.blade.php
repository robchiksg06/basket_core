<x-layouts.app>
<div class="max-w-2xl mx-auto py-10 px-4">

    <div class="mb-8">
        <a href="{{ route('tournaments.index') }}" class="text-orange-500 hover:underline font-medium text-sm">← Atpakaļ uz turnīriem</a>
        <h1 class="text-4xl font-extrabold text-slate-900 mt-2">Jauns turnīrs</h1>
        <p class="text-gray-500 mt-1">Ievadi nosaukumu, izvēlies formātu un komandas</p>
    </div>

    <form method="POST" action="{{ route('tournaments.store') }}" class="space-y-6">
        @csrf

        {{-- Info --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-5">
                <h2 class="text-lg font-bold text-white uppercase tracking-wide">Turnīra informācija</h2>
            </div>
            <div class="p-8 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nosaukums *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="piem. Skolas turnīrs 2026">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Apraksts</label>
                    <textarea name="description" rows="2"
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500"
                              placeholder="Neobligāts apraksts">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Format --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-8 py-5">
                <h2 class="text-lg font-bold text-white uppercase tracking-wide">Formāts</h2>
            </div>
            <div class="p-8 space-y-4">
                <label class="flex items-start gap-4 p-4 rounded-2xl border-2 cursor-pointer transition
                              has-[:checked]:border-orange-400 has-[:checked]:bg-orange-50 border-gray-200">
                    <input type="radio" name="format" value="single_elimination"
                           {{ old('format', 'single_elimination') === 'single_elimination' ? 'checked' : '' }}
                           class="mt-1 accent-orange-600" id="fmt-single">
                    <div>
                        <div class="font-bold text-slate-800">Single Elimination</div>
                        <div class="text-sm text-gray-500 mt-0.5">Zaudē → iziet. Vienkāršs atzarojums no sākuma līdz finālam.</div>
                    </div>
                </label>

                <label class="flex items-start gap-4 p-4 rounded-2xl border-2 cursor-pointer transition
                              has-[:checked]:border-orange-400 has-[:checked]:bg-orange-50 border-gray-200">
                    <input type="radio" name="format" value="group_knockout"
                           {{ old('format') === 'group_knockout' ? 'checked' : '' }}
                           class="mt-1 accent-orange-600" id="fmt-group">
                    <div>
                        <div class="font-bold text-slate-800">Grupas + Atzarojums</div>
                        <div class="text-sm text-gray-500 mt-0.5">Vispirms grupas posms (katrs pret katru), tad labākās komandas iet knockout.</div>
                    </div>
                </label>

                {{-- Group options (visible only for group_knockout) --}}
                <div id="group-options" class="hidden pl-4 space-y-4 pt-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Grupu skaits</label>
                            <select name="groups_count"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                                @foreach([2,3,4,6,8] as $n)
                                    <option value="{{ $n }}" {{ old('groups_count', 2) == $n ? 'selected' : '' }}>{{ $n }} grupas</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Turpina no katras grupas</label>
                            <select name="advance_per_group"
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                                @foreach([1,2,3,4] as $n)
                                    <option value="{{ $n }}" {{ old('advance_per_group', 2) == $n ? 'selected' : '' }}>{{ $n }} komanda{{ $n > 1 ? 's' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Teams --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-8 py-5 flex items-center justify-between">
                <h2 class="text-lg font-bold text-white uppercase tracking-wide">Komandas</h2>
                <div class="flex items-center gap-3">
                    <span class="text-white/60 text-sm">Skaits:</span>
                    <select id="team-count"
                            class="bg-white/10 text-white border border-white/20 rounded-lg px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-orange-400">
                        @foreach([2,3,4,5,6,7,8,10,12,16,20,24,32] as $n)
                            <option value="{{ $n }}" {{ $n == 8 ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-6">
                <div id="team-inputs" class="space-y-2.5">
                    @for($i = 0; $i < 32; $i++)
                        <div class="team-row flex items-center gap-3" data-index="{{ $i }}"
                             style="{{ $i >= 8 ? 'display:none' : '' }}">
                            <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                {{ $i + 1 }}
                            </div>
                            <input type="text" name="teams[{{ $i }}]"
                                   value="{{ old('teams.' . $i) }}"
                                   placeholder="Komandas nosaukums"
                                   class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"
                                   {{ $i < 8 ? 'required' : '' }}>
                        </div>
                    @endfor
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_public" value="1" class="w-5 h-5 rounded accent-orange-600">
                        <span class="text-sm font-medium text-slate-700">Rādīt šo turnīru publiski</span>
                    </label>
                </div>
                <p class="text-xs text-gray-400 mt-3">Komandu secība tiks izlozēta automātiski</p>
            </div>
        </div>

        <div class="flex items-center gap-4 pb-4">
            <button type="submit"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-4 rounded-xl font-bold text-base shadow-sm transition">
                🏆 Izveidot un sākt turnīru
            </button>
            <a href="{{ route('tournaments.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Atcelt</a>
        </div>
    </form>
</div>

<script>
    // Team count toggle
    const select = document.getElementById('team-count');
    const rows   = document.querySelectorAll('.team-row');
    function updateRows() {
        const count = parseInt(select.value);
        rows.forEach((row, i) => {
            const show = i < count;
            row.style.display = show ? 'flex' : 'none';
            row.querySelector('input').required = show;
        });
    }
    select.addEventListener('change', updateRows);
    updateRows();

    // Format toggle
    const radios      = document.querySelectorAll('input[name="format"]');
    const groupOpts   = document.getElementById('group-options');
    function updateFormat() {
        const isGroup = document.querySelector('input[name="format"]:checked')?.value === 'group_knockout';
        groupOpts.classList.toggle('hidden', !isGroup);
    }
    radios.forEach(r => r.addEventListener('change', updateFormat));
    updateFormat();
</script>
</x-layouts.app>
