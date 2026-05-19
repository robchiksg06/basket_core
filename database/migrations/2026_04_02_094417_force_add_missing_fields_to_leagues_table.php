<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('leagues', 'name')) {
            Schema::table('leagues', function (Blueprint $table) {
                $table->string('name')->nullable();
            });
        }

        if (! Schema::hasColumn('leagues', 'description')) {
            Schema::table('leagues', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }

        if (! Schema::hasColumn('leagues', 'logo')) {
            Schema::table('leagues', function (Blueprint $table) {
                $table->string('logo')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('leagues', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('leagues', 'name')) {
                $columnsToDrop[] = 'name';
            }

            if (Schema::hasColumn('leagues', 'description')) {
                $columnsToDrop[] = 'description';
            }

            if (Schema::hasColumn('leagues', 'logo')) {
                $columnsToDrop[] = 'logo';
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};