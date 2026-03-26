<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->date('game_date')->nullable();
            $table->string('location')->nullable();
            $table->string('home_team_name');
            $table->string('away_team_name');
            $table->enum('status', ['setup', 'live', 'finished'])->default('setup');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
