<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->with('items.product.primaryImage')->latest()->take(5)->get();
        $totalOrders = $user->orders()->count();
        $activeOrders = $user->orders()->whereIn('status', ['pending', 'paid', 'confirmed', 'processing', 'ready', 'assigned', 'picked_up', 'on_delivery'])->count();

        return view('account.dashboard', compact('user', 'recentOrders', 'totalOrders', 'activeOrders'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with(['items.product.primaryImage', 'delivery'])->latest()->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function orderDetail(int $id)
    {
        $user = Auth::user();
        $order = $user->orders()->with(['items.product.primaryImage', 'delivery.courier', 'payment', 'coupon', 'reviews'])->findOrFail($id);

        return view('account.order-detail', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();

        return view('account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil Anda telah berhasil diperbarui.');
    }

    public function addresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->latest()->get();

        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:50',
            'district' => 'required|string|max:50',
            'postal_code' => 'nullable|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        $isDefault = $request->boolean('is_default');
        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create(array_merge($validated, ['is_default' => $isDefault]));

        return back()->with('success', 'Alamat baru berhasil ditambahkan.');
    }

    public function deleteAddress(int $id)
    {
        $user = Auth::user();
        $user->addresses()->findOrFail($id)->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function storeReview(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Check if user owns order and order is delivered or completed
        $order = $user->orders()->whereIn('status', ['delivered', 'completed'])->findOrFail($request->order_id);

        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('order_id', $order->id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('info', 'Anda sudah memberikan ulasan untuk rangkaian bunga ini.');
        }

        Review::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'approved', // Auto-approved directly into testimonials!
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda telah berhasil dikirim dan tampil secara langsung pada testimoni toko kami.');
    }
}
