<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get or create active cart for the current user or guest session.
     */
    public function getCart(): Cart
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Migrate session cart items if any existed prior to login
            $sessionId = Session::getId();
            $sessionCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
            if ($sessionCart) {
                foreach ($sessionCart->items as $item) {
                    $existingItem = $cart->items()
                        ->where('product_id', $item->product_id)
                        ->where('variation_id', $item->variation_id)
                        ->where('custom_config', $item->custom_config)
                        ->first();

                    if ($existingItem) {
                        $existingItem->increment('quantity', $item->quantity);
                    } else {
                        $item->update(['cart_id' => $cart->id]);
                    }
                }
                $sessionCart->delete();
            }

            return $cart->load(['items.product.images', 'items.variation']);
        }

        $sessionId = Session::getId();
        $cart = Cart::firstOrCreate(['session_id' => $sessionId]);

        return $cart->load(['items.product.images', 'items.variation']);
    }

    /**
     * Add product to cart.
     */
    public function addItem(int $productId, ?int $variationId = null, int $quantity = 1, ?array $customConfig = null): CartItem
    {
        $cart = $this->getCart();
        $product = Product::findOrFail($productId);

        $unitPrice = $product->current_price;
        if ($variationId) {
            $variation = ProductVariation::where('product_id', $productId)->findOrFail($variationId);
            $unitPrice += $variation->price_adjustment;
        }

        // If custom bouquet config contains price adjustment
        if ($customConfig && isset($customConfig['custom_price'])) {
            $unitPrice = $customConfig['custom_price'];
        }

        $existingItem = $cart->items()
            ->where('product_id', $productId)
            ->where('variation_id', $variationId)
            ->when($customConfig, function ($q) use ($customConfig) {
                return $q->where('custom_config', json_encode($customConfig));
            })
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);

            return $existingItem;
        }

        return $cart->items()->create([
            'product_id' => $productId,
            'variation_id' => $variationId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'custom_config' => $customConfig ? json_encode($customConfig) : null,
        ]);
    }

    /**
     * Update item quantity.
     */
    public function updateItemQuantity(int $itemId, int $quantity): bool
    {
        $cart = $this->getCart();
        $item = $cart->items()->find($itemId);

        if (! $item) {
            return false;
        }

        if ($quantity <= 0) {
            $item->delete();

            return true;
        }

        $item->update(['quantity' => $quantity]);

        return true;
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $itemId): bool
    {
        $cart = $this->getCart();
        $item = $cart->items()->find($itemId);

        if ($item) {
            return $item->delete();
        }

        return false;
    }

    /**
     * Clear all items from cart.
     */
    public function clear(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }

    /**
     * Get item count.
     */
    public function count(): int
    {
        $cart = $this->getCart();

        return (int) $cart->items()->sum('quantity');
    }

    /**
     * Get subtotal.
     */
    public function subtotal(): float
    {
        $cart = $this->getCart();

        return (float) $cart->items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });
    }
}
