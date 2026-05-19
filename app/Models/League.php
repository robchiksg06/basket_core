<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
        'name',
        'description',
        'logo',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'league_team', 'league_id', 'team_id');
    }
}