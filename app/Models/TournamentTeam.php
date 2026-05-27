<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentTeam extends Model
{
    protected $fillable = ['tournament_id', 'name', 'seed'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
