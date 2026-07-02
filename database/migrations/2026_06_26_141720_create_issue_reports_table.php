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
        Schema::create('issue_reports', function (Blueprint $table) {
            $table->id();

            // Nomor laporan, contoh: RPT-20260626-0001
            $table->string('report_number')->unique();

            // User petugas parkir yang membuat laporan
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Lokasi parkir tempat kendala terjadi
            $table->foreignId('parking_location_id')
                ->constrained('parking_locations')
                ->cascadeOnDelete();

            // Judul kendala, contoh: Barrier Gate Tidak Terbuka
            $table->string('title');

            // Kategori kendala, contoh: Perangkat, Sistem, Tiket, Pembayaran, Lainnya
            $table->string('category');

            // Prioritas kendala
            $table->enum('priority', ['Rendah', 'Sedang', 'Tinggi', 'Darurat'])
                ->default('Sedang');

            // Deskripsi lengkap kendala dari petugas parkir
            $table->text('description');

            // Foto bukti kendala
            $table->string('photo')->nullable();

            // Status laporan
            $table->enum('status', [
                'Menunggu Verifikasi',
                'Dalam Proses',
                'Menunggu Informasi',
                'Selesai Ditangani',
                'Ditolak',
                'Ditutup / Diarsipkan',
            ])->default('Menunggu Verifikasi');

            // Teknisi vendor yang ditugaskan oleh manajer
            $table->foreignId('assigned_technician_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Manajer operasional yang memverifikasi laporan
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Waktu laporan diverifikasi
            $table->timestamp('verified_at')->nullable();

            // Catatan verifikasi dari manajer
            $table->text('verification_note')->nullable();

            // Alasan jika laporan ditolak
            $table->text('rejection_reason')->nullable();

            // Waktu laporan ditutup / diarsipkan
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_reports');
    }
};