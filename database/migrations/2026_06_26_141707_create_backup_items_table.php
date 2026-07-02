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
        Schema::create('backup_items', function (Blueprint $table) {
            $table->id();

            // Kode barang backup, contoh: BRG001
            $table->string('item_code')->unique();

            // Nama barang backup, contoh: Printer Tiket
            $table->string('item_name');

            // Kategori barang, contoh: Hardware, Sparepart, Peralatan, Consumable
            $table->string('category')->nullable();

            // Jumlah stok barang backup yang tersedia
            $table->integer('stock')->default(0);

            // Satuan barang, contoh: unit, pcs, roll, box
            $table->string('unit')->default('unit');

            // Lokasi penyimpanan barang
            $table->string('storage_location')->nullable();

            // Keterangan tambahan
            $table->text('description')->nullable();

            // Status barang backup
            $table->enum('status', ['Tersedia', 'Tidak Tersedia'])->default('Tersedia');

            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_items');
    }
};