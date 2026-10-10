<?php

namespace App\Http\Controllers;

use App\Models\CustomBouquetOption;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CustomBouquetController extends Controller
{
    public function index()
    {
        $flowers = CustomBouquetOption::where('type', 'flower')->where('status', 'active')->orderBy('sort_order')->get();
        $wrappings = CustomBouquetOption::where('type', 'wrapping')->where('status', 'active')->orderBy('sort_order')->get();
        $ribbons = CustomBouquetOption::where('type', 'ribbon')->where('status', 'active')->orderBy('sort_order')->get();
        $addons = CustomBouquetOption::where('type', 'addon')->where('status', 'active')->orderBy('sort_order')->get();

        return view('custom-bouquet', compact('flowers', 'wrappings', 'ribbons', 'addons'));
    }

    public function addToCart(Request $request, CartService $cartService)
    {
        $request->validate([
            'flowers' => 'required|array',
            'flowers.*.id' => 'required|exists:custom_bouquet_options,id',
            'flowers.*.qty' => 'required|integer|min:0|max:50',
            'wrapping_id' => 'required|exists:custom_bouquet_options,id',
            'ribbon_id' => 'required|exists:custom_bouquet_options,id',
            'addons' => 'nullable|array',
            'addons.*' => 'exists:custom_bouquet_options,id',
            'card_message' => 'nullable|string|max:500',
            'card_to' => 'nullable|string|max:100',
            'card_from' => 'nullable|string|max:100',
        ]);

        // Validate that at least one flower has qty > 0
        $totalFlowerQty = collect($request->flowers)->sum('qty');
        if ($totalFlowerQty <= 0) {
            return back()->withErrors(['flowers' => 'Pilih setidaknya 1 tangkai bunga untuk buket Anda.'])->withInput();
        }

        // Calculate total custom bouquet price
        $totalPrice = 0;
        $summary = [];

        // Base arrangement service fee
        $baseFee = 50000;
        $totalPrice += $baseFee;
        $summary['service_fee'] = $baseFee;

        // Flowers
        $flowerItems = [];
        foreach ($request->flowers as $flowerInput) {
            $qty = (int) $flowerInput['qty'];
            if ($qty <= 0) {
                continue;
            }

            $opt = CustomBouquetOption::find($flowerInput['id']);
            $cost = $opt->price * $qty;
            $totalPrice += $cost;
            $flowerItems[] = [
                'name' => $opt->name,
                'qty' => $qty,
                'price' => $opt->price,
                'subtotal' => $cost,
            ];
        }
        $summary['flowers'] = $flowerItems;

        // Wrapping
        $wrapping = CustomBouquetOption::find($request->wrapping_id);
        $totalPrice += $wrapping->price;
        $summary['wrapping'] = $wrapping->name;

        // Ribbon
        $ribbon = CustomBouquetOption::find($request->ribbon_id);
        $totalPrice += $ribbon->price;
        $summary['ribbon'] = $ribbon->name;

        // Add-ons
        $addonItems = [];
        if ($request->filled('addons')) {
            foreach ($request->addons as $addonId) {
                $addon = CustomBouquetOption::find($addonId);
                $totalPrice += $addon->price;
                $addonItems[] = [
                    'name' => $addon->name,
                    'price' => $addon->price,
                ];
            }
        }
        $summary['addons'] = $addonItems;

        $summary['card'] = [
            'to' => $request->card_to,
            'from' => $request->card_from,
            'message' => $request->card_message,
        ];
        $summary['custom_price'] = $totalPrice;

        // Find or create a system anchor product for custom bouquets
        $customProduct = Product::firstOrCreate(
            ['slug' => 'custom-artisan-bouquet'],
            [
                'category_id' => 1,
                'name' => 'Custom Hand-Crafted Artisan Bouquet',
                'description' => 'Rangkaian buket personalisasi sesuai pilihan bunga, kertas, pita, dan aksesoris Anda.',
                'price' => $totalPrice,
                'sku' => 'CUSTOM-BOUQUET',
                'stock' => 999,
                'status' => 'active',
            ]
        );

        $cartService->addItem($customProduct->id, null, 1, $summary);

        return redirect()->route('cart.index')->with('success', 'Buket impian Anda berhasil dirangkai dan dimasukkan ke keranjang!');
    }
}
