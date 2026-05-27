<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'position',
        'height',
        'image',
        'team',
        'team_id',
    ];

    public function seasons()
    {
        return $this->hasMany(PlayerSeason::class)->orderByDesc('season');
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function teamRelation()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    protected static function booted(): void
    {
        static::saving(function ($player) {
            if ($player->team_id) {
                $team = Team::find($player->team_id);
                $player->team = $team?->name;
            } else {
                $player->team = null;
            }
        });
    }
}