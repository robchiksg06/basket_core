<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    protected $fillable = [
        'game_id',
        'team_side',
        'player_name',
        'jersey_number',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function events()
    {
        return $this->hasMany(GameEvent::class, 'game_player_id');
    }

    public function statLine(): array
    {
        $events = $this->events;

        $ftMade = $events->where('event_type', 'shot')->where('shot_type', 'ft')->where('is_made', true)->count();
        $ftAtt = $events->where('event_type', 'shot')->where('shot_type', 'ft')->count();

        $twoMade = $events->where('event_type', 'shot')->where('shot_type', '2pt')->where('is_made', true)->count();
        $twoAtt = $events->where('event_type', 'shot')->where('shot_type', '2pt')->count();

        $threeMade = $events->where('event_type', 'shot')->where('shot_type', '3pt')->where('is_made', true)->count();
        $threeAtt = $events->where('event_type', 'shot')->where('shot_type', '3pt')->count();

        $offRebounds = $events->where('event_type', 'rebound')->where('event_subtype', 'offensive')->count();
        $defRebounds = $events->where('event_type', 'rebound')->where('event_subtype', 'defensive')->count();

        $assists = $events->where('event_type', 'assist')->count();
        $steals = $events->where('event_type', 'steal')->count();
        $turnovers = $events->where('event_type', 'turnover')->count();

        return [
            'points' => ($ftMade * 1) + ($twoMade * 2) + ($threeMade * 3),

            'ft_made' => $ftMade,
            'ft_att' => $ftAtt,

            '2pt_made' => $twoMade,
            '2pt_att' => $twoAtt,

            '3pt_made' => $threeMade,
            '3pt_att' => $threeAtt,

            'rebounds' => $offRebounds + $defRebounds,
            'off_rebounds' => $offRebounds,
            'def_rebounds' => $defRebounds,

            'assists' => $assists,
            'steals' => $steals,
            'turnovers' => $turnovers,
        ];
    }
}