<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Spēles protokols — {{ $game->home_team_name }} vs {{ $game->away_team_name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            color: #111;
            background: #fff;
            padding: 24px 32px;
        }

        /* ── Print button ── */
        .no-print {
            text-align: right;
            margin-bottom: 20px;
        }
        .print-btn {
            background: #ea580c;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.3px;
        }
        .print-btn:hover { background: #c2410c; }

        @media print {
            .no-print { display: none; }
            body { padding: 10px 16px; }
        }

        /* ── Header ── */
        .header {
            border: 2px solid #111;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .header-top {
            background: #111;
            color: #fff;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-top .brand {
            font-size: 15px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #fb923c;
        }
        .header-top .doc-title {
            font-size: 11px;
            color: #9ca3af;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .header-meta {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0;
        }
        .header-meta .meta-item {
            padding: 8px 16px;
            border-right: 1px solid #e5e7eb;
        }
        .header-meta .meta-item:last-child { border-right: none; }
        .meta-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            font-weight: 600;
        }
        .meta-value {
            font-size: 12px;
            font-weight: 700;
            color: #111;
            margin-top: 2px;
        }

        /* ── Scoreboard ── */
        .scoreboard {
            border: 2px solid #111;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
            display: flex;
        }
        .score-team {
            flex: 1;
            padding: 16px 20px;
            text-align: center;
        }
        .score-team.home { background: #f8fafc; border-right: 2px solid #111; }
        .score-team.away { background: #fff7ed; }
        .score-team-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .score-team-name {
            font-size: 16px;
            font-weight: 900;
            color: #111;
            margin-bottom: 6px;
        }
        .score-pts {
            font-size: 42px;
            font-weight: 900;
            line-height: 1;
            color: #111;
        }
        .score-team.away .score-pts { color: #ea580c; }
        .score-divider {
            width: 2px;
            background: #111;
        }
        .score-center {
            width: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #111;
        }
        .score-vs {
            font-size: 20px;
            font-weight: 900;
            color: #fff;
        }

        /* ── Section title ── */
        .section-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #6b7280;
            margin-bottom: 6px;
            margin-top: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* ── Tables ── */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
            font-size: 10.5px;
        }
        thead tr {
            background: #111;
            color: #fff;
        }
        thead th {
            padding: 6px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        thead th.center { text-align: center; }
        tbody tr { border-top: 1px solid #f3f4f6; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody td {
            padding: 5px 8px;
            color: #111;
        }
        tbody td.center { text-align: center; }
        tbody td.pts {
            font-weight: 800;
            font-size: 12px;
            text-align: center;
        }
        tbody td.num {
            font-family: monospace;
            color: #6b7280;
            text-align: center;
            font-size: 10px;
        }
        tbody td.stat { text-align: center; color: #374151; }
        tbody td.total {
            font-weight: 900;
            font-size: 13px;
            text-align: center;
        }

        /* ── Quarter table ── */
        .quarter-table th, .quarter-table td {
            text-align: center;
        }
        .quarter-table td:first-child,
        .quarter-table th:first-child {
            text-align: left;
        }

        /* ── Two columns ── */
        .two-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .team-block-title {
            font-size: 13px;
            font-weight: 900;
            color: #111;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 2px solid #ea580c;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .team-block-title .team-badge {
            font-size: 9px;
            background: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ── Events ── */
        .event-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-shot     { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
        .badge-rebound  { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-assist   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-steal    { background: #faf5ff; color: #7c3aed; border: 1px solid #ddd6fe; }
        .badge-turnover { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .made   { color: #15803d; font-weight: 700; }
        .missed { color: #b91c1c; font-weight: 700; }

        /* ── Footer ── */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            color: #9ca3af;
            font-size: 9px;
        }
        .signature-line {
            display: flex;
            gap: 40px;
        }
        .signature-item {
            text-align: center;
        }
        .signature-item .line {
            width: 120px;
            border-bottom: 1px solid #9ca3af;
            margin-bottom: 3px;
            height: 20px;
        }
    </style>
</head>
<body>

    {{-- Print button --}}
    <div class="no-print">
        <button class="print-btn" onclick="window.print()">🖨 Drukāt protokolu</button>
    </div>

    {{-- Header info --}}
    <div class="header">
        <div class="header-top">
            <span class="brand">🏀 BasketCore</span>
            <span class="doc-title">Spēles protokols</span>
        </div>
        <div class="header-meta">
            <div class="meta-item">
                <div class="meta-label">Turnīrs / Nosaukums</div>
                <div class="meta-value">{{ $game->title ?: '—' }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Datums</div>
                <div class="meta-value">{{ $game->game_date ?: '—' }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Vieta</div>
                <div class="meta-value">{{ $game->location ?: '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Scoreboard --}}
    <div class="scoreboard">
        <div class="score-team home">
            <div class="score-team-label">Mājinieki</div>
            <div class="score-team-name">{{ $game->home_team_name }}</div>
            <div class="score-pts">{{ $game->home_score }}</div>
        </div>
        <div class="score-center">
            <span class="score-vs">:</span>
        </div>
        <div class="score-team away">
            <div class="score-team-label">Viesi</div>
            <div class="score-team-name">{{ $game->away_team_name }}</div>
            <div class="score-pts">{{ $game->away_score }}</div>
        </div>
    </div>

    {{-- Quarter scores --}}
    <div class="section-title">Ceturtdaļu rezultāts</div>
    <table class="quarter-table">
        <thead>
            <tr>
                <th>Komanda</th>
                <th class="center">1. ceturtdaļa</th>
                <th class="center">2. ceturtdaļa</th>
                <th class="center">3. ceturtdaļa</th>
                <th class="center">4. ceturtdaļa</th>
                <th class="center">Kopā</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight:700">{{ $game->home_team_name }}</td>
                <td class="center">{{ $quarterScores[1]['home'] }}</td>
                <td class="center">{{ $quarterScores[2]['home'] }}</td>
                <td class="center">{{ $quarterScores[3]['home'] }}</td>
                <td class="center">{{ $quarterScores[4]['home'] }}</td>
                <td class="total">{{ $game->home_score }}</td>
            </tr>
            <tr>
                <td style="font-weight:700; color:#ea580c">{{ $game->away_team_name }}</td>
                <td class="center">{{ $quarterScores[1]['away'] }}</td>
                <td class="center">{{ $quarterScores[2]['away'] }}</td>
                <td class="center">{{ $quarterScores[3]['away'] }}</td>
                <td class="center">{{ $quarterScores[4]['away'] }}</td>
                <td class="total" style="color:#ea580c">{{ $game->away_score }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Player stats --}}
    <div class="section-title">Spēlētāju statistika</div>
    <div class="two-cols">
        <div>
            <div class="team-block-title">
                {{ $game->home_team_name }}
                <span class="team-badge">Mājinieki</span>
            </div>
            @include('games.partials.print-stats-table', ['players' => $homePlayers])
        </div>
        <div>
            <div class="team-block-title">
                {{ $game->away_team_name }}
                <span class="team-badge">Viesi</span>
            </div>
            @include('games.partials.print-stats-table', ['players' => $awayPlayers])
        </div>
    </div>

    {{-- Events log --}}
    <div class="section-title">Notikumu saraksts</div>
    <table>
        <thead>
            <tr>
                <th>Q</th>
                <th>Laiks</th>
                <th>Komanda</th>
                <th>Spēlētājs</th>
                <th>Notikums</th>
                <th class="center">Rezultāts</th>
            </tr>
        </thead>
        <tbody>
            @forelse($game->events->sortBy('id') as $event)
                @php
                    $eventType = $event->event_type ?? 'shot';
                    $label = match($eventType) {
                        'shot'     => strtoupper($event->shot_type ?? '—'),
                        'rebound'  => 'Atlēkusī bumba',
                        'assist'   => 'Piespēle',
                        'steal'    => 'Pārķerta bumba',
                        'turnover' => 'Kļūda',
                        default    => ucfirst($eventType),
                    };
                    $badgeClass = match($eventType) {
                        'shot'     => 'badge-shot',
                        'rebound'  => 'badge-rebound',
                        'assist'   => 'badge-assist',
                        'steal'    => 'badge-steal',
                        'turnover' => 'badge-turnover',
                        default    => '',
                    };
                    $result = match($eventType) {
                        'shot'    => $event->is_made
                                        ? '<span class="made">Iemests</span>'
                                        : '<span class="missed">Garām</span>',
                        'rebound' => match($event->event_subtype) {
                                        'offensive' => 'Uzbrukumā',
                                        'defensive' => 'Aizsardzībā',
                                        default     => '—',
                                    },
                        default   => '—',
                    };
                @endphp
                <tr>
                    <td class="num">Q{{ $event->quarter }}</td>
                    <td class="num">{{ $event->created_at->format('H:i') }}</td>
                    <td>{{ $event->team_side === 'home' ? $game->home_team_name : $game->away_team_name }}</td>
                    <td><strong>#{{ $event->player?->jersey_number }}</strong> {{ $event->player?->player_name ?? '—' }}</td>
                    <td><span class="event-badge {{ $badgeClass }}">{{ $label }}</span></td>
                    <td class="center">{!! $result !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#9ca3af; padding:16px;">Nav reģistrētu notikumu</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer / Signatures --}}
    <div class="footer">
        <div class="signature-line">
            <div class="signature-item">
                <div class="line"></div>
                <div>Galvenais tiesnesis</div>
            </div>
            <div class="signature-item">
                <div class="line"></div>
                <div>Sekretārs</div>
            </div>
        </div>
        <div style="text-align:right; line-height:1.6;">
            <div>Izdrukāts: {{ now()->format('d.m.Y H:i') }}</div>
            <div>BasketCore protokols</div>
        </div>
    </div>

</body>
</html>
