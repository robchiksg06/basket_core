<table>
    <thead>
        <tr>
            <th>Nr.</th>
            <th>Spēlētājs</th>
            <th>PTS</th>
            <th>1PT</th>
            <th>2PT</th>
            <th>3PT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($players as $player)
            @php($stats = $player->statLine())
            <tr>
                <td>{{ $player->jersey_number }}</td>
                <td>{{ $player->player_name }}</td>
                <td>{{ $stats['points'] }}</td>
                <td>{{ $stats['ft_made'] }}/{{ $stats['ft_att'] }}</td>
                <td>{{ $stats['2pt_made'] }}/{{ $stats['2pt_att'] }}</td>
                <td>{{ $stats['3pt_made'] }}/{{ $stats['3pt_att'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>