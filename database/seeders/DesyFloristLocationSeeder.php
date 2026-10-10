<?php

namespace Database\Seeders;

use App\Models\DeliveryZone;
use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class DesyFloristLocationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default Store Profile Settings
        $settings = [
            'store_name' => 'Desy Florist',
            'store_tagline' => 'Artisan Floral Studio & Same Day Delivery Surabaya',
            'store_city' => 'Surabaya',
            'store_district' => 'Surabaya Pusat',
            'store_province' => 'Jawa Timur',
            'store_timezone' => 'Asia/Jakarta',
            'store_address' => 'Jl. Kayoon No. 42 (Sentra Bunga Surabaya Pusat), Kec. Genteng, Surabaya, Jawa Timur 60271',
            'store_latitude' => '-7.2654000',
            'store_longitude' => '112.7483000',
            'store_phone' => '+62 31 5345678',
            'store_whatsapp' => '6281234567890',
            'store_email' => 'halo@desyflorist.com',
            'store_open_hours' => 'Senin - Minggu: 08:00 - 20:00 WIB',
            'operating_hours' => '08:00 - 20:00 WIB',
            'operating_days' => 'Senin - Minggu',
            'same_day_cutoff' => '16:00',
            'free_shipping_min' => '750000',
            'extra_fee_per_km' => '3000',
            'max_delivery_distance_km' => '25.0',
            'google_maps_api_key' => '',
            'unserviced_message' => 'Mohon maaf, alamat tujuan Anda saat ini berada di luar jangkauan operasional pengiriman Desy Florist Surabaya.',
            'enabled_districts' => json_encode([
                'Tegalsari', 'Genteng', 'Bubutan', 'Simokerto', // Surabaya Pusat
                'Gubeng', 'Tambaksari', 'Wonokromo', 'Sawahan', 'Krembangan', // Ring 1 dekat
                'Sukolilo', 'Mulyorejo', 'Rungkut', 'Dukuh Pakis', 'Wonocolo', // Ring 2 menengah
                // Kecamatan lain default tidak aktif agar admin dapat mengaktifkan sesuai kapasitas
            ]),
            'delivery_slots' => json_encode([
                ['id' => 'slot-1', 'name' => 'Slot Pagi', 'time' => '08:00 - 11:00', 'quota' => 10, 'is_active' => true],
                ['id' => 'slot-2', 'name' => 'Slot Siang', 'time' => '11:00 - 14:00', 'quota' => 15, 'is_active' => true],
                ['id' => 'slot-3', 'name' => 'Slot Sore', 'time' => '14:00 - 17:00', 'quota' => 15, 'is_active' => true],
                ['id' => 'slot-4', 'name' => 'Slot Malam', 'time' => '17:00 - 20:00', 'quota' => 8, 'is_active' => true],
            ]),
        ];

        foreach ($settings as $key => $value) {
            StoreSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // 2. Set up Clean 3 Zones System (Surabaya Pusat origin)
        DeliveryZone::query()->delete();

        DeliveryZone::create([
            'name' => 'Zona 1: Dekat Toko (Surabaya Pusat & Ring 1)',
            'min_distance_km' => 0.0,
            'max_distance_km' => 5.0,
            'delivery_fee' => 15000,
            'min_order' => 100000,
            'areas' => ['Tegalsari', 'Genteng', 'Bubutan', 'Simokerto', 'Gubeng'],
            'available_slots' => ['08:00 - 11:00', '11:00 - 14:00', '14:00 - 17:00', '17:00 - 20:00'],
            'status' => true,
        ]);

        DeliveryZone::create([
            'name' => 'Zona 2: Wilayah Menengah (Surabaya Ring 2)',
            'min_distance_km' => 5.1,
            'max_distance_km' => 15.0,
            'delivery_fee' => 30000,
            'min_order' => 150000,
            'areas' => ['Sawahan', 'Wonokromo', 'Tambaksari', 'Krembangan', 'Pabean Cantian', 'Sukolilo', 'Mulyorejo', 'Dukuh Pakis', 'Gayungan', 'Wonocolo', 'Rungkut', 'Tenggilis Mejoyo'],
            'available_slots' => ['08:00 - 11:00', '11:00 - 14:00', '14:00 - 17:00', '17:00 - 20:00'],
            'status' => true,
        ]);

        DeliveryZone::create([
            'name' => 'Zona 3: Wilayah Lebih Jauh (Surabaya Ring 3)',
            'min_distance_km' => 15.1,
            'max_distance_km' => 25.0,
            'delivery_fee' => 45000,
            'min_order' => 200000,
            'areas' => ['Sambikerep', 'Sukomanunggal', 'Tandes', 'Wiyung', 'Karang Pilang', 'Jambangan', 'Gunung Anyar', 'Kenjeran', 'Bulak', 'Semampir', 'Asemrowo', 'Benowo', 'Pakal', 'Lakarsantri'],
            'available_slots' => ['11:00 - 14:00', '14:00 - 17:00', '17:00 - 20:00'],
            'status' => true,
        ]);
    }
}
