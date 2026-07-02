<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'parking_location_id')) {
                $table->foreignId('parking_location_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('parking_locations')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'parking_location_id')) {
                $table->dropForeign(['parking_location_id']);
                $table->dropColumn('parking_location_id');
            }
        });
    }
};