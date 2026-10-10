<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index()
    {
        $cart = $this->cartService->getCart();
        $subtotal = $this->cartService->subtotal();

        // Check applied coupon from session
        $couponCode = session('applied_coupon');
        $coupon = null;
        $discountAmount = 0;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('status', 'active')->first();
            if ($coupon && $coupon->isValid()) {
                if ($subtotal >= $coupon->min_purchase) {
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                } else {
                    session()->forget('applied_coupon');
                }
            } else {
                session()->forget('applied_coupon');
            }
        }

        $total = max(0, $subtotal - $discountAmount);

        return view('cart.index', compact('cart', 'subtotal', 'coupon', 'discountAmount', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'variation_id' => 'nullable|exists:product_variations,id',
            'card_message' => 'nullable|string|max:500',
            'delivery_date' => 'nullable|date|after_or_equal:today',
        ]);

        $customConfig = [];
        if ($request->filled('card_message')) {
            $customConfig['card_message'] = $request->card_message;
        }
        if ($request->filled('delivery_date')) {
            $customConfig['delivery_date'] = $request->delivery_date;
        }

        $this->cartService->addItem(
            (int) $request->product_id,
            $request->variation_id ? (int) $request->variation_id : null,
            (int) ($request->quantity ?? 1),
            ! empty($customConfig) ? $customConfig : null
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rangkaian bunga berhasil ditambahkan ke keranjang!',
                'count' => $this->cartService->count(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Rangkaian bunga berhasil ditambahkan ke keranjang belanja!');
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cartService->updateItemQuantity($itemId, (int) $request->quantity);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $this->cartService->count(),
                'subtotal' => $this->cartService->subtotal(),
            ]);
        }

        return back()->with('success', 'Jumlah pesanan telah diperbarui.');
    }

    public function remove(int $itemId)
    {
        $this->cartService->removeItem($itemId);

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->where('status', 'active')->first();

        if (! $coupon || ! $coupon->isValid()) {
            return back()->withErrors(['coupon' => 'Kode voucher tidak valid atau telah kedaluwarsa.']);
        }

        $subtotal = $this->cartService->subtotal();
        if ($subtotal < $coupon->min_purchase) {
            return back()->withErrors([
                'coupon' => 'Minimal belanja untuk voucher ini adalah Rp '.number_format($coupon->min_purchase, 0, ',', '.'),
            ]);
        }

        session(['applied_coupon' => $coupon->code]);

        return back()->with('success', 'Voucher '.$coupon->code.' berhasil digunakan!');
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');

        return back()->with('success', 'Voucher telah dilepas.');
    }
}
