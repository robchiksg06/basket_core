<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentGroupMatch extends Model
{
    protected $fillable = [
        'tournament_id', 'group_id',
        'team1_id', 'team2_id',
        'team1_score', 'team2_score', 'winner_id', 'game_id',
    ];

    public function group()
    {
        return $this->belongsTo(TournamentGroup::class, 'group_id');
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

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function isPending(): bool
    {
        return !$this->winner_id;
    }
}
