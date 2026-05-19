<table>
    <thead>
        <tr>
            <th class="center">Nr.</th>
            <th>Spēlētājs</th>
            <th class="center">PTS</th>
            <th class="center">1PT</th>
            <th class="center">2PT</th>
            <th class="center">3PT</th>
            <th class="center">REB</th>
            <th class="center">AST</th>
            <th class="center">STL</th>
            <th class="center">TOV</th>
        </tr>
    </thead>
    <tbody>
        @foreach($players as $player)
            @php($stats = $player->statLine())
            <tr>
                <td class="num">{{ $player->jersey_number }}</td>
                <td>{{ $player->player_name }}</td>
                <td class="pts">{{ $stats['points'] }}</td>
                <td class="stat">{{ $stats['ft_made'] }}/{{ $stats['ft_att'] }}</td>
                <td class="stat">{{ $stats['2pt_made'] }}/{{ $stats['2pt_att'] }}</td>
                <td class="stat">{{ $stats['3pt_made'] }}/{{ $stats['3pt_att'] }}</td>
                <td class="stat">{{ $stats['rebounds'] }}</td>
                <td class="stat">{{ $stats['assists'] }}</td>
                <td class="stat">{{ $stats['steals'] }}</td>
                <td class="stat">{{ $stats['turnovers'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
