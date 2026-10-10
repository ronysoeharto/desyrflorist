<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hand Bouquet',
                'description' => 'Rangkaian buket tangan cantik dibalut kertas premium elegan, cocok untuk ulang tahun, wisuda, dan anniversary.',
                'image' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Table Flower',
                'description' => 'Bunga meja segar dalam vas keramik atau kaca mewah, memberi kesegaran dan sentuhan hangat untuk ruangan Anda.',
                'image' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Bloom Box',
                'description' => 'Rangkaian mawar dan bunga tropis dalam kotak beludru bundar yang menawan dan berkelas.',
                'image' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Standing Flower',
                'description' => 'Rangkaian bunga standing megah dan elegan untuk ucapan selamat pernikahan, grand opening, atau duka cita terhormat.',
                'image' => 'https://images.unsplash.com/photo-1567696911980-2eed69a46042?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Bunga Papan',
                'description' => 'Karangan bunga papan bernuansa artistik dan rapi untuk ucapan selamat, wisuda, peresmian, hingga duka cita.',
                'image' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Anggrek Bulan',
                'description' => 'Pesona anggrek bulan Phalaenopsis pilihan berkualitas tinggi yang tahan lama dan berkelas.',
                'image' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 6,
                'status' => 'active',
            ],
            [
                'name' => 'Wedding & Event',
                'description' => 'Buket pengantin intim, boutonniere, hingga dekorasi pelaminan modern dengan sentuhan floral premium.',
                'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 7,
                'status' => 'active',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'image' => $cat['image'],
                    'sort_order' => $cat['sort_order'],
                    'status' => $cat['status'],
                ]
            );
        }
    }
}
