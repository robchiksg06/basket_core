<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('game_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_player_id')->constrained()->cascadeOnDelete();
            $table->string('team_side');
            $table->unsignedTinyInteger('quarter');

            $table->string('event_type')->default('shot');
            $table->string('event_subtype')->nullable();

            $table->string('shot_type')->nullable();
            $table->boolean('is_made')->nullable();

            $table->decimal('court_x', 8, 2)->nullable();
            $table->decimal('court_y', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_events');
    }
};
