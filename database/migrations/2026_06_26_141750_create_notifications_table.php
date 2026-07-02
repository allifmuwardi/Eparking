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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // User penerima notifikasi
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Judul notifikasi
            $table->string('title');

            // Isi pesan notifikasi
            $table->text('message');

            // Tipe notifikasi, contoh: report, backup_request, traffic, system
            $table->string('type')->default('system');

            // ID data terkait, misalnya ID laporan atau ID permintaan barang
            $table->unsignedBigInteger('reference_id')->nullable();

            // Nama tabel/model terkait, contoh: issue_reports, backup_requests
            $table->string('reference_type')->nullable();

            // Link tujuan ketika notifikasi diklik
            $table->string('url')->nullable();

            // Status sudah dibaca atau belum
            $table->boolean('is_read')->default(false);

            // Waktu notifikasi dibaca
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};