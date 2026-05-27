<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        $me = Auth::user();
        abort_if($me->id === $user->id, 403);

        if ($me->following()->where('following_id', $user->id)->exists()) {
            $me->following()->detach($user->id);
            $label = 'Pārtrauci sekot ' . $user->name;
        } else {
            $me->following()->attach($user->id);
            $label = 'Tagad sekoji ' . $user->name;
        }

        return back()->with('success', $label);
    }
}
