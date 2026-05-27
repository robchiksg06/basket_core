<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->string('season', 20);
            $table->string('team_name', 255)->nullable();
            $table->unsignedSmallInteger('games_played')->default(0);
            $table->decimal('points_per_game', 5, 2)->default(0);
            $table->decimal('rebounds_per_game', 5, 2)->default(0);
            $table->decimal('assists_per_game', 5, 2)->default(0);
            $table->decimal('steals_per_game', 5, 2)->default(0);
            $table->decimal('blocks_per_game', 5, 2)->default(0);
            $table->decimal('field_goal_pct', 5, 2)->nullable();
            $table->decimal('three_point_pct', 5, 2)->nullable();
            $table->decimal('free_throw_pct', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_seasons');
    }
};
