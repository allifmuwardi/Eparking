<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_code', 50)->unique();
            $table->string('location_name');
            $table->string('area')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('status')->default('Aktif');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_locations');
    }
};