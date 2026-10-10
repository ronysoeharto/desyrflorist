<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'store_name' => 'Desy Florist',
            'store_tagline' => 'Artisan Floral Studio & Same Day Delivery',
            'store_description' => 'Desy Florist menghadirkan rangkaian bunga segar premium, buket pengantin, bunga meja, dan karangan bunga elegan dengan dedikasi tinggi terhadap seni merangkai dan kepuasan Anda.',
            'store_address' => 'Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan, 12190',
            'store_phone' => '+62 21 5566 7788',
            'store_whatsapp' => '6281234567890',
            'store_email' => 'hello@desyflorist.com',
            'store_instagram' => 'desyflorist.official',
            'store_open_hours' => 'Senin - Minggu: 07.30 - 21.00 WIB',
            'same_day_cutoff' => '16:00',
            'free_shipping_min' => '750000',
        ];

        foreach ($settings as $key => $value) {
            StoreSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Coupons
        $coupons = [
            [
                'code' => 'DESYWELCOME',
                'discount_type' => 'percentage',
                'discount_amount' => 10,
                'min_purchase' => 300000,
                'max_discount' => 50000,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addMonths(6),
                'usage_limit' => 500,
                'used_count' => 12,
                'status' => 'active',
            ],
            [
                'code' => 'BLOOM50K',
                'discount_type' => 'fixed',
                'discount_amount' => 50000,
                'min_purchase' => 500000,
                'max_discount' => 50000,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addMonths(3),
                'usage_limit' => 200,
                'used_count' => 8,
                'status' => 'active',
            ],
        ];

        foreach ($coupons as $cp) {
            Coupon::updateOrCreate(
                ['code' => $cp['code']],
                $cp
            );
        }
    }
}
