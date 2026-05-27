<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TournamentGroup extends Model
{
    protected $fillable = ['tournament_id', 'name'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function groupTeams()
    {
        return $this->hasMany(TournamentGroupTeam::class, 'group_id');
    }

    public function matches()
    {
        return $this->hasMany(TournamentGroupMatch::class, 'group_id');
    }

    public function standings(): Collection
    {
        $groupTeams = $this->groupTeams()->with('team')->get();
        $matches    = $this->matches()->whereNotNull('winner_id')->get();

        $stats = [];
        foreach ($groupTeams as $gt) {
            $stats[$gt->team_id] = [
                'team'            => $gt->team,
                'played'          => 0,
                'wins'            => 0,
                'losses'          => 0,
                'points_for'      => 0,
                'points_against'  => 0,
            ];
        }

        foreach ($matches as $m) {
            if (!isset($stats[$m->team1_id], $stats[$m->team2_id])) continue;

            $stats[$m->team1_id]['played']++;
            $stats[$m->team2_id]['played']++;
            $stats[$m->team1_id]['points_for']     += $m->team1_score;
            $stats[$m->team1_id]['points_against']  += $m->team2_score;
            $stats[$m->team2_id]['points_for']     += $m->team2_score;
            $stats[$m->team2_id]['points_against']  += $m->team1_score;

            if ($m->winner_id === $m->team1_id) {
                $stats[$m->team1_id]['wins']++;
                $stats[$m->team2_id]['losses']++;
            } else {
                $stats[$m->team2_id]['wins']++;
                $stats[$m->team1_id]['losses']++;
            }
        }

        uasort($stats, function ($a, $b) {
            if ($a['wins'] !== $b['wins']) return $b['wins'] - $a['wins'];
            $da = $a['points_for'] - $a['points_against'];
            $db = $b['points_for'] - $b['points_against'];
            if ($da !== $db) return $db - $da;
            return $b['points_for'] - $a['points_for'];
        });

        return collect(array_values($stats));
    }
}
