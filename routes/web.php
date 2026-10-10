<?php

use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CourierController as AdminCourierController;
use App\Http\Controllers\Admin\CustomBouquetOptionController as AdminCustomBouquetOptionController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DeliveryController as AdminDeliveryController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Courier\CourierController;
use App\Http\Controllers\CustomBouquetController;
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/categories/{slug}', [ShopController::class, 'category'])->name('shop.category');

Route::get('/custom-bouquet', [CustomBouquetController::class, 'index'])->name('custom-bouquet.index');
Route::post('/custom-bouquet', [CustomBouquetController::class, 'addToCart'])->name('custom-bouquet.add');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/payment-proof/{orderNumber}', [CheckoutController::class, 'uploadPaymentProof'])->name('checkout.payment-proof');
Route::get('/tracking/{orderNumber?}', [CheckoutController::class, 'tracking'])->name('checkout.tracking');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Customer Account Routes (Auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AccountController::class, 'orderDetail'])->name('orders.detail');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])->name('addresses.delete');
    Route::post('/reviews', [AccountController::class, 'storeReview'])->name('reviews.store');
});

Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/toggle/{productId}', [WishlistController::class, 'toggle'])->name('toggle');
    Route::post('/move-to-cart/{productId}', [WishlistController::class, 'moveToCart'])->name('move-to-cart');
});

/*
|--------------------------------------------------------------------------
| Courier Routes (Role: courier, Status: approved)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:courier'])->prefix('courier')->name('courier.')->group(function () {
    Route::get('/', [CourierController::class, 'dashboard'])->name('dashboard');
    Route::get('/deliveries', [CourierController::class, 'deliveries'])->name('deliveries');
    Route::get('/deliveries/{id}', [CourierController::class, 'show'])->name('deliveries.show');
    Route::put('/deliveries/{id}/status', [CourierController::class, 'updateStatus'])->name('deliveries.status');
    Route::post('/deliveries/{id}/proof', [CourierController::class, 'uploadProof'])->name('deliveries.proof');
    Route::post('/deliveries/{id}/location', [CourierController::class, 'updateLocation'])->name('deliveries.location');
    Route::get('/history', [CourierController::class, 'history'])->name('history');
    Route::get('/profile', [CourierController::class, 'profile'])->name('profile');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::put('/orders/{id}/tracking-number', [AdminOrderController::class, 'updateTrackingNumber'])->name('orders.tracking-number');
    Route::get('/orders/{id}/print-resi', [AdminOrderController::class, 'printResi'])->name('orders.print-resi');
    Route::post('/orders/{id}/assign', [AdminOrderController::class, 'assignCourier'])->name('orders.assign');
    Route::post('/orders/{id}/payment/approve', [AdminOrderController::class, 'approvePayment'])->name('orders.payment.approve');
    Route::post('/orders/{id}/payment/reject', [AdminOrderController::class, 'rejectPayment'])->name('orders.payment.reject');

    // Products
    Route::resource('products', AdminProductController::class)->names('products');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Custom Bouquet Options
    Route::resource('custom-options', AdminCustomBouquetOptionController::class)->except(['show']);

    // Banners Management
    Route::get('/banners', [AdminBannerController::class, 'index'])->name('banners.index');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{id}/edit', [AdminBannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{id}', [AdminBannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{id}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');
    Route::post('/banners/{id}/toggle', [AdminBannerController::class, 'toggleStatus'])->name('banners.toggle');

    // Couriers Management
    Route::get('/couriers', [AdminCourierController::class, 'index'])->name('couriers.index');
    Route::get('/couriers/{id}', [AdminCourierController::class, 'show'])->name('couriers.show');
    Route::put('/couriers/{id}/status', [AdminCourierController::class, 'updateStatus'])->name('couriers.status');

    // Deliveries Management
    Route::get('/deliveries', [AdminDeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/{id}', [AdminDeliveryController::class, 'show'])->name('deliveries.show');
    Route::post('/deliveries/{id}/location', [AdminDeliveryController::class, 'updateLocation'])->name('deliveries.location');

    // Promotions
    Route::get('/promotions', [AdminPromotionController::class, 'index'])->name('promotions.index');
    Route::post('/promotions', [AdminPromotionController::class, 'store'])->name('promotions.store');
    Route::delete('/promotions/{id}', [AdminPromotionController::class, 'destroy'])->name('promotions.destroy');

    // Inventory
    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/{id}/adjust', [AdminInventoryController::class, 'adjust'])->name('inventory.adjust');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Settings (Profil Toko, Zona, Tarif, Jam Operasional, Slot, Tentang Kami)
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/profile', [AdminSettingController::class, 'profile'])->name('settings.profile');
    Route::post('/settings/profile', [AdminSettingController::class, 'updateProfile'])->name('settings.profile.update');
    Route::get('/settings/about', [AdminSettingController::class, 'about'])->name('settings.about');
    Route::post('/settings/about', [AdminSettingController::class, 'updateAbout'])->name('settings.about.update');
    Route::get('/settings/zones', [AdminSettingController::class, 'zones'])->name('settings.zones');
    Route::post('/settings/zones', [AdminSettingController::class, 'updateZones'])->name('settings.zones.update');
    Route::get('/settings/rates', [AdminSettingController::class, 'rates'])->name('settings.rates');
    Route::post('/settings/rates', [AdminSettingController::class, 'updateRates'])->name('settings.rates.update');
    Route::get('/settings/hours', [AdminSettingController::class, 'hours'])->name('settings.hours');
    Route::post('/settings/hours', [AdminSettingController::class, 'updateHours'])->name('settings.hours.update');
    Route::get('/settings/slots', [AdminSettingController::class, 'slots'])->name('settings.slots');
    Route::post('/settings/slots', [AdminSettingController::class, 'updateSlots'])->name('settings.slots.update');
});

// Fallback asset routes for serverless hosting (e.g. Vercel)
Route::get('/build/{path}', function (string $path) {
    $filePath = public_path('build/'.$path);
    if (! file_exists($filePath)) {
        abort(404);
    }
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeType = match ($extension) {
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'woff2' => 'font/woff2',
        'woff' => 'font/woff',
        'json' => 'application/json',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        default => mime_content_type($filePath) ?: 'application/octet-stream',
    };

    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000, immutable',
    ]);
})->where('path', '.*');

Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/'.$path);
    if (! file_exists($filePath)) {
        $tmpPath = '/tmp/storage/app/public/'.$path;
        if (file_exists($tmpPath)) {
            $filePath = $tmpPath;
        } else {
            abort(404);
        }
    }

    $mimeType = @mime_content_type($filePath) ?: 'application/octet-stream';

    return response()->file($filePath, [
        'Content-Type' => $mimeType,
    ]);
})->where('path', '.*');
