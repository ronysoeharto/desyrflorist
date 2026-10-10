<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('stock', 'asc');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
        }

        $products = $query->paginate(20)->withQueryString();
        $transactions = InventoryTransaction::with('product')->latest()->take(10)->get();

        return view('admin.inventory.index', compact('products', 'transactions'));
    }

    public function adjust(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:200',
        ]);

        $qty = (int) $request->quantity;

        if ($request->type === 'in') {
            $product->increment('stock', $qty);
        } elseif ($request->type === 'out') {
            $product->decrement('stock', min($product->stock, $qty));
        } elseif ($request->type === 'adjustment') {
            $product->update(['stock' => $qty]);
        }

        InventoryTransaction::create([
            'product_id' => $product->id,
            'type' => $request->type,
            'quantity' => $qty,
            'notes' => $request->notes ?? 'Penyesuaian manual oleh admin',
        ]);

        return back()->with('success', 'Stok untuk '.$product->name.' berhasil diperbarui.');
    }
}
