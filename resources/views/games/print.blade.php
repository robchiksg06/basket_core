<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Spēles protokols</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111; }
        h1, h2, h3 { margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #222; padding: 8px; font-size: 14px; }
        .header { margin-bottom: 24px; }
        .two-cols { display: flex; gap: 24px; }
        .col { flex: 1; }
        .print-btn { margin-bottom: 20px; }
        @media print {
            .print-btn { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Printēt</button>

    <div class="header">
        <h1>Spēles protokols</h1>
        <p><strong>Spēle:</strong> {{ $game->home_team_name }} - {{ $game->away_team_name }}</p>
        <p><strong>Nosaukums:</strong> {{ $game->title }}</p>
        <p><strong>Datums:</strong> {{ $game->game_date }}</p>
        <p><strong>Vieta:</strong> {{ $game->location }}</p>
        <p><strong>Rezultāts:</strong> {{ $game->home_score }} : {{ $game->away_score }}</p>
    </div>

    <h2>Ceturtdaļu rezultāts</h2>
    <table>
        <thead>
            <tr>
                <th>Komanda</th>
                <th>Q1</th>
                <th>Q2</th>
                <th>Q3</th>
                <th>Q4</th>
                <th>Kopā</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $game->home_team_name }}</td>
                <td>{{ $quarterScores[1]['home'] }}</td>
                <td>{{ $quarterScores[2]['home'] }}</td>
                <td>{{ $quarterScores[3]['home'] }}</td>
                <td>{{ $quarterScores[4]['home'] }}</td>
                <td><strong>{{ $game->home_score }}</strong></td>
            </tr>
            <tr>
                <td>{{ $game->away_team_name }}</td>
                <td>{{ $quarterScores[1]['away'] }}</td>
                <td>{{ $quarterScores[2]['away'] }}</td>
                <td>{{ $quarterScores[3]['away'] }}</td>
                <td>{{ $quarterScores[4]['away'] }}</td>
                <td><strong>{{ $game->away_score }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="two-cols">
        <div class="col">
            <h2>{{ $game->home_team_name }}</h2>
            @include('games.partials.print-stats-table', ['players' => $homePlayers])
        </div>

        <div class="col">
            <h2>{{ $game->away_team_name }}</h2>
            @include('games.partials.print-stats-table', ['players' => $awayPlayers])
        </div>
    </div>

    <h2>Notikumu saraksts</h2>
    <table>
        <thead>
            <tr>
                <th>Q</th>
                <th>Komanda</th>
                <th>Spēlētājs</th>
                <th>Metiens</th>
                <th>Statuss</th>
            </tr>
        </thead>
        <tbody>
            @foreach($game->events->sortBy('id') as $event)
                <tr>
                    <td>{{ $event->quarter }}</td>
                    <td>{{ $event->team_side === 'home' ? $game->home_team_name : $game->away_team_name }}</td>
                    <td>#{{ $event->player->jersey_number }} {{ $event->player->player_name }}</td>
                    <td>{{ strtoupper($event->shot_type) }}</td>
                    <td>{{ $event->is_made ? 'Sekmīgs' : 'Nesekmīgs' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>