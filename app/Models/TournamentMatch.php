<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{
    protected $fillable = [
        'tournament_id', 'round', 'position',
        'team1_id', 'team2_id',
        'team1_score', 'team2_score', 'winner_id',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team1()
    {
        return $this->belongsTo(TournamentTeam::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(TournamentTeam::class, 'team2_id');
    }

    public function winner()
    {
        return $this->belongsTo(TournamentTeam::class, 'winner_id');
    }

    public function isPending(): bool
    {
        return $this->team1_id && $this->team2_id && !$this->winner_id;
    }
}
