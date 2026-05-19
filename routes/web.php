<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GameProtocolController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;

/*
|--------------------------------------------------------------------------
| Public / Basic
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('home'))->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Leagues
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/leagues', [LeagueController::class, 'index'])->name('leagues.index');
    Route::get('/leagues/{league}', [LeagueController::class, 'show'])->name('leagues.show');

    Route::get('/coaches', fn() => view('coaches.index'))->name('coaches.index');
});

/*
|--------------------------------------------------------------------------
| Teams
|--------------------------------------------------------------------------
*/

Route::resource('teams', TeamController::class)->middleware('auth');
Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');

/*
|--------------------------------------------------------------------------
| Players
|--------------------------------------------------------------------------
*/

// Visi ielogotie var redzēt sarakstu
Route::get('/players', [PlayerController::class, 'index'])
    ->middleware('auth')
    ->name('players.index');

// Tikai adminiem: create/edit/update/delete
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('players', PlayerController::class)->except(['index']);
});

Route::get('/public/players', [PlayerController::class, 'publicView'])->name('players.public');

Route::get('/public/players/{player}', function (\App\Models\Player $player) {
    return view('players.show', compact('player'));
})->name('players.show');

Route::post('/players/{player}/like', [LikeController::class, 'toggle'])
    ->middleware('auth')
    ->name('players.like');

/*
|--------------------------------------------------------------------------
| Forum
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/{thread}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/{thread}/reply', [ForumController::class, 'reply'])->name('forum.reply');
});

/*
|--------------------------------------------------------------------------
| Games
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/games', [GameProtocolController::class, 'index'])->name('games.index');
    Route::get('/games/create', [GameProtocolController::class, 'create'])->name('games.create');
    Route::post('/games', [GameProtocolController::class, 'store'])->name('games.store');
    Route::get('/games/{game}', [GameProtocolController::class, 'show'])->name('games.show');
    Route::post('/games/{game}/events', [GameProtocolController::class, 'addEvent'])->name('games.events.store');
    Route::delete('/games/{game}/events/{event}', [GameProtocolController::class, 'deleteEvent'])->name('games.events.delete');
    Route::post('/games/{game}/finish', [GameProtocolController::class, 'finish'])->name('games.finish');
    Route::get('/games/{game}/print', [GameProtocolController::class, 'print'])->name('games.print');
    Route::delete('/games/{game}', [GameProtocolController::class, 'destroy'])->name('games.destroy');
    Route::patch('/games/{game}/visibility', [GameProtocolController::class, 'toggleVisibility'])->name('games.visibility');
});