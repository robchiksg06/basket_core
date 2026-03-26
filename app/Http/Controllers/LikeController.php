<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Player $player)
    {
        $user = Auth::user();

        $like = Like::where('user_id', $user->id)
                    ->where('player_id', $player->id)
                    ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'player_id' => $player->id
            ]);
        }

        return back();
    }
}