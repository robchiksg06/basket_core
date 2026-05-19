<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('PRAGMA foreign_keys=off');

        DB::statement('
            CREATE TABLE game_events_fixed (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                game_id INTEGER NOT NULL,
                game_player_id INTEGER NOT NULL,
                team_side VARCHAR(255) NOT NULL,
                quarter TINYINT UNSIGNED NOT NULL,
                event_type VARCHAR(255) NOT NULL DEFAULT "shot",
                event_subtype VARCHAR(255) NULL,
                shot_type VARCHAR(255) NULL,
                is_made TINYINT(1) NULL,
                court_x NUMERIC(8,2) NULL,
                court_y NUMERIC(8,2) NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
                FOREIGN KEY (game_player_id) REFERENCES game_players(id) ON DELETE CASCADE
            )
        ');

        DB::statement('INSERT INTO game_events_fixed SELECT * FROM game_events');
        DB::statement('DROP TABLE game_events');
        DB::statement('ALTER TABLE game_events_fixed RENAME TO game_events');

        DB::statement('PRAGMA foreign_keys=on');
    }

    public function down(): void
    {
        //
    }
};
