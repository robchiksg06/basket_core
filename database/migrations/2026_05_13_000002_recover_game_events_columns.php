<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('PRAGMA foreign_keys=off');

        DB::statement('
            CREATE TABLE game_events_v3 (
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

        // Iepriekšējā migrācija izmantoja SELECT * bez kolonnu nosaukumiem,
        // tāpēc kolonnas tika sajauktas. Šeit tās atjauno pareizi:
        //
        // Pašreizējā stāvoklis (bojātajā tabulā):
        //   event_type    → satur veco shot_type vērtību ('ft'/'2pt'/'3pt')
        //   event_subtype → satur veco is_made vērtību (0 vai 1)
        //   shot_type     → satur veco created_at (datetime string)
        //   is_made       → satur veco updated_at (datetime string)
        //   created_at    → satur veco event_type ('shot') — tāpēc Carbon kļūda
        //   updated_at    → satur veco event_subtype (NULL)
        DB::statement("
            INSERT INTO game_events_v3
                (id, game_id, game_player_id, team_side, quarter,
                 event_type, event_subtype, shot_type, is_made,
                 court_x, court_y, created_at, updated_at)
            SELECT
                id,
                game_id,
                game_player_id,
                team_side,
                quarter,
                'shot'                          AS event_type,
                NULL                            AS event_subtype,
                event_type                      AS shot_type,
                CAST(event_subtype AS INTEGER)  AS is_made,
                court_x,
                court_y,
                shot_type                       AS created_at,
                is_made                         AS updated_at
            FROM game_events
            WHERE event_type IN ('ft', '2pt', '3pt')
        ");

        DB::statement('DROP TABLE game_events');
        DB::statement('ALTER TABLE game_events_v3 RENAME TO game_events');

        DB::statement('PRAGMA foreign_keys=on');
    }

    public function down(): void
    {
        //
    }
};
