<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_events', function (Blueprint $table) {
            $table->string('shot_type')->nullable()->change();
            $table->boolean('is_made')->nullable()->change();
            $table->decimal('court_x', 8, 2)->nullable()->change();
            $table->decimal('court_y', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('game_events', function (Blueprint $table) {
            $table->string('shot_type')->nullable(false)->change();
            $table->boolean('is_made')->nullable(false)->default(false)->change();
            $table->decimal('court_x', 8, 2)->nullable(false)->default(0)->change();
            $table->decimal('court_y', 8, 2)->nullable(false)->default(0)->change();
        });
    }
};