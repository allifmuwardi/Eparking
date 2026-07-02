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
            $table->text('address')->nullable();
            $table->string('area')->nullable();
            $table->string('city')->nullable();

            // PIC lokasi parkir
            $table->string('pic_name')->nullable();
            $table->string('pic_phone', 30)->nullable();

            // Tambahan cadangan kalau ada view/controller lama yang masih pakai phone
            $table->string('phone', 30)->nullable();

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