<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'title',
        'game_date',
        'location',
        'home_team_name',
        'away_team_name',
        'status',
        'user_id',
        'is_public',
    ];

    public function players()
    {
        return $this->hasMany(GamePlayer::class);
    }
    public function user()
    {
    return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(GameEvent::class);
    }

    public function homePlayers()
    {
        return $this->players()->where('team_side', 'home');
    }

    public function awayPlayers()
    {
        return $this->players()->where('team_side', 'away');
    }

    public function getHomeScoreAttribute(): int
    {
        return $this->events->where('team_side', 'home')->sum(function ($event) {
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

    public function getAwayScoreAttribute(): int
    {
        return $this->events->where('team_side', 'away')->sum(function ($event) {
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
}