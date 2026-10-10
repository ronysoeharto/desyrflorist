<?php

namespace Database\Seeders;

use App\Models\CourierProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@desyflorist.com'],
            [
                'name' => 'Desy Admin',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Courier (Approved)
        $courier = User::updateOrCreate(
            ['email' => 'courier@desyflorist.com'],
            [
                'name' => 'Budi Santoso',
                'phone' => '081298765432',
                'password' => Hash::make('password'),
                'role' => 'courier',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        CourierProfile::updateOrCreate(
            ['user_id' => $courier->id],
            [
                'identity_number' => '3171012345678901',
                'vehicle_type' => 'motorcycle',
                'vehicle_number' => 'Honda Vario 160',
                'plate_number' => 'B 4123 DSY',
                'address' => 'Jl. Kebon Jeruk No. 12, Jakarta Barat',
                'status' => 'approved',
                'approved_at' => now(),
            ]
        );

        // Courier (Pending)
        $pendingCourier = User::updateOrCreate(
            ['email' => 'agus.kurir@desyflorist.com'],
            [
                'name' => 'Agus Prasetyo',
                'phone' => '081311223344',
                'password' => Hash::make('password'),
                'role' => 'courier',
                'status' => 'pending',
                'email_verified_at' => now(),
            ]
        );

        CourierProfile::updateOrCreate(
            ['user_id' => $pendingCourier->id],
            [
                'identity_number' => '3171098765432101',
                'vehicle_type' => 'car',
                'vehicle_number' => 'Toyota Avanza (Bunga Papan / Standing)',
                'plate_number' => 'B 9876 AGP',
                'address' => 'Jl. Tebet Barat Dalam No. 45, Jakarta Selatan',
                'status' => 'pending',
            ]
        );

        // Customer
        User::updateOrCreate(
            ['email' => 'customer@desyflorist.com'],
            [
                'name' => 'Clara Maharani',
                'phone' => '081288776655',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}
