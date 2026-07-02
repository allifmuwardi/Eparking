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
        Schema::table('users', function (Blueprint $table) {
            // Username/NIP digunakan untuk login
            $table->string('username')->unique()->after('id');

            // NIP pegawai/petugas
            $table->string('nip')->nullable()->after('username');

            // Nama lengkap user
            $table->string('full_name')->nullable()->after('name');

            // Role user dalam sistem
            $table->enum('role', [
                'petugas',
                'teknisi',
                'manajer',
                'admin',
            ])->default('petugas')->after('email');

            // Nomor telepon user
            $table->string('phone')->nullable()->after('role');

            // Status user
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif')->after('phone');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'nip',
                'full_name',
                'role',
                'phone',
                'status',
            ]);
        });
    }
};