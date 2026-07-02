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
        Schema::create('backup_requests', function (Blueprint $table) {
            $table->id();

            // Nomor permintaan barang backup, contoh: BRQ-20260626-0001
            $table->string('request_number')->unique();

            // User petugas parkir yang membuat permintaan
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Lokasi parkir yang membutuhkan barang backup
            $table->foreignId('parking_location_id')
                ->constrained('parking_locations')
                ->cascadeOnDelete();

            // Barang backup yang diminta
            $table->foreignId('backup_item_id')
                ->constrained('backup_items')
                ->cascadeOnDelete();

            // Jumlah barang yang diminta
            $table->integer('quantity')->default(1);

            // Alasan permintaan barang backup
            $table->text('reason');

            // Tingkat kebutuhan
            $table->enum('priority', ['Rendah', 'Sedang', 'Tinggi', 'Darurat'])
                ->default('Sedang');

            // Status permintaan barang backup
            $table->enum('status', [
                'Menunggu Verifikasi',
                'Disetujui',
                'Ditolak',
                'Dalam Proses',
                'Selesai',
            ])->default('Menunggu Verifikasi');

            // Admin atau manajer yang memverifikasi permintaan
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Waktu verifikasi
            $table->timestamp('verified_at')->nullable();

            // Catatan verifikasi
            $table->text('verification_note')->nullable();

            // Alasan penolakan jika ditolak
            $table->text('rejection_reason')->nullable();

            // Admin yang memproses barang backup
            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Waktu mulai diproses
            $table->timestamp('processed_at')->nullable();

            // Waktu selesai diproses
            $table->timestamp('completed_at')->nullable();

            // Foto bukti penyerahan barang, jika diperlukan
            $table->string('handover_photo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_requests');
    }
};