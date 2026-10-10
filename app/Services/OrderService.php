<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Delivery;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrderFromCart(array $data, CartService $cartService): Order
    {
        return DB::transaction(function () use ($data, $cartService) {
            $cart = $cartService->getCart();
            if ($cart->items->isEmpty()) {
                throw new \Exception('Keranjang belanja Anda kosong.');
            }

            $user = Auth::user();
            $subtotal = $cartService->subtotal();

            // Calculate delivery fee
            $zone = DeliveryZone::find($data['delivery_zone_id'] ?? null);
            $deliveryFee = $zone ? $zone->delivery_fee : 35000;

            // Check store free shipping threshold (0 means disabled, set by admin)
            $freeShippingMin = (int) StoreSetting::get('free_shipping_min', '0');
            if ($freeShippingMin > 0 && $subtotal >= $freeShippingMin) {
                $deliveryFee = 0;
            }

            // Calculate coupon discount
            $discount = 0;
            $couponId = null;
            if (! empty($data['coupon_code'])) {
                $coupon = Coupon::where('code', $data['coupon_code'])->where('status', 'active')->first();
                if ($coupon && $coupon->isValid() && $subtotal >= $coupon->min_purchase) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                    $coupon->increment('used_count');
                }
            }

            $total = max(0, $subtotal - $discount + $deliveryFee);

            // Generate unique order number: DSY-YYYYMMDD-XXXX
            $orderNumber = 'DSY-'.date('Ymd').'-'.strtoupper(Str::random(5));
            $trackingNumber = Order::generateTrackingNumber();

            $order = Order::create([
                'user_id' => $user ? $user->id : null,
                'order_number' => $orderNumber,
                'tracking_number' => $trackingNumber,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'delivery_method' => $data['delivery_method'] ?? 'delivery',
                'delivery_date' => $data['delivery_date'],
                'delivery_slot' => $data['delivery_slot'],
                'recipient_name' => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],
                'delivery_address' => $data['delivery_address'],
                'delivery_city' => $data['city'] ?? $data['delivery_city'] ?? null,
                'delivery_district' => $data['district'] ?? $data['delivery_district'] ?? null,
                'recipient_latitude' => $data['recipient_latitude'] ?? null,
                'recipient_longitude' => $data['recipient_longitude'] ?? null,
                'distance_km' => $data['distance_km'] ?? null,
                'delivery_postal_code' => $data['postal_code'] ?? $data['delivery_postal_code'] ?? null,
                'delivery_notes' => $data['delivery_notes'] ?? null,
                'greeting_message' => $data['greeting_message'] ?? null,
                'coupon_id' => $couponId,
            ]);

            // Save order items
            foreach ($cart->items as $cartItem) {
                $productName = $cartItem->product ? $cartItem->product->name : 'Custom Artisan Bouquet';
                $variationName = $cartItem->variation ? $cartItem->variation->value : null;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'variation_id' => $cartItem->variation_id,
                    'product_name' => $productName,
                    'variation_name' => $variationName,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'subtotal' => $cartItem->unit_price * $cartItem->quantity,
                    'custom_config' => is_string($cartItem->custom_config) ? json_decode($cartItem->custom_config, true) : $cartItem->custom_config,
                ]);

                // Decrement stock
                if ($cartItem->product && $cartItem->product->stock > 0) {
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'],
                'amount' => $total,
                'status' => 'pending',
            ]);

            // Create initial delivery tracking record
            Delivery::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'Menunggu verifikasi pembayaran & penyiapan rangkaian bunga.',
            ]);

            // Record coupon usage if user logged in
            if ($couponId && $user) {
                CouponUsage::create([
                    'coupon_id' => $couponId,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                ]);
            }

            // Clear cart & session coupon
            $cartService->clear();
            session()->forget('applied_coupon');

            return $order;
        });
    }
}
