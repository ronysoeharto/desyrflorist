<?php

namespace Database\Seeders;

use App\Models\DeliveryZone;
use Illuminate\Database\Seeder;

class DeliveryZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Zona 1 — Jakarta Pusat & Jakarta Barat',
                'areas' => ['Menteng', 'Gambir', 'Tanah Abang', 'Kebon Jeruk', 'Palmerah', 'Grogol', 'Tanjung Duren', 'Tomang'],
                'delivery_fee' => 25000,
                'min_order' => 150000,
                'available_slots' => ['09:00 - 12:00', '13:00 - 16:00', '16:00 - 19:00', '19:00 - 21:00'],
                'status' => 'active',
            ],
            [
                'name' => 'Zona 2 — Jakarta Selatan',
                'areas' => ['Kebayoran Baru', 'Kebayoran Lama', 'Cilandak', 'Tebet', 'Pancoran', 'Setiabudi', 'Kemang', 'Senopati', 'Pondok Indah'],
                'delivery_fee' => 35000,
                'min_order' => 200000,
                'available_slots' => ['09:00 - 12:00', '13:00 - 16:00', '16:00 - 19:00'],
                'status' => 'active',
            ],
            [
                'name' => 'Zona 3 — Jakarta Timur & Jakarta Utara',
                'areas' => ['Kelapa Gading', 'Sunter', 'Pluit', 'PIK', 'Pulo Gadung', 'Jatinegara', 'Duren Sawit'],
                'delivery_fee' => 45000,
                'min_order' => 200000,
                'available_slots' => ['10:00 - 14:00', '14:00 - 18:00'],
                'status' => 'active',
            ],
            [
                'name' => 'Zona 4 — Tangerang & Tangerang Selatan (BSD, Bintaro)',
                'areas' => ['BSD City', 'Serpong', 'Alam Sutera', 'Gading Serpong', 'Bintaro', 'Ciputat', 'Pamulang'],
                'delivery_fee' => 55000,
                'min_order' => 300000,
                'available_slots' => ['10:00 - 14:00', '15:00 - 19:00'],
                'status' => 'active',
            ],
            [
                'name' => 'Zona 5 — Depok & Bekasi',
                'areas' => ['Depok Margonda', 'Cinere', 'Bekasi Barat', 'Summarecon Bekasi'],
                'delivery_fee' => 65000,
                'min_order' => 350000,
                'available_slots' => ['11:00 - 16:00'],
                'status' => 'active',
            ],
            [
                'name' => 'Zona 6 — Surabaya Pusat & Sekitarnya (Bubutan, Genteng, Tegalsari, Gubeng)',
                'areas' => ['Bubutan', 'Genteng', 'Tegalsari', 'Gubeng', 'Wonokromo', 'Sawahan', 'Tambaksari', 'Krembangan'],
                'delivery_fee' => 25000,
                'min_order' => 150000,
                'available_slots' => ['09:00 - 12:00', '13:00 - 16:00', '16:00 - 19:00', '19:00 - 21:00'],
                'status' => 'active',
            ],
            [
                'name' => 'Zona 7 — Surabaya Timur, Barat, Selatan & Sidoarjo',
                'areas' => ['Rungkut', 'Sukolilo', 'Mulyorejo', 'Sambikerep', 'Sukomanunggal', 'Wiyung', 'Gayungan', 'Waru Sidoarjo', 'Sidoarjo Kota'],
                'delivery_fee' => 35000,
                'min_order' => 200000,
                'available_slots' => ['09:00 - 12:00', '13:00 - 16:00', '16:00 - 19:00'],
                'status' => 'active',
            ],
            [
                'name' => 'Zona 8 — Bandung & Kota Lainnya di Indonesia',
                'areas' => ['Bandung', 'Cimahi', 'Semarang', 'Yogyakarta', 'Malang', 'Bali', 'Medan', 'Makassar', 'Seluruh Indonesia', 'Lainnya'],
                'delivery_fee' => 50000,
                'min_order' => 250000,
                'available_slots' => ['10:00 - 16:00', '13:00 - 18:00'],
                'status' => 'active',
            ],
        ];

        foreach ($zones as $zone) {
            DeliveryZone::updateOrCreate(
                ['name' => $zone['name']],
                $zone
            );
        }
    }
}
