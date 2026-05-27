<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_match_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('match_id');
            $table->string('match_type'); // 'knockout' or 'group'
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('voted_team_id')->constrained('tournament_teams')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['match_id', 'match_type', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_match_votes');
    }
};
