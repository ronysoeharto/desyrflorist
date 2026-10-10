<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomBouquetOptionSeeder;
use Database\Seeders\DeliveryZoneSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\StoreSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTrackingResiTest extends TestCase
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

    public function test_order_creation_has_unique_tracking_number()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => Order::generateOrderNumber(),
            'tracking_number' => Order::generateTrackingNumber(),
            'subtotal' => 150000,
            'discount' => 0,
            'delivery_fee' => 20000,
            'total' => 170000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'recipient_name' => 'Adelia Putri',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Jl. Gubeng Kertajaya No. 12, Surabaya',
            'delivery_date' => now()->addDay()->toDateString(),
            'delivery_slot' => '09:00 - 12:00',
        ]);

        $this->assertNotNull($order->tracking_number);
        $this->assertStringStartsWith('DF-RESI-', $order->tracking_number);
    }

    public function test_customer_can_track_order_via_order_number_and_via_resi()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'DSY-20261010-TEST1',
            'tracking_number' => 'DF-RESI-20261010-TEST1',
            'subtotal' => 150000,
            'discount' => 0,
            'delivery_fee' => 20000,
            'total' => 170000,
            'status' => 'processing',
            'payment_status' => 'paid',
            'recipient_name' => 'Bunga Cantik',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Jl. Dharmahusada Indah No. 5, Surabaya',
            'delivery_date' => now()->addDay()->toDateString(),
            'delivery_slot' => '13:00 - 16:00',
        ]);

        // 1. Track by order number
        $responseOrder = $this->get(route('checkout.tracking', ['orderNumber' => $order->order_number]));
        $responseOrder->assertStatus(200);
        $responseOrder->assertSee($order->order_number);
        $responseOrder->assertSee($order->tracking_number);

        // 2. Track by resi (tracking number)
        $responseResi = $this->get(route('checkout.tracking', ['orderNumber' => $order->tracking_number]));
        $responseResi->assertStatus(200);
        $responseResi->assertSee($order->order_number);
        $responseResi->assertSee($order->tracking_number);

        // 3. Track JSON endpoint
        $responseJson = $this->getJson(route('checkout.tracking', ['orderNumber' => $order->tracking_number]));
        $responseJson->assertStatus(200);
        $responseJson->assertJsonFragment([
            'order_number' => $order->order_number,
            'tracking_number' => $order->tracking_number,
            'order_status' => 'processing',
        ]);
    }

    public function test_admin_can_view_order_update_resi_and_print_resi()
    {
        $admin = User::where('role', 'admin')->first();
        $customer = User::factory()->create(['role' => 'customer']);

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'DSY-20261010-ADMIN1',
            'tracking_number' => 'DF-RESI-20261010-ADM1',
            'subtotal' => 200000,
            'discount' => 0,
            'delivery_fee' => 25000,
            'total' => 225000,
            'status' => 'ready',
            'payment_status' => 'paid',
            'recipient_name' => 'Citra Kirana',
            'recipient_phone' => '081999888777',
            'delivery_address' => 'Jl. Pemuda No. 1, Surabaya',
            'delivery_date' => now()->addDay()->toDateString(),
            'delivery_slot' => '09:00 - 12:00',
        ]);

        // 1. Admin order show page displays resi
        $showResponse = $this->actingAs($admin)->get(route('admin.orders.show', $order->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($order->tracking_number);
        $showResponse->assertSee('Nomor Resi Resmi');

        // 2. Admin updates resi custom
        $updateResponse = $this->actingAs($admin)->put(route('admin.orders.tracking-number', $order->id), [
            'tracking_number' => 'DF-RESI-CUSTOM-999',
        ]);
        $updateResponse->assertSessionHas('success');
        $this->assertEquals('DF-RESI-CUSTOM-999', $order->fresh()->tracking_number);

        // 3. Admin regenerates resi automatically
        $generateResponse = $this->actingAs($admin)->put(route('admin.orders.tracking-number', $order->id), [
            'action' => 'generate',
        ]);
        $generateResponse->assertSessionHas('success');
        $this->assertNotEquals('DF-RESI-CUSTOM-999', $order->fresh()->tracking_number);

        // 4. Admin accesses printable resi page
        $printResponse = $this->actingAs($admin)->get(route('admin.orders.print-resi', $order->id));
        $printResponse->assertStatus(200);
        $printResponse->assertSee('RESI PENGIRIMAN RESMI');
        $printResponse->assertSee($order->fresh()->tracking_number);
        $printResponse->assertSee('Citra Kirana');
    }

    public function test_customer_can_see_resi_and_lacak_in_order_history_and_detail()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'DSY-20261010-CUST1',
            'tracking_number' => 'DF-RESI-20261010-CUST1',
            'subtotal' => 180000,
            'discount' => 0,
            'delivery_fee' => 20000,
            'total' => 200000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'recipient_name' => 'Dewi Sartika',
            'recipient_phone' => '081233445566',
            'delivery_address' => 'Jl. Manyar Kertoarjo No. 8, Surabaya',
            'delivery_date' => now()->addDay()->toDateString(),
            'delivery_slot' => '13:00 - 16:00',
        ]);

        // Order history
        $ordersResponse = $this->actingAs($customer)->get(route('account.orders'));
        $ordersResponse->assertStatus(200);
        $ordersResponse->assertSee('Resi: DF-RESI-20261010-CUST1');
        $ordersResponse->assertSee('Lacak');

        // Order detail
        $detailResponse = $this->actingAs($customer)->get(route('account.orders.detail', $order->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('DF-RESI-20261010-CUST1');
        $detailResponse->assertSee('Lacak Pengiriman Bunga');
    }

    public function test_courier_must_pick_up_flowers_at_store_before_delivering()
    {
        $courier = User::where('role', 'courier')->first();
        $customer = User::factory()->create(['role' => 'customer']);

        $order = Order::create([
            'user_id' => $customer->id,
            'courier_id' => $courier->id,
            'order_number' => 'DSY-20261010-COUR1',
            'tracking_number' => 'DF-RESI-20261010-COUR1',
            'subtotal' => 150000,
            'discount' => 0,
            'delivery_fee' => 20000,
            'total' => 170000,
            'status' => 'assigned',
            'payment_status' => 'paid',
            'recipient_name' => 'Rina Sasmita',
            'recipient_phone' => '081299988811',
            'delivery_address' => 'Jl. Rungkut Asri No. 10, Surabaya',
            'delivery_date' => now()->addDay()->toDateString(),
            'delivery_slot' => '09:00 - 12:00',
        ]);

        $delivery = \App\Models\Delivery::create([
            'order_id' => $order->id,
            'courier_id' => $courier->id,
            'status' => 'assigned',
        ]);

        // 1. Courier views delivery detail: shows Ambil Bunga di Toko, Mulai Antar is locked
        $pageResponse = $this->actingAs($courier)->get(route('courier.deliveries.show', $delivery->id));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Saya Sudah Ambil Bunga di Toko');
        $pageResponse->assertSee('Langkah 1: Wajib Ambil Bunga di Toko Terlebih Dahulu');

        // 2. Courier tries to skip and directly change status to on_delivery -> should be rejected!
        $skipResponse = $this->actingAs($courier)->put(route('courier.deliveries.status', $delivery->id), [
            'status' => 'on_delivery',
        ]);
        $skipResponse->assertSessionHas('error');
        $this->assertEquals('assigned', $delivery->fresh()->status);

        // 3. Courier correctly clicks 'picked_up'
        $pickupResponse = $this->actingAs($courier)->put(route('courier.deliveries.status', $delivery->id), [
            'status' => 'picked_up',
        ]);
        $pickupResponse->assertSessionHas('success');
        $this->assertEquals('picked_up', $delivery->fresh()->status);
        $this->assertEquals('picked_up', $order->fresh()->status);
        $this->assertNotNull($delivery->fresh()->picked_up_at);

        // 4. Now courier views page again: shows Mulai Antar Bunga
        $pageAfterPickup = $this->actingAs($courier)->get(route('courier.deliveries.show', $delivery->id));
        $pageAfterPickup->assertStatus(200);
        $pageAfterPickup->assertSee('Mulai Antar Bunga ke Penerima Sekarang');

        // 5. Courier starts delivery (on_delivery) -> success!
        $deliveryResponse = $this->actingAs($courier)->put(route('courier.deliveries.status', $delivery->id), [
            'status' => 'on_delivery',
        ]);
        $deliveryResponse->assertSessionHas('success');
        $this->assertEquals('on_delivery', $delivery->fresh()->status);
        $this->assertEquals('on_delivery', $order->fresh()->status);
    }
}
