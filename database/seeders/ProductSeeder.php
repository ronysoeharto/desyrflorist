<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $handBouquet = Category::where('slug', 'hand-bouquet')->first();
        $tableFlower = Category::where('slug', 'table-flower')->first();
        $bloomBox = Category::where('slug', 'bloom-box')->first();
        $standingFlower = Category::where('slug', 'standing-flower')->first();
        $bungaPapan = Category::where('slug', 'bunga-papan')->first();
        $anggrek = Category::where('slug', 'anggrek-bulan')->first();
        $wedding = Category::where('slug', 'wedding-event')->first();

        $products = [
            // Hand Bouquets
            [
                'category_id' => $handBouquet->id,
                'name' => 'Pastel Peony & Dusty Rose Reverie',
                'description' => 'Kombinasi romantis antara mawar Ecuador dusty pink, peony pastel, eucalyptus segar, dan baby breath putih lembut. Dibalut dalam wrapping kertas matte dusty rose dengan pita satin ganda.',
                'price' => 485000,
                'discount_price' => 450000,
                'sku' => 'HB-REV-01',
                'stock' => 25,
                'is_featured' => true,
                'is_best_seller' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [
                    ['type' => 'size', 'name' => 'Ukuran Buket', 'value' => 'Reguler (10 Tangkai Mawar)', 'price_adjustment' => 0, 'stock' => 15],
                    ['type' => 'size', 'name' => 'Ukuran Buket', 'value' => 'Deluxe (20 Tangkai Mawar)', 'price_adjustment' => 250000, 'stock' => 10],
                    ['type' => 'wrapping', 'name' => 'Warna Kertas', 'value' => 'Dusty Pink Matte', 'price_adjustment' => 0, 'stock' => 20],
                    ['type' => 'wrapping', 'name' => 'Warna Kertas', 'value' => 'Warm Ivory Linen', 'price_adjustment' => 15000, 'stock' => 10],
                ],
            ],
            [
                'name' => 'Blushing Sunset Lily Bouquet',
                'category_id' => $handBouquet->id,
                'description' => 'Buket elegan yang memancarkan pesona senja dengan Asiatic Lilies putih, mawar peach champagne, dan sentuhan foliage hijau lembut.',
                'price' => 520000,
                'discount_price' => null,
                'sku' => 'HB-BSL-02',
                'stock' => 18,
                'is_featured' => true,
                'is_best_seller' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [
                    ['type' => 'size', 'name' => 'Ukuran', 'value' => 'Standard', 'price_adjustment' => 0, 'stock' => 10],
                    ['type' => 'size', 'name' => 'Ukuran', 'value' => 'Grandeur', 'price_adjustment' => 200000, 'stock' => 8],
                ],
            ],
            [
                'name' => 'Everlasting Cotton & Dried Florals',
                'category_id' => $handBouquet->id,
                'description' => 'Rangkaian bunga abadi dari bunga kapas alami, lavender kering, bunny tails, dan pampa grass bernuansa hangat earth-tone.',
                'price' => 380000,
                'discount_price' => 350000,
                'sku' => 'HB-COT-03',
                'stock' => 30,
                'is_featured' => false,
                'is_best_seller' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [],
            ],
            [
                'name' => 'Graceful Hydrangea Cloud',
                'category_id' => $handBouquet->id,
                'description' => 'Satu kuntum Hydrangea biru pastel raksasa dikelilingi baby breath putih premium dan mawar putih Avalon.',
                'price' => 420000,
                'discount_price' => null,
                'sku' => 'HB-HYD-04',
                'stock' => 12,
                'is_featured' => true,
                'is_best_seller' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [],
            ],

            // Table Flowers
            [
                'name' => 'Serenade in Rose Vase Arrangement',
                'category_id' => $tableFlower->id,
                'description' => 'Rangkaian bunga meja mewah dalam vas keramik bertekstur matte ivory. Berisi mawar merah muda, carnation salem, lisianthus putih, dan dedaunan segar.',
                'price' => 650000,
                'discount_price' => 595000,
                'sku' => 'TF-SRN-01',
                'stock' => 15,
                'is_featured' => true,
                'is_best_seller' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [
                    ['type' => 'vase', 'name' => 'Pilihan Vas', 'value' => 'Keramik Ivory Matte', 'price_adjustment' => 0, 'stock' => 10],
                    ['type' => 'vase', 'name' => 'Pilihan Vas', 'value' => 'Kaca Kristal Fluted', 'price_adjustment' => 45000, 'stock' => 5],
                ],
            ],
            [
                'name' => 'Botanical Meadow Table Centerpiece',
                'category_id' => $tableFlower->id,
                'description' => 'Desain center meja modern bergaya taman liar Inggris dengan aneka ranunculus kuning lembut, delphinium, chamomile, dan eucalyptus.',
                'price' => 750000,
                'discount_price' => null,
                'sku' => 'TF-BOT-02',
                'stock' => 8,
                'is_featured' => false,
                'is_best_seller' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1589244159943-460088ed5c92?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [],
            ],

            // Bloom Box
            [
                'name' => 'Velvet Rose Royale Bloom Box',
                'category_id' => $bloomBox->id,
                'description' => 'Kotak bulat beludru dusty pink berhiaskan pita emas dengan susunan 24 kuntum mawar semi-holland gradasi pink ke putih.',
                'price' => 690000,
                'discount_price' => 620000,
                'sku' => 'BB-VEL-01',
                'stock' => 20,
                'is_featured' => true,
                'is_best_seller' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [
                    ['type' => 'box_color', 'name' => 'Warna Box', 'value' => 'Dusty Pink Velvet', 'price_adjustment' => 0, 'stock' => 12],
                    ['type' => 'box_color', 'name' => 'Warna Box', 'value' => 'Warm Cream Velvet', 'price_adjustment' => 0, 'stock' => 8],
                ],
            ],
            [
                'name' => 'Sweet Macaron & Flower Box',
                'category_id' => $bloomBox->id,
                'description' => 'Kombinasi manis bunga mawar pastel dan 6 buah artisan macaron aneka rasa dalam kotak akrilik transparan berpita emas.',
                'price' => 550000,
                'discount_price' => null,
                'sku' => 'BB-MAC-02',
                'stock' => 10,
                'is_featured' => false,
                'is_best_seller' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1533616688419-b7a585564566?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [],
            ],

            // Standing Flower
            [
                'name' => 'Grandeur Congratulations Standing Flower',
                'category_id' => $standingFlower->id,
                'description' => 'Standing flower 2 tingkat bernuansa warna pastel mewah untuk peresmian kantor, wisuda, atau pernikahan berkesan.',
                'price' => 1250000,
                'discount_price' => 1150000,
                'sku' => 'SF-GRD-01',
                'stock' => 10,
                'is_featured' => true,
                'is_best_seller' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1567696911980-2eed69a46042?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [],
            ],

            // Bunga Papan
            [
                'name' => 'Bunga Papan Rustik & Modern Congratulations',
                'category_id' => $bungaPapan->id,
                'description' => 'Bunga papan kayu modern ukuran 2x1.25m dengan latar kanvas beige dan rangkaian bunga segar elegan di 4 sudut.',
                'price' => 950000,
                'discount_price' => 850000,
                'sku' => 'BP-MOD-01',
                'stock' => 15,
                'is_featured' => false,
                'is_best_seller' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [],
            ],

            // Anggrek Bulan
            [
                'name' => 'Premium White Phalaenopsis Orchid (3 Tangkai)',
                'category_id' => $anggrek->id,
                'description' => 'Anggrek bulan putih premium kelas satu dalam pot keramik minimalis. Tahan mekar hingga 1-2 bulan dengan perawatan mudah.',
                'price' => 850000,
                'discount_price' => 790000,
                'sku' => 'ORC-WHT-01',
                'stock' => 14,
                'is_featured' => true,
                'is_best_seller' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [
                    ['type' => 'branches', 'name' => 'Jumlah Tangkai', 'value' => '2 Tangkai', 'price_adjustment' => -200000, 'stock' => 6],
                    ['type' => 'branches', 'name' => 'Jumlah Tangkai', 'value' => '3 Tangkai', 'price_adjustment' => 0, 'stock' => 8],
                ],
            ],

            // Wedding Collection
            [
                'name' => 'Aura Bridal Bouquet & Boutonniere Set',
                'category_id' => $wedding->id,
                'description' => 'Buket pengantin eksklusif dari mawar David Austin putih, ranunculus blush, dan pita sutra organik panjang yang menjuntai anggun.',
                'price' => 1100000,
                'discount_price' => null,
                'sku' => 'WD-AUR-01',
                'stock' => 5,
                'is_featured' => true,
                'is_best_seller' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [],
            ],
        ];

        foreach ($products as $pData) {
            $images = $pData['images'] ?? [];
            $variations = $pData['variations'] ?? [];
            unset($pData['images'], $pData['variations']);

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($pData['name'])],
                array_merge($pData, ['status' => 'active'])
            );

            // Add images
            ProductImage::where('product_id', $product->id)->delete();
            foreach ($images as $index => $imgPath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgPath,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }

            // Add variations
            ProductVariation::where('product_id', $product->id)->delete();
            foreach ($variations as $var) {
                ProductVariation::create(array_merge($var, ['product_id' => $product->id]));
            }
        }
    }
}
