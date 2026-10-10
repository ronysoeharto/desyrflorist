<?php

namespace Tests\Feature;

use App\Models\CustomBouquetOption;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomBouquetOptionSeeder;
use Database\Seeders\DeliveryZoneSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\StoreSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FloristShopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            AdminSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            DeliveryZoneSeeder::class,
            CustomBouquetOptionSeeder::class,
            StoreSettingSeeder::class,
        ]);
    }

    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Desy Florist');
        $response->assertSee('Katalog');
    }

    public function test_catalog_and_category_pages_load()
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('Koleksi Rangkaian Bunga');

        $responseCat = $this->get('/categories/hand-bouquet');
        $responseCat->assertStatus(200);
        $responseCat->assertSee('Hand Bouquet');
    }

    public function test_product_detail_page_loads()
    {
        $product = Product::first();
        $response = $this->get('/products/'.$product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_cart_add_and_view()
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();

        $response = $this->actingAs($customer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
            'card_message' => 'Happy Birthday!',
        ]);

        $response->assertRedirect('/cart');

        $cartResponse = $this->actingAs($customer)->get('/cart');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee($product->name);
    }

    public function test_custom_bouquet_builder_and_add_to_cart()
    {
        $customer = User::where('role', 'customer')->first();
        $flower = CustomBouquetOption::where('type', 'flower')->first();
        $wrapping = CustomBouquetOption::where('type', 'wrapping')->first();
        $ribbon = CustomBouquetOption::where('type', 'ribbon')->first();

        $response = $this->actingAs($customer)->post('/custom-bouquet', [
            'flowers' => [
                ['id' => $flower->id, 'qty' => 5],
            ],
            'wrapping_id' => $wrapping->id,
            'ribbon_id' => $ribbon->id,
            'card_to' => 'Adinda',
            'card_from' => 'Bima',
            'card_message' => 'Semoga lekas sembuh dan ceria selalu!',
        ]);

        $response->assertRedirect('/cart');

        $cartResponse = $this->actingAs($customer)->get('/cart');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('Custom Hand-Crafted Artisan Bouquet');
    }

    public function test_checkout_process_creates_order_and_tracking()
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();
        $zone = DeliveryZone::first();

        // Add item to cart
        $this->actingAs($customer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Process checkout
        $response = $this->actingAs($customer)->post('/checkout', [
            'recipient_name' => 'Maya Anggraini',
            'recipient_phone' => '081234567890',
            'delivery_zone_id' => $zone->id,
            'delivery_address' => 'Jl. Senopati No. 10, Jakarta Selatan',
            'delivery_date' => date('Y-m-d'),
            'delivery_slot' => '13:00 - 16:00',
            'delivery_notes' => 'Pagar putih, samping coffee shop',
            'greeting_message' => 'Selamat atas kelulusanmu!',
            'payment_method' => 'bca',
        ]);

        $this->assertDatabaseHas('orders', [
            'recipient_name' => 'Maya Anggraini',
        ]);

        $order = Order::where('recipient_name', 'Maya Anggraini')->first();
        $this->assertNotNull($order);

        $response->assertRedirect(route('checkout.success', $order->order_number));

        // Test tracking
        $trackingResponse = $this->get(route('checkout.tracking', $order->order_number));
        $trackingResponse->assertStatus(200);
        $trackingResponse->assertSee($order->order_number);
    }

    public function test_customer_registration_and_login()
    {
        $response = $this->post('/register', [
            'role' => 'customer',
            'name' => 'Bella Swan',
            'email' => 'bella@example.com',
            'phone' => '081299998888',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_courier_registration_status_is_pending()
    {
        $response = $this->post('/register', [
            'role' => 'courier',
            'name' => 'Dedi Kurir Baru',
            'email' => 'dedi.kurir@example.com',
            'phone' => '081288887777',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'identity_number' => '3171012399887766',
            'vehicle_type' => 'motorcycle',
            'vehicle_number' => 'Yamaha NMAX 155',
            'plate_number' => 'B 5555 DED',
            'address' => 'Jl. Fatmawati No. 8, Cilandak',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => 'dedi.kurir@example.com',
            'role' => 'courier',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('courier_profiles', [
            'identity_number' => '3171012399887766',
            'status' => 'pending',
        ]);
    }

    public function test_admin_dashboard_access_and_authorization()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Studio');

        // Normal customer cannot access admin
        $customer = User::where('role', 'customer')->first();
        $forbiddenResponse = $this->actingAs($customer)->get('/admin');
        $forbiddenResponse->assertStatus(403);
    }

    public function test_approved_courier_can_access_courier_portal()
    {
        $courier = User::where('role', 'courier')->where('status', 'active')->first();

        $response = $this->actingAs($courier)->get('/courier');
        $response->assertStatus(200);
        $response->assertSee('Halo, '.$courier->name);
    }

    public function test_admin_order_detail_view_loads_correctly()
    {
        $admin = User::where('role', 'admin')->first();
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();
        $zone = DeliveryZone::first();

        $this->actingAs($customer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($customer)->post('/checkout', [
            'recipient_name' => 'Maya Anggraini',
            'recipient_phone' => '081234567890',
            'delivery_zone_id' => $zone->id,
            'delivery_address' => 'Jl. Senopati No. 10, Jakarta Selatan',
            'delivery_date' => date('Y-m-d'),
            'delivery_slot' => '13:00 - 16:00',
            'payment_method' => 'bca',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);

        $response = $this->actingAs($admin)->get('/admin/orders/'.$order->id);
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee('Rangkaian Bunga Pesanan');
    }

    public function test_payment_proof_upload_admin_rejection_and_customer_reupload_cycle()
    {
        Storage::fake('public');

        $admin = User::where('role', 'admin')->first();
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();
        $zone = DeliveryZone::first();

        // 1. Customer checks out
        $this->actingAs($customer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($customer)->post('/checkout', [
            'recipient_name' => 'Adinda Putri',
            'recipient_phone' => '081299998888',
            'delivery_zone_id' => $zone->id,
            'delivery_address' => 'Jl. Kemang Raya No. 12',
            'delivery_date' => date('Y-m-d'),
            'delivery_slot' => '09:00 - 12:00',
            'payment_method' => 'bca',
        ]);

        $order = Order::latest('id')->first();
        $this->assertEquals('pending', $order->payment_status);
        $this->assertEquals('pending', $order->status);

        // 2. Customer uploads payment proof
        $file1 = UploadedFile::fake()->create('bukti_transfer_1.jpg', 300, 'image/jpeg');
        $uploadResponse = $this->actingAs($customer)->post('/checkout/payment-proof/'.$order->order_number, [
            'proof' => $file1,
            'customer_notes' => 'Transfer via BCA Mobile a.n Adinda',
        ]);

        $uploadResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('verifying', $order->payment_status);
        $this->assertNotNull($order->payment->proof_image);
        Storage::disk('public')->assertExists($order->payment->proof_image);

        // 3. Admin views order and rejects payment proof with reason
        $adminViewResponse = $this->actingAs($admin)->get('/admin/orders/'.$order->id);
        $adminViewResponse->assertStatus(200);
        $adminViewResponse->assertSee('Bukti Transfer Pelanggan');
        $adminViewResponse->assertSee('Setujui Pembayaran');
        $adminViewResponse->assertSee('Tolak Bukti');

        $rejectionReason = 'Nominal transfer kurang Rp 50.000 dari total tagihan.';
        $rejectResponse = $this->actingAs($admin)->post('/admin/orders/'.$order->id.'/payment/reject', [
            'rejection_reason' => $rejectionReason,
        ]);

        $rejectResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('rejected', $order->payment_status);
        $this->assertEquals($rejectionReason, $order->payment->rejection_reason);

        // 4. Customer views rejection reason in order tracking & success pages
        $customerSuccessView = $this->actingAs($customer)->get('/checkout/success/'.$order->order_number);
        $customerSuccessView->assertStatus(200);
        $customerSuccessView->assertSee('Bukti Pembayaran Ditolak — Mohon Unggah Ulang');
        $customerSuccessView->assertSee($rejectionReason);

        // 5. Customer re-uploads new valid payment proof
        $file2 = UploadedFile::fake()->create('bukti_transfer_2_lunas.png', 400, 'image/png');
        $reuploadResponse = $this->actingAs($customer)->post('/checkout/payment-proof/'.$order->order_number, [
            'proof' => $file2,
            'customer_notes' => 'Sudah transfer kekurangan Rp 50.000',
        ]);

        $reuploadResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('verifying', $order->payment_status);
        $this->assertNull($order->payment->rejection_reason);
        Storage::disk('public')->assertExists($order->payment->proof_image);

        // 6. Admin approves the re-uploaded payment proof
        $approveResponse = $this->actingAs($admin)->post('/admin/orders/'.$order->id.'/payment/approve');
        $approveResponse->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('confirmed', $order->status);
        $this->assertNotNull($order->paid_at);
        $this->assertTrue($order->isPaid());

        // 7. Customer sees approved status
        $finalView = $this->actingAs($customer)->get('/account/orders/'.$order->id);
        $finalView->assertStatus(200);
        $finalView->assertSee('Pembayaran Berhasil Disetujui');
    }

    public function test_checkout_with_structured_shopee_style_address_and_manual_house_point()
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();
        $zone = DeliveryZone::where('name', 'like', '%Surabaya%')->first() ?? DeliveryZone::first();

        // 1. Customer adds item to cart
        $this->actingAs($customer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // 2. View checkout page and verify Shopee-style address components exist
        $checkoutPageResponse = $this->actingAs($customer)->get('/checkout');
        $checkoutPageResponse->assertStatus(200);
        $checkoutPageResponse->assertSee('Wilayah Pengiriman', false);
        $checkoutPageResponse->assertSee('Titik Tujuan Rumah', false);
        $checkoutPageResponse->assertSee('Catatan Patokan', false);
        $checkoutPageResponse->assertSee('Pratinjau Alamat Lengkap Kurir:', false);

        // 3. Process checkout with structured location (Surabaya - Bubutan) & manual house point
        $response = $this->actingAs($customer)->post('/checkout', [
            'recipient_name' => 'Clara Maharani',
            'recipient_phone' => '081288776655',
            'province' => 'Jawa Timur',
            'city' => 'Kota Surabaya',
            'district' => 'Bubutan',
            'postal_code' => '60174',
            'street_address' => 'Jl. Bubutan No. 45, RT 02 / RW 03',
            'delivery_address' => 'Jl. Bubutan No. 45, RT 02 / RW 03, Kel. Bubutan, Kec. Bubutan, Kota Surabaya, Jawa Timur 60174',
            'delivery_notes' => 'Pagar hitam depan pos satpam, seberang masjid',
            'delivery_zone_id' => $zone->id,
            'delivery_date' => date('Y-m-d'),
            'delivery_slot' => '13:00 - 16:00',
            'payment_method' => 'bca',
        ]);

        $order = Order::latest('id')->first();
        $this->assertNotNull($order);
        $response->assertRedirect('/checkout/success/'.$order->order_number);

        // 4. Verify order structured address fields
        $this->assertEquals('Kota Surabaya', $order->delivery_city);
        $this->assertEquals('Bubutan', $order->delivery_district);
        $this->assertEquals('60174', $order->delivery_postal_code);
        $this->assertStringContainsString('Jl. Bubutan No. 45', $order->delivery_address);
        $this->assertEquals('Pagar hitam depan pos satpam, seberang masjid', $order->delivery_notes);

        // 5. Success page shows complete combined address
        $successResponse = $this->actingAs($customer)->get('/checkout/success/'.$order->order_number);
        $successResponse->assertStatus(200);
        $successResponse->assertSee('Jl. Bubutan No. 45');
    }

    public function test_store_location_updates_in_admin_and_reflects_in_frontend_and_maps_url()
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Admin updates store location and coordinates
        $newAddress = 'Jl. Kayoon No. 42, Sentra Bunga Surabaya Pusat';
        $newLat = '-7.2679543';
        $newLng = '112.7490163';

        $response = $this->actingAs($admin)->post('/admin/settings/profile', [
            'store_name' => 'Desy Florist Surabaya',
            'store_tagline' => 'Artisan Floral Studio Surabaya',
            'store_city' => 'Surabaya',
            'store_district' => 'Surabaya Pusat',
            'store_province' => 'Jawa Timur',
            'store_timezone' => 'Asia/Jakarta',
            'store_address' => $newAddress,
            'store_latitude' => $newLat,
            'store_longitude' => $newLng,
            'store_whatsapp' => '082139379038',
            'store_email' => 'desyflorist58@gmail.com',
        ]);

        $response->assertSessionHas('success');

        // 2. Front-end homepage shows updated address and Google Maps link, not the old Senopati address
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($newAddress);
        $homeResponse->assertSee("https://www.google.com/maps?q={$newLat},{$newLng}", false);
        $homeResponse->assertDontSee('Jl. Senopati No. 88');

        // 3. Contact page also shows updated address, interactive map, and Google Maps link
        $contactResponse = $this->get('/contact');
        $contactResponse->assertStatus(200);
        $contactResponse->assertSee($newAddress);
        $contactResponse->assertSee("https://www.google.com/maps?q={$newLat},{$newLng}", false);
        $contactResponse->assertDontSee('Jl. Senopati No. 88');
    }

    public function test_checkout_map_syncs_with_distant_regions_properly()
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();
        $this->actingAs($customer)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $response = $this->actingAs($customer)->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('resolveRegionCoordinates');
        $response->assertSee('triggerLiveGeocode');
        $response->assertSee('KNOWN_REGION_COORDS');
    }

    public function test_admin_can_manage_about_us_content_and_view_public_page()
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Admin accesses About Us settings page
        $pageResponse = $this->actingAs($admin)->get('/admin/settings/about');
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Konten Halaman Tentang Kami');

        // 2. Admin updates About Us content
        $updateResponse = $this->actingAs($admin)->post('/admin/settings/about', [
            'about_tagline' => 'Artisan Florist Terbaik Surabaya',
            'about_title' => 'Kisah Perjalanan Desy Florist 2026',
            'about_subtitle' => 'Merangkai senyum di setiap momen istimewa Anda.',
            'about_story_title' => 'Filosofi Dan Visi Kami',
            'about_story_content' => 'Bunga adalah karya seni alami yang menyampaikan kehangatan cinta.',
            'about_val1_title' => 'Jaminan Bunga Segar',
            'about_val1_desc' => 'Dipetik harian dari kebun bunga terbaik.',
            'about_val2_title' => 'Karya Florist Handal',
            'about_val2_desc' => 'Keahlian wrapping internasional.',
            'about_val3_title' => 'Pengiriman Cepat Amati',
            'about_val3_desc' => 'Garansi tiba tepat waktu.',
        ]);

        $updateResponse->assertSessionHas('success');

        // 3. Public About page displays updated contents
        $publicResponse = $this->get('/about');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Artisan Florist Terbaik Surabaya');
        $publicResponse->assertSee('Kisah Perjalanan Desy Florist 2026');
        $publicResponse->assertSee('Filosofi Dan Visi Kami');
        $publicResponse->assertSee('Jaminan Bunga Segar');
    }

    public function test_guest_redirected_to_login_when_accessing_checkout()
    {
        // Unauthenticated guest tries to visit checkout
        $response = $this->get('/checkout');
        $response->assertRedirect(route('login'));

        // Unauthenticated guest tries to process checkout
        $postResponse = $this->post('/checkout', [
            'recipient_name' => 'Budi',
        ]);
        $postResponse->assertRedirect(route('login'));
    }

    public function test_courier_user_is_restricted_to_courier_pages()
    {
        $courier = User::where('role', 'courier')->first();

        // Courier attempts to visit public home / shop / cart page -> redirected to courier dashboard
        $homeResponse = $this->actingAs($courier)->get('/');
        $homeResponse->assertRedirect(route('courier.dashboard'));

        $shopResponse = $this->actingAs($courier)->get('/shop');
        $shopResponse->assertRedirect(route('courier.dashboard'));

        // Courier accesses courier dashboard -> allowed 200 OK
        $courierDashboard = $this->actingAs($courier)->get('/courier');
        $courierDashboard->assertStatus(200);
    }
}
