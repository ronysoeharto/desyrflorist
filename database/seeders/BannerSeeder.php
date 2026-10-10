<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Sentuhan Bunga Segar, Dirangkai Tulus untuk Momen Berharga Anda.',
                'subtitle' => 'Artisan Florist & Same-Day Delivery Jakarta',
                'description' => 'Dari buket tangan romantis bernuansa pastel hingga bunga meja berkelas. Setiap tangkai dipilih langsung dari kebun terbaik dan dirangkai penuh keahlian oleh floris kami.',
                'image_path' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=1000&q=80',
                'link_url' => route('shop.index', [], false),
                'button_text' => 'Jelajahi Katalog Bunga',
                'secondary_button_text' => '✨ Rangkai Buket Sendiri',
                'secondary_button_url' => route('custom-bouquet.index', [], false),
                'badge_text' => '"Bunganya sangat segar dan wangi, kemasannya rapi sekali!"',
                'badge_author' => '— Alika, Jakarta Selatan',
                'sort_order' => 1,
                'status' => 'active',
            ]
        );
    }
}
