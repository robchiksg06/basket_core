<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentGroupTeam extends Model
{
    protected $fillable = ['group_id', 'team_id'];

    public function group()
    {
        return $this->belongsTo(TournamentGroup::class, 'group_id');
    }

    public function team()
    {
        return $this->belongsTo(TournamentTeam::class, 'team_id');
    }
}
