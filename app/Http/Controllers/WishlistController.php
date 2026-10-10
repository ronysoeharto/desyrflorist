<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $items = Wishlist::where('user_id', $user->id)
            ->with('product.primaryImage', 'product.category')
            ->latest()
            ->get();

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request, int $productId)
    {
        $user = Auth::user();

        $item = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->delete();
            $inWishlist = false;
            $message = 'Produk dihapus dari wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $inWishlist = true;
            $message = 'Produk disimpan ke wishlist.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'in_wishlist' => $inWishlist,
                'message' => $message,
                'count' => Wishlist::where('user_id', $user->id)->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    public function moveToCart(Request $request, int $productId, CartService $cartService)
    {
        $user = Auth::user();

        Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        $cartService->addItem($productId, null, 1);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dipindahkan ke keranjang belanja!');
    }
}
