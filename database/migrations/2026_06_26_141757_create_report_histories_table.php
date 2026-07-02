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
        Schema::create('report_histories', function (Blueprint $table) {
            $table->id();

            // Laporan kendala yang memiliki histori
            $table->foreignId('issue_report_id')
                ->constrained('issue_reports')
                ->cascadeOnDelete();

            // User yang melakukan aksi
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Aksi yang dilakukan, contoh: created, verified, assigned, updated, closed
            $table->string('action');

            // Status sebelum perubahan
            $table->string('previous_status')->nullable();

            // Status setelah perubahan
            $table->string('new_status')->nullable();

            // Catatan histori
            $table->text('notes')->nullable();

            // Data tambahan dalam format JSON jika diperlukan
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_histories');
    }
};