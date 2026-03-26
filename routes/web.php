<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});

Route::get('/', fn() => view('home'))->name('home');

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', fn() => view('dashboard'))->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/teams', fn() => view('teams.index'))->name('teams.index');
    Route::get('/leagues', fn() => view('leagues.index'))->name('leagues.index');
    Route::get('/coaches', fn() => view('coaches.index'))->name('coaches.index');
    Route::get('/', fn() => view('home'))->name('home');

});


use App\Http\Controllers\PlayerController;

// 1. Visi ielogotie var redzēt sarakstu
Route::get('/players', [PlayerController::class, 'index'])->middleware('auth')->name('players.index');

// 2. Tikai adminiem: create/edit/update/delete
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('players', PlayerController::class)->except(['index']);
});

use App\Http\Controllers\TeamController;

Route::resource('teams', TeamController::class);

use App\Http\Controllers\LeagueController;


Route::get('/leagues/{league}', [LeagueController::class, 'show'])->name('leagues.show');

Route::get('/public/players', [PlayerController::class, 'publicView'])->name('players.public');

Route::get('/public/players/{player}', function (\App\Models\Player $player) {
    return view('players.show', compact('player'));
})->name('players.show');



// routes/web.php

use App\Http\Controllers\LikeController;


Route::post('/players/{player}/like', [LikeController::class, 'toggle'])
    ->middleware('auth')
    ->name('players.like');
    use App\Http\Controllers\ForumController;

    Route::middleware('auth')->group(function () {
        Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
        Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
        Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
        Route::get('/forum/{thread}', [ForumController::class, 'show'])->name('forum.show');
        Route::post('/forum/{thread}/reply', [ForumController::class, 'reply'])->name('forum.reply');
    });

    use App\Http\Controllers\GameProtocolController;

Route::get('/games', [GameProtocolController::class, 'index'])->name('games.index');
Route::get('/games/create', [GameProtocolController::class, 'create'])->name('games.create');
Route::post('/games', [GameProtocolController::class, 'store'])->name('games.store');
Route::get('/games/{game}', [GameProtocolController::class, 'show'])->name('games.show');
Route::post('/games/{game}/events', [GameProtocolController::class, 'addEvent'])->name('games.events.store');
Route::delete('/games/{game}/events/{event}', [GameProtocolController::class, 'deleteEvent'])->name('games.events.delete');
Route::post('/games/{game}/finish', [GameProtocolController::class, 'finish'])->name('games.finish');
Route::get('/games/{game}/print', [GameProtocolController::class, 'print'])->name('games.print');
Route::delete('/games/{game}', [GameProtocolController::class, 'destroy'])->name('games.destroy');
Route::patch('/games/{game}/visibility', [GameProtocolController::class, 'toggleVisibility'])
    ->name('games.visibility');
    



    







