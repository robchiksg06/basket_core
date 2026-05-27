<table class="w-full text-sm">
    <thead>
        <tr class="bg-gray-50 text-xs text-slate-400 uppercase tracking-wider">
            <th class="text-center py-2.5 px-2 font-semibold w-8">Nr.</th>
            <th class="text-left py-2.5 px-3 font-semibold">Spēlētājs</th>
            <th class="text-center py-2.5 px-2 font-semibold text-slate-600">PTS</th>
            <th class="text-center py-2.5 px-2 font-semibold">1PT</th>
            <th class="text-center py-2.5 px-2 font-semibold">2PT</th>
            <th class="text-center py-2.5 px-2 font-semibold">3PT</th>
            <th class="text-center py-2.5 px-2 font-semibold text-slate-600">REB</th>
            <th class="text-center py-2.5 px-2 font-semibold">OR</th>
            <th class="text-center py-2.5 px-2 font-semibold">DR</th>
            <th class="text-center py-2.5 px-2 font-semibold">AST</th>
            <th class="text-center py-2.5 px-2 font-semibold">STL</th>
            <th class="text-center py-2.5 px-2 font-semibold">TOV</th>
            <th class="text-center py-2.5 px-2 font-semibold text-yellow-600">FLS</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
        @foreach($players as $player)
            @php($stats = $player->statLine())
            <tr class="hover:bg-gray-50 transition">
                <td class="py-2.5 px-2 text-center font-mono text-xs text-gray-400">{{ $player->jersey_number }}</td>
                <td class="py-2.5 px-3 font-medium text-slate-800">{{ $player->player_name }}</td>
                <td class="py-2.5 px-2 text-center font-black text-slate-900">{{ $stats['points'] }}</td>
                <td class="py-2.5 px-2 text-center text-gray-500 text-xs">{{ $stats['ft_made'] }}/{{ $stats['ft_att'] }}</td>
                <td class="py-2.5 px-2 text-center text-gray-500 text-xs">{{ $stats['2pt_made'] }}/{{ $stats['2pt_att'] }}</td>
                <td class="py-2.5 px-2 text-center text-gray-500 text-xs">{{ $stats['3pt_made'] }}/{{ $stats['3pt_att'] }}</td>
                <td class="py-2.5 px-2 text-center font-bold text-slate-700">{{ $stats['rebounds'] }}</td>
                <td class="py-2.5 px-2 text-center text-gray-400 text-xs">{{ $stats['off_rebounds'] }}</td>
                <td class="py-2.5 px-2 text-center text-gray-400 text-xs">{{ $stats['def_rebounds'] }}</td>
                <td class="py-2.5 px-2 text-center text-gray-600">{{ $stats['assists'] }}</td>
                <td class="py-2.5 px-2 text-center text-gray-600">{{ $stats['steals'] }}</td>
                <td class="py-2.5 px-2 text-center text-gray-600">{{ $stats['turnovers'] }}</td>
                <td class="py-2.5 px-2 text-center font-semibold {{ $stats['fouls'] >= 5 ? 'text-red-600' : ($stats['fouls'] >= 3 ? 'text-yellow-600' : 'text-gray-600') }}">
                    {{ $stats['fouls'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
