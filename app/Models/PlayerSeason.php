<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerSeason extends Model
{
    protected $fillable = [
        'player_id',
        'season',
        'team_name',
        'games_played',
        'points_per_game',
        'rebounds_per_game',
        'assists_per_game',
        'steals_per_game',
        'blocks_per_game',
        'field_goal_pct',
        'three_point_pct',
        'free_throw_pct',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
