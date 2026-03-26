<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'position',
        'height',
        'team',
        'image',
    ];
    
    public function likedByUsers() {
        return $this->belongsToMany(User::class, 'likes');
    }
    
}