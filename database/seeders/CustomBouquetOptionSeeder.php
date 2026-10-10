<?php

namespace Database\Seeders;

use App\Models\CustomBouquetOption;
use Illuminate\Database\Seeder;

class CustomBouquetOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            // Flowers
            ['type' => 'flower', 'name' => 'Ecuador Dusty Pink Rose', 'price' => 25000, 'sort_order' => 1, 'image' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'flower', 'name' => 'White Avalanche Rose', 'price' => 22000, 'sort_order' => 2, 'image' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'flower', 'name' => 'Holland Pink Tulip', 'price' => 35000, 'sort_order' => 3, 'image' => 'https://images.unsplash.com/photo-1520763185298-1b434c919102?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'flower', 'name' => 'White Asiatic Lily', 'price' => 45000, 'sort_order' => 4, 'image' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'flower', 'name' => 'Baby\'s Breath Fresh (Per Ikat)', 'price' => 30000, 'sort_order' => 5, 'image' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'flower', 'name' => 'Silver Dollar Eucalyptus Foliage', 'price' => 20000, 'sort_order' => 6, 'image' => 'https://images.unsplash.com/photo-1589244159943-460088ed5c92?auto=format&fit=crop&w=400&q=80'],

            // Wrapping
            ['type' => 'wrapping', 'name' => 'Dusty Pink Korean Matte Paper', 'price' => 35000, 'sort_order' => 1, 'image' => 'https://images.unsplash.com/photo-1572373695272-47df733b1e32?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'wrapping', 'name' => 'Warm Ivory Linen Textured Wrap', 'price' => 40000, 'sort_order' => 2, 'image' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'wrapping', 'name' => 'Classic Kraft Rustic Paper', 'price' => 25000, 'sort_order' => 3, 'image' => 'https://images.unsplash.com/photo-1606822207185-1845ebfc67df?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'wrapping', 'name' => 'Midnight Slate Elegant Paper', 'price' => 35000, 'sort_order' => 4, 'image' => 'https://images.unsplash.com/photo-1542840410-3092f99611a3?auto=format&fit=crop&w=400&q=80'],

            // Ribbon
            ['type' => 'ribbon', 'name' => 'Dusty Rose Premium Double Satin', 'price' => 15000, 'sort_order' => 1, 'image' => 'https://images.unsplash.com/photo-1628148810757-08051786576b?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'ribbon', 'name' => 'Pure White Silk Organza', 'price' => 18000, 'sort_order' => 2, 'image' => 'https://images.unsplash.com/photo-1596796123447-3b2d1dc78b02?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'ribbon', 'name' => 'Muted Gold Metallic Thread Ribbon', 'price' => 20000, 'sort_order' => 3, 'image' => 'https://images.unsplash.com/photo-1579705745124-22b64d4c55d0?auto=format&fit=crop&w=400&q=80'],

            // Add-ons
            ['type' => 'addon', 'name' => 'Custom Hand-Lettered Greeting Card', 'price' => 25000, 'sort_order' => 1, 'image' => 'https://images.unsplash.com/photo-1512141517438-6b83f0653ff9?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'addon', 'name' => 'Ferrero Rocher Box (8 Pcs)', 'price' => 85000, 'sort_order' => 2, 'image' => 'https://images.unsplash.com/photo-1607519393309-8470a6c0b3c2?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'addon', 'name' => 'Mini Teddy Bear Keychain', 'price' => 45000, 'sort_order' => 3, 'image' => 'https://images.unsplash.com/photo-1559454403-b8fb88521f11?auto=format&fit=crop&w=400&q=80'],
            ['type' => 'addon', 'name' => 'Warm White LED Fairy Lights', 'price' => 30000, 'sort_order' => 4, 'image' => 'https://images.unsplash.com/photo-1513689405626-d1838cf3ff96?auto=format&fit=crop&w=400&q=80'],
        ];

        foreach ($options as $opt) {
            CustomBouquetOption::updateOrCreate(
                ['type' => $opt['type'], 'name' => $opt['name']],
                array_merge($opt, ['status' => 'active'])
            );
        }
    }
}
