<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerSeason;
use Illuminate\Http\Request;

class PlayerStatsImportController extends Controller
{
    public function show()
    {
        return view('players.import-stats');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'CSV fails ir tukšs.']);
        }

        $header = array_map('trim', $header);
        $expected = ['player_name', 'season', 'team_name', 'games_played', 'points_per_game',
                     'rebounds_per_game', 'assists_per_game', 'steals_per_game', 'blocks_per_game',
                     'field_goal_pct', 'three_point_pct', 'free_throw_pct'];

        $missingCols = array_diff($expected, $header);
        if (!empty($missingCols)) {
            fclose($handle);
            return back()->withErrors([
                'csv_file' => 'Trūkst kolonnas: ' . implode(', ', $missingCols),
            ]);
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors  = [];
        $row = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            if (count($data) < count($expected)) {
                $errors[] = "Rinda $row: nepietiek kolonnu.";
                $skipped++;
                continue;
            }

            $values = array_combine($header, $data);
            $values = array_map('trim', $values);

            $player = Player::whereRaw('LOWER(name) = ?', [strtolower($values['player_name'])])->first();
            if (!$player) {
                $errors[] = "Rinda $row: spēlētājs \"{$values['player_name']}\" nav atrasts.";
                $skipped++;
                continue;
            }

            $season = $values['season'];
            if (empty($season)) {
                $errors[] = "Rinda $row: sezona ir tukša.";
                $skipped++;
                continue;
            }

            $existing = PlayerSeason::where('player_id', $player->id)
                ->where('season', $season)
                ->first();

            $statsData = [
                'team_name'          => $values['team_name'] ?: null,
                'games_played'       => (int) ($values['games_played'] ?: 0),
                'points_per_game'    => (float) ($values['points_per_game'] ?: 0),
                'rebounds_per_game'  => (float) ($values['rebounds_per_game'] ?: 0),
                'assists_per_game'   => (float) ($values['assists_per_game'] ?: 0),
                'steals_per_game'    => (float) ($values['steals_per_game'] ?: 0),
                'blocks_per_game'    => (float) ($values['blocks_per_game'] ?: 0),
                'field_goal_pct'     => $values['field_goal_pct'] !== '' ? (float) $values['field_goal_pct'] : null,
                'three_point_pct'    => $values['three_point_pct'] !== '' ? (float) $values['three_point_pct'] : null,
                'free_throw_pct'     => $values['free_throw_pct'] !== '' ? (float) $values['free_throw_pct'] : null,
            ];

            if ($existing) {
                $existing->update($statsData);
                $updated++;
            } else {
                PlayerSeason::create(array_merge(['player_id' => $player->id, 'season' => $season], $statsData));
                $created++;
            }
        }

        fclose($handle);

        $message = "Importēts: $created jauni, $updated atjaunināti, $skipped izlaisti.";
        if (!empty($errors)) {
            session()->flash('import_errors', array_slice($errors, 0, 20));
        }

        return back()->with('success', $message);
    }
}
