<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'country', 'logo'];

    public function leagues()
    {
        return $this->belongsToMany(League::class, 'league_team', 'team_id', 'league_id');
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}