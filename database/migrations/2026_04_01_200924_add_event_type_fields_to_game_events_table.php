<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_events', function (Blueprint $table) {
            $table->string('event_type')->default('shot')->after('quarter');
            $table->string('event_subtype')->nullable()->after('event_type');
        });
    }

    public function down(): void
    {
        Schema::table('game_events', function (Blueprint $table) {
            $table->dropColumn(['event_type', 'event_subtype']);
        });
    }
};