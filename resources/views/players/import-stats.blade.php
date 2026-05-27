<x-layouts.app>
<div class="max-w-2xl mx-auto py-10 px-4">

    <div class="mb-8">
        <a href="{{ route('players.index') }}" class="text-orange-500 hover:underline font-medium text-sm">← Atpakaļ</a>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-2">Importēt statistiku (CSV)</h1>
        <p class="text-gray-500 mt-1 text-sm">Augšupielādē CSV failu ar spēlētāju sezonas statistiku</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl px-5 py-4 text-green-800 font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
            @foreach($errors->all() as $error)
                <p class="text-red-700 text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('import_errors'))
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-2xl px-5 py-4">
            <div class="font-semibold text-yellow-800 mb-2 text-sm">Brīdinājumi (rindas ar kļūdām tika izlaistas):</div>
            @foreach(session('import_errors') as $err)
                <p class="text-yellow-700 text-xs">{{ $err }}</p>
            @endforeach
        </div>
    @endif

    {{-- Upload form --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-5">
            <h2 class="text-lg font-bold text-white uppercase tracking-wide">Augšupielādēt CSV</h2>
        </div>
        <div class="p-8">
            <form method="POST" action="{{ route('players.import-stats.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">CSV fails</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required
                           class="block w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:font-semibold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 cursor-pointer border border-gray-200 rounded-xl px-3 py-2">
                </div>
                <button type="submit"
                        class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
                    Importēt
                </button>
            </form>
        </div>
    </div>

    {{-- CSV format guide --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-8 py-5">
            <h2 class="text-lg font-bold text-white uppercase tracking-wide">CSV formāts</h2>
        </div>
        <div class="p-8 space-y-4">
            <p class="text-sm text-gray-600">Pirmā rinda ir virsrakstu rinda. Kolonnu secībai jābūt šādai:</p>

            <div class="overflow-x-auto">
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-100">
                            <th class="px-3 py-2 text-left font-semibold text-slate-700 border border-gray-200">Kolonna</th>
                            <th class="px-3 py-2 text-left font-semibold text-slate-700 border border-gray-200">Apraksts</th>
                            <th class="px-3 py-2 text-left font-semibold text-slate-700 border border-gray-200">Piemērs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach([
                            ['player_name', 'Spēlētāja vārds (precīzi kā sistēmā)', 'Jānis Bērziņš'],
                            ['season', 'Sezona', '2024/25'],
                            ['team_name', 'Komanda šajā sezonā', 'BK Rīga'],
                            ['games_played', 'Nospēlētas spēles', '32'],
                            ['points_per_game', 'Punkti/spēle', '18.5'],
                            ['rebounds_per_game', 'Atlēcieni/spēle', '7.2'],
                            ['assists_per_game', 'Piespēles/spēle', '4.1'],
                            ['steals_per_game', 'Pārtverti/spēle', '1.3'],
                            ['blocks_per_game', 'Bloki/spēle', '0.8'],
                            ['field_goal_pct', 'Metiens % (var būt tukšs)', '48.5'],
                            ['three_point_pct', 'Trīspunktu % (var būt tukšs)', '36.2'],
                            ['free_throw_pct', 'Soda metiens % (var būt tukšs)', '82.0'],
                        ] as [$col, $desc, $ex])
                        <tr>
                            <td class="px-3 py-2 border border-gray-200 font-mono text-orange-700 bg-orange-50/30">{{ $col }}</td>
                            <td class="px-3 py-2 border border-gray-200 text-slate-600">{{ $desc }}</td>
                            <td class="px-3 py-2 border border-gray-200 text-slate-500">{{ $ex }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-slate-50 rounded-xl p-4 border border-gray-200">
                <div class="text-xs font-semibold text-slate-600 mb-2">Piemērs (pirmās divas rindas):</div>
                <pre class="text-xs text-slate-700 overflow-x-auto">player_name,season,team_name,games_played,points_per_game,rebounds_per_game,assists_per_game,steals_per_game,blocks_per_game,field_goal_pct,three_point_pct,free_throw_pct
Jānis Bērziņš,2024/25,BK Rīga,32,18.5,7.2,4.1,1.3,0.8,48.5,36.2,82.0</pre>
            </div>

            <p class="text-xs text-gray-500">
                Ja spēlētājs ar norādīto vārdu nav atrasts sistēmā, rinda tiek izlaista.<br>
                Ja šim spēlētājam šī sezona jau pastāv, tā tiks <strong>atjaunināta</strong>.
            </p>
        </div>
    </div>
</div>
</x-layouts.app>
