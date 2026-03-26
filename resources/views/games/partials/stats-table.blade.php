<table class="w-full border">
    <thead class="bg-gray-50">
        <tr>
            <th class="border px-3 py-2 text-left">Nr.</th>
            <th class="border px-3 py-2 text-left">Spēlētājs</th>
            <th class="border px-3 py-2">PTS</th>
            <th class="border px-3 py-2">1PT</th>
            <th class="border px-3 py-2">2PT</th>
            <th class="border px-3 py-2">3PT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($players as $player)
            @php($stats = $player->statLine())
            <tr>
                <td class="border px-3 py-2">{{ $player->jersey_number }}</td>
                <td class="border px-3 py-2">{{ $player->player_name }}</td>
                <td class="border px-3 py-2 text-center font-bold">{{ $stats['points'] }}</td>
                <td class="border px-3 py-2 text-center">{{ $stats['ft_made'] }}/{{ $stats['ft_att'] }}</td>
                <td class="border px-3 py-2 text-center">{{ $stats['2pt_made'] }}/{{ $stats['2pt_att'] }}</td>
                <td class="border px-3 py-2 text-center">{{ $stats['3pt_made'] }}/{{ $stats['3pt_att'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>