<?php

namespace Tests\Feature;

use App\Models\Banner;
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

class BannerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

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

        $this->admin = User::where('email', 'admin@desyflorist.com')->first();
        $this->customer = User::factory()->create([
            'role' => 'customer',
        ]);
    }

    public function test_homepage_displays_active_banner(): void
    {
        Banner::create([
            'title' => 'Bunga Spesial Musim Semi',
            'subtitle' => 'Edisi Terbatas',
            'description' => 'Koleksi tulip dan mawar segar.',
            'image_path' => 'https://images.unsplash.com/photo-spring-flowers',
            'link_url' => '/shop',
            'status' => 'active',
            'sort_order' => 1,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Bunga Spesial Musim Semi');
        $response->assertSee('Edisi Terbatas');
        $response->assertSee('https://images.unsplash.com/photo-spring-flowers');
    }

    public function test_admin_can_view_banner_management_page(): void
    {
        $banner = Banner::create([
            'title' => 'Buket Elegan Desy',
            'image_path' => 'https://images.unsplash.com/photo-sample',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.banners.index'));

        $response->assertStatus(200);
        $response->assertSee('Banner Beranda');
        $response->assertSee('Buket Elegan Desy');
    }

    public function test_guest_or_customer_cannot_access_admin_banners(): void
    {
        $guestResponse = $this->get(route('admin.banners.index'));
        $guestResponse->assertRedirect(route('login'));

        $customerResponse = $this->actingAs($this->customer)->get(route('admin.banners.index'));
        $customerResponse->assertStatus(403);
    }

    public function test_admin_can_create_banner_with_url(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.banners.store'), [
            'title' => 'Promo Spesial Imlek',
            'subtitle' => 'Koleksi Anggrek & Mawar',
            'description' => 'Bawa keberuntungan dengan rangkaian bunga segar.',
            'image_url' => 'https://images.unsplash.com/photo-imlek',
            'link_url' => '/shop',
            'button_text' => 'Beli Sekarang',
            'secondary_button_text' => 'Rangkai Sendiri',
            'secondary_button_url' => '/custom-bouquet',
            'badge_text' => 'Bunganya tahan lama dan indah sekali!',
            'badge_author' => '— Citra, Jakarta',
            'sort_order' => 2,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseHas('banners', [
            'title' => 'Promo Spesial Imlek',
            'image_path' => 'https://images.unsplash.com/photo-imlek',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_create_banner_with_file_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('hero-banner.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admin.banners.store'), [
            'title' => 'Banner Unggahan Baru',
            'image_file' => $file,
            'status' => 'active',
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.banners.index'));

        $banner = Banner::where('title', 'Banner Unggahan Baru')->first();
        $this->assertNotNull($banner);
        $this->assertStringStartsWith('banners/', $banner->image_path);
        Storage::disk('public')->assertExists($banner->image_path);
    }

    public function test_admin_can_update_banner(): void
    {
        $banner = Banner::create([
            'title' => 'Banner Lama',
            'image_path' => 'https://images.unsplash.com/old-banner',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.banners.update', $banner->id), [
            'title' => 'Banner Diperbarui',
            'image_url' => 'https://images.unsplash.com/new-banner',
            'status' => 'inactive',
            'sort_order' => 5,
        ]);

        $response->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'title' => 'Banner Diperbarui',
            'image_path' => 'https://images.unsplash.com/new-banner',
            'status' => 'inactive',
            'sort_order' => 5,
        ]);
    }

    public function test_admin_can_toggle_banner_status(): void
    {
        $banner = Banner::create([
            'title' => 'Banner Toggle Test',
            'image_path' => 'https://images.unsplash.com/test-toggle',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.banners.toggle', $banner->id));
        $response->assertSessionHas('success');

        $this->assertEquals('inactive', $banner->fresh()->status);

        $this->actingAs($this->admin)->post(route('admin.banners.toggle', $banner->id));
        $this->assertEquals('active', $banner->fresh()->status);
    }

    public function test_admin_can_delete_banner(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('to-delete.jpg', 200, 'image/jpeg');
        $path = $file->store('banners', 'public');

        $banner = Banner::create([
            'title' => 'Banner Hapus',
            'image_path' => $path,
            'status' => 'active',
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete(route('admin.banners.destroy', $banner->id));
        $response->assertRedirect(route('admin.banners.index'));

        $this->assertDatabaseMissing('banners', ['id' => $banner->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
