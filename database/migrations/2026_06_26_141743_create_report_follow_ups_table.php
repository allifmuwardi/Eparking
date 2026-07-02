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
        Schema::create('report_follow_ups', function (Blueprint $table) {
            $table->id();

            // Laporan kendala yang sedang ditindaklanjuti
            $table->foreignId('issue_report_id')
                ->constrained('issue_reports')
                ->cascadeOnDelete();

            // User teknisi vendor yang melakukan update
            $table->foreignId('technician_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Status sebelum di-update
            $table->string('previous_status')->nullable();

            // Status setelah di-update
            $table->enum('new_status', [
                'Menunggu Verifikasi',
                'Dalam Proses',
                'Menunggu Informasi',
                'Selesai Ditangani',
                'Ditolak',
                'Ditutup / Diarsipkan',
            ]);

            // Catatan hasil pengecekan atau penanganan dari teknisi
            $table->text('follow_up_note');

            // Foto dokumentasi hasil penanganan
            $table->string('documentation_photo')->nullable();

            // Apakah teknisi membutuhkan barang backup
            $table->boolean('need_backup_item')->default(false);

            // Barang backup yang dibutuhkan, jika ada
            $table->foreignId('backup_item_id')
                ->nullable()
                ->constrained('backup_items')
                ->nullOnDelete();

            // Jumlah barang backup yang dibutuhkan
            $table->integer('backup_item_quantity')->nullable();

            // Catatan kebutuhan barang backup
            $table->text('backup_item_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_follow_ups');
    }
};