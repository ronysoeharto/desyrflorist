<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Payment;
use App\Models\StoreSetting;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService
    ) {}

    public function index()
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('shop.index')->with('info', 'Keranjang Anda masih kosong. Silakan pilih rangkaian bunga favorit Anda terlebih dahulu.');
        }

        $subtotal = $this->cartService->subtotal();
        $deliveryZones = DeliveryZone::where('status', true)->orderBy('min_distance_km')->get();
        $freeShippingMin = (int) StoreSetting::get('free_shipping_min', '0');

        $storeSettings = StoreSetting::pluck('value', 'key');
        $enabledDistricts = json_decode(StoreSetting::get('enabled_districts', '[]'), true) ?: [];
        $deliverySlots = json_decode(StoreSetting::get('delivery_slots', '[]'), true) ?: [];
        $maxDistanceKm = (float) StoreSetting::get('max_delivery_distance_km', '25.0');
        $unservicedMessage = StoreSetting::get('unserviced_message', 'Mohon maaf, alamat tujuan Anda saat ini berada di luar area operasional pengiriman Desy Florist Surabaya.');

        // Check applied coupon
        $couponCode = session('applied_coupon');
        $coupon = null;
        $discountAmount = 0;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('status', 'active')->first();
            if ($coupon && $coupon->isValid() && $subtotal >= $coupon->min_purchase) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            }
        }

        $user = Auth::user();
        $defaultAddress = $user ? $user->defaultAddress() : null;

        return view('checkout.index', compact(
            'cart',
            'subtotal',
            'deliveryZones',
            'freeShippingMin',
            'storeSettings',
            'enabledDistricts',
            'deliverySlots',
            'maxDistanceKm',
            'unservicedMessage',
            'coupon',
            'discountAmount',
            'user',
            'defaultAddress'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:20',
            'delivery_zone_id' => 'required|exists:delivery_zones,id',
            'delivery_address' => 'required|string|max:500',
            'delivery_date' => 'required|date|after_or_equal:today',
            'delivery_slot' => 'required|string',
            'delivery_notes' => 'nullable|string|max:300',
            'greeting_message' => 'nullable|string|max:500',
            'payment_method' => 'required|string|in:bca,mandiri,qris,cod',
            'district' => 'nullable|string|max:100',
            'recipient_latitude' => 'nullable|numeric|between:-90,90',
            'recipient_longitude' => 'nullable|numeric|between:-180,180',
            'distance_km' => 'nullable|numeric|min:0',
        ]);

        $enabledDistricts = json_decode(StoreSetting::get('enabled_districts', '[]'), true) ?: [];
        $unservicedMessage = StoreSetting::get('unserviced_message', 'Mohon maaf, alamat tujuan Anda saat ini berada di luar area operasional pengiriman Desy Florist Surabaya.');
        $maxDistanceKm = (float) StoreSetting::get('max_delivery_distance_km', '25.0');

        // Check if district is enabled by admin
        $district = $request->input('district');
        if ($district && ! empty($enabledDistricts)) {
            // Case-insensitive match
            $isServed = collect($enabledDistricts)->contains(function ($item) use ($district) {
                return strcasecmp($item, $district) === 0;
            });

            if (! $isServed) {
                return back()->withInput()->withErrors([
                    'district' => $unservicedMessage." (Kecamatan {$district} saat ini belum masuk area operasional toko).",
                ]);
            }
        }

        // Check max distance
        if ($request->filled('distance_km') && (float) $request->distance_km > $maxDistanceKm) {
            return back()->withInput()->withErrors([
                'delivery_address' => $unservicedMessage." (Jarak pengiriman {$request->distance_km} KM melebihi batas maksimal {$maxDistanceKm} KM).",
            ]);
        }

        $data = $request->all();
        $data['coupon_code'] = session('applied_coupon');

        try {
            $order = $this->orderService->createOrderFromCart($data, $this->cartService);

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Pesanan Anda berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::with(['items.product.primaryImage', 'delivery', 'payment', 'coupon'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $storeSettings = StoreSetting::pluck('value', 'key');

        return view('checkout.success', compact('order', 'storeSettings'));
    }

    public function tracking(Request $request, ?string $orderNumber = null)
    {
        $order = null;
        $searchNumber = $orderNumber ?? $request->query('order_number') ?? $request->query('resi');

        if ($searchNumber) {
            $search = trim($searchNumber);
            $order = Order::with(['items.product.primaryImage', 'delivery.courier', 'payment'])
                ->where(function ($q) use ($search) {
                    $q->where('order_number', $search)
                        ->orWhere('tracking_number', $search);
                })
                ->first();
        }

        if ($request->wantsJson()) {
            $storeLat = (float) StoreSetting::get('store_latitude', '-7.257472');
            $storeLng = (float) StoreSetting::get('store_longitude', '112.752088');

            return response()->json([
                'order_number' => $order?->order_number,
                'tracking_number' => $order?->tracking_number,
                'order_status' => $order?->status,
                'status_label' => $order?->status_label,
                'delivery_status' => $order?->delivery?->status,
                'delivery_status_label' => $order?->delivery?->status_label,
                'current_latitude' => $order?->delivery?->current_latitude ?? $storeLat,
                'current_longitude' => $order?->delivery?->current_longitude ?? $storeLng,
                'recipient_latitude' => $order?->recipient_latitude,
                'recipient_longitude' => $order?->recipient_longitude,
                'store_latitude' => $storeLat,
                'store_longitude' => $storeLng,
                'courier_name' => $order?->delivery?->courier?->name ?? 'Kurir Desy Florist',
                'courier_phone' => $order?->delivery?->courier?->phone ?? '',
                'last_updated_human' => $order?->delivery?->last_location_updated_at ? $order->delivery->last_location_updated_at->diffForHumans() : 'Baru saja',
                'last_updated_time' => $order?->delivery?->last_location_updated_at ? $order->delivery->last_location_updated_at->format('H:i:s') : date('H:i:s'),
                'is_completed' => in_array($order?->status, ['delivered', 'completed', 'cancelled']),
            ]);
        }

        $storeSettings = StoreSetting::pluck('value', 'key');

        return view('checkout.tracking', compact('order', 'searchNumber', 'storeSettings'));
    }

    public function uploadPaymentProof(Request $request, string $orderNumber)
    {
        $order = Order::with('payment')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->isPaid()) {
            return back()->with('info', 'Pesanan ini sudah berstatus lunas.');
        }

        $request->validate([
            'proof' => 'required|file|mimes:jpeg,png,jpg,webp|max:5120',
            'customer_notes' => 'nullable|string|max:500',
        ], [
            'proof.required' => 'Silakan pilih foto atau file bukti transfer.',
            'proof.file' => 'File bukti transfer harus berupa berkas yang valid.',
            'proof.mimes' => 'File bukti transfer harus berformat JPEG, PNG, JPG, atau WEBP.',
            'proof.max' => 'Ukuran file bukti transfer maksimal 5 MB.',
        ]);

        $payment = $order->payment;
        if (! $payment) {
            $payment = Payment::create([
                'order_id' => $order->id,
                'method' => 'bank_transfer',
                'amount' => $order->total,
                'status' => 'pending',
            ]);
        }

        // Delete old proof file if exists
        if ($payment->proof_image && Storage::disk('public')->exists($payment->proof_image)) {
            Storage::disk('public')->delete($payment->proof_image);
        }

        $path = $request->file('proof')->store('payments', 'public');

        $paymentData = $payment->payment_data ?? [];
        if ($request->filled('customer_notes')) {
            $paymentData['customer_notes'] = $request->input('customer_notes');
        }
        $paymentData['uploaded_at'] = now()->toIso8601String();

        $payment->update([
            'proof_image' => $path,
            'proof_uploaded_at' => now(),
            'status' => 'verifying',
            'rejection_reason' => null, // clear previous rejection
            'payment_data' => $paymentData,
        ]);

        $order->update([
            'payment_status' => 'verifying',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Tim admin kami akan segera memeriksa dan menyetujui pembayaran Anda.');
    }
}
