<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_group_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('tournament_groups')->cascadeOnDelete();
            $table->foreignId('team1_id')->constrained('tournament_teams')->cascadeOnDelete();
            $table->foreignId('team2_id')->constrained('tournament_teams')->cascadeOnDelete();
            $table->integer('team1_score')->nullable();
            $table->integer('team2_score')->nullable();
            $table->foreignId('winner_id')->nullable()->constrained('tournament_teams')->nullOnDelete();
            $table->foreignId('game_id')->nullable()->constrained('games')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_group_matches');
    }
};
