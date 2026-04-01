<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameEvent extends Model
{
    protected $fillable = [
        'game_id',
        'game_player_id',
        'team_side',
        'quarter',
        'event_type',
        'event_subtype',
        'shot_type',
        'is_made',
        'court_x',
        'court_y',
    ];

    protected $casts = [
        'is_made' => 'boolean',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function player()
    {
        return $this->belongsTo(GamePlayer::class, 'game_player_id');
    }
}