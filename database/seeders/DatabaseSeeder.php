<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ParkingLocation;
use App\Models\BackupItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        // User Petugas Parkir
        User::updateOrCreate(
            ['username' => '1001'],
            [
                'nip' => '1001',
                'name' => 'Petugas Parkir',
                'full_name' => 'Petugas Parkir',
                'email' => 'petugas@eliteparkir.test',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'phone' => '081111111001',
                'status' => 'Aktif',
            ]
        );

        // User Teknisi Vendor
        User::updateOrCreate(
            ['username' => '2001'],
            [
                'nip' => '2001',
                'name' => 'Teknisi Vendor',
                'full_name' => 'Teknisi Vendor',
                'email' => 'teknisi@eliteparkir.test',
                'password' => Hash::make('password'),
                'role' => 'teknisi',
                'phone' => '081111112001',
                'status' => 'Aktif',
            ]
        );

        // User Manajer Operasional
        User::updateOrCreate(
            ['username' => '3001'],
            [
                'nip' => '3001',
                'name' => 'Manajer Operasional',
                'full_name' => 'Manajer Operasional',
                'email' => 'manajer@eliteparkir.test',
                'password' => Hash::make('password'),
                'role' => 'manajer',
                'phone' => '081111113001',
                'status' => 'Aktif',
            ]
        );

        // User Admin
        User::updateOrCreate(
            ['username' => '4001'],
            [
                'nip' => '4001',
                'name' => 'Admin',
                'full_name' => 'Admin Sistem',
                'email' => 'admin@eliteparkir.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081111114001',
                'status' => 'Aktif',
            ]
        );

        // Data awal lokasi parkir
        ParkingLocation::updateOrCreate(
            ['location_code' => 'LPK001'],
            [
                'location_name' => 'Area Parkir Utama',
                'address' => 'Jl. Contoh Lokasi Parkir',
                'area' => 'Gate Utama',
                'pic_name' => 'Koordinator Parkir',
                'pic_phone' => '081234567890',
                'status' => 'Aktif',
            ]
        );

        // Data awal barang backup
        BackupItem::updateOrCreate(
            ['item_code' => 'BRG001'],
            [
                'item_name' => 'Printer Tiket',
                'category' => 'Hardware',
                'stock' => 5,
                'unit' => 'unit',
                'storage_location' => 'Gudang Operasional',
                'description' => 'Printer tiket cadangan untuk kebutuhan operasional parkir.',
                'status' => 'Tersedia',
            ]
        );

        BackupItem::updateOrCreate(
            ['item_code' => 'BRG002'],
            [
                'item_name' => 'Roll Tiket',
                'category' => 'Consumable',
                'stock' => 20,
                'unit' => 'roll',
                'storage_location' => 'Gudang Operasional',
                'description' => 'Roll tiket cadangan untuk dispenser tiket.',
                'status' => 'Tersedia',
            ]
        );
    }
}