<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentVote extends Model
{
    protected $fillable = ['tournament_id', 'user_id', 'tournament_team_id'];

    public function team()
    {
        return $this->belongsTo(TournamentTeam::class, 'tournament_team_id');
    }
}
