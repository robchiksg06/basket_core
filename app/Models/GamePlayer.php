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
        return $this->hasMany(GameEvent::class);
    }

    public function getPointsAttribute(): int
    {
        return $this->events->sum(function ($event) {
            if (!$event->is_made) {
                return 0;
            }

            return match ($event->shot_type) {
                'ft' => 1,
                '2pt' => 2,
                '3pt' => 3,
            };
        });
    }

    public function statLine(): array
    {
        $events = $this->events;

        $ftMade = $events->where('shot_type', 'ft')->where('is_made', true)->count();
        $ftAtt = $events->where('shot_type', 'ft')->count();

        $twoMade = $events->where('shot_type', '2pt')->where('is_made', true)->count();
        $twoAtt = $events->where('shot_type', '2pt')->count();

        $threeMade = $events->where('shot_type', '3pt')->where('is_made', true)->count();
        $threeAtt = $events->where('shot_type', '3pt')->count();

        return [
            'points' => $this->points,
            'ft_made' => $ftMade,
            'ft_att' => $ftAtt,
            '2pt_made' => $twoMade,
            '2pt_att' => $twoAtt,
            '3pt_made' => $threeMade,
            '3pt_att' => $threeAtt,
        ];
    }
}