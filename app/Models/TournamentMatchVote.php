<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentMatchVote extends Model
{
    protected $fillable = ['tournament_id', 'match_id', 'match_type', 'user_id', 'voted_team_id'];
}
