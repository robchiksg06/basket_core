<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('game_events', function (Blueprint $table) {
            $table->decimal('court_x', 8, 2)->nullable()->after('is_made');
            $table->decimal('court_y', 8, 2)->nullable()->after('court_x');
        });
    }

    public function down(): void
    {
        Schema::table('game_events', function (Blueprint $table) {
            $table->dropColumn(['court_x', 'court_y']);
        });
    }
};