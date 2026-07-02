<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('daily_traffic_reports', function (Blueprint $table) {
            $table->id();

            // User petugas parkir yang menginput traffic harian
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Lokasi parkir
            $table->foreignId('parking_location_id')
                ->constrained('parking_locations')
                ->cascadeOnDelete();

            // Tanggal laporan traffic
            $table->date('report_date');

            // Shift petugas
            $table->enum('shift', ['Pagi', 'Siang', 'Malam'])->default('Pagi');

            // Jumlah kendaraan masuk
            $table->integer('total_vehicle_in')->default(0);

            // Jumlah kendaraan keluar
            $table->integer('total_vehicle_out')->default(0);

            // Jumlah mobil
            $table->integer('car_count')->default(0);

            // Jumlah motor
            $table->integer('motorcycle_count')->default(0);

            // Jumlah kendaraan lain, jika ada
            $table->integer('other_vehicle_count')->default(0);

            // Total transaksi parkir
            $table->integer('total_transaction')->default(0);

            // Total pendapatan parkir harian
            $table->decimal('total_revenue', 15, 2)->default(0);

            // Catatan operasional harian
            $table->text('notes')->nullable();

            // Foto dokumentasi traffic harian, jika diperlukan
            $table->string('photo')->nullable();

            $table->timestamps();

            // Mencegah 1 petugas membuat laporan traffic ganda
            // pada lokasi, tanggal, dan shift yang sama.
            $table->unique(
                ['user_id', 'parking_location_id', 'report_date', 'shift'],
                'unique_daily_traffic_report'
            );
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_traffic_reports');
    }
};