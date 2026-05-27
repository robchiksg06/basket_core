<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->enum('format', ['single_elimination', 'group_knockout'])
                ->default('single_elimination')->after('status');
            $table->unsignedTinyInteger('groups_count')->nullable()->after('format');
            $table->unsignedTinyInteger('advance_per_group')->nullable()->after('groups_count');
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn(['format', 'groups_count', 'advance_per_group']);
        });
    }
};
