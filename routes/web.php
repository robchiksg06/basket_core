<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GameProtocolController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerStatsImportController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TournamentController;

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

Route::post('/users/{user}/follow', [App\Http\Controllers\FollowController::class, 'toggle'])
    ->middleware('auth')
    ->name('users.follow');

Route::get('/dashboard', App\Http\Controllers\DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/account', [App\Http\Controllers\AccountController::class, 'show'])->name('account.settings');
    Route::patch('/account/profile', [App\Http\Controllers\AccountController::class, 'updateProfile'])->name('account.profile');
    Route::patch('/account/password', [App\Http\Controllers\AccountController::class, 'updatePassword'])->name('account.password');
    Route::post('/account/avatar', [App\Http\Controllers\AccountController::class, 'updateAvatar'])->name('account.avatar');
});

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
    Route::get('/players/import-stats', [PlayerStatsImportController::class, 'show'])->name('players.import-stats');
    Route::post('/players/import-stats', [PlayerStatsImportController::class, 'import'])->name('players.import-stats.store');
    Route::resource('players', PlayerController::class)->except(['index']);
});

Route::get('/public/players', [PlayerController::class, 'publicView'])->name('players.public');
Route::get('/public/players/compare', [PlayerController::class, 'compare'])->name('players.compare');

Route::get('/public/players/{player}', function (\App\Models\Player $player) {
    $player->load('seasons');
    return view('players.show', compact('player'));
})->name('players.public.show');

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
    Route::get('/leaderboard', App\Http\Controllers\LeaderboardController::class)->name('leaderboard');
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

/*
|--------------------------------------------------------------------------
| Tournaments
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
    Route::post('/tournaments/{tournament}/matches/{match}/result', [TournamentController::class, 'result'])->name('tournaments.result');
    Route::post('/tournaments/{tournament}/group-matches/{match}/result', [TournamentController::class, 'groupResult'])->name('tournaments.group-result');
    Route::post('/tournaments/{tournament}/generate-knockout', [TournamentController::class, 'generateKnockout'])->name('tournaments.generate-knockout');
    Route::patch('/tournaments/{tournament}/visibility', [TournamentController::class, 'toggleVisibility'])->name('tournaments.visibility');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    Route::post('/tournaments/{tournament}/vote', [App\Http\Controllers\TournamentVoteController::class, 'vote'])->name('tournaments.vote');
    Route::post('/tournaments/{tournament}/match-vote', [App\Http\Controllers\TournamentMatchVoteController::class, 'vote'])->name('tournaments.match-vote');
});