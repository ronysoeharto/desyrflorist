<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\DeliveryProof;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $profile = $user->courierProfile;

        $activeDeliveries = Delivery::with(['order.items.product'])
            ->where('courier_id', $user->id)
            ->whereIn('status', ['assigned', 'picked_up', 'on_delivery'])
            ->latest()
            ->get();

        $completedCount = Delivery::where('courier_id', $user->id)
            ->where('status', 'delivered')
            ->count();

        $todayDeliveries = Delivery::where('courier_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        return view('courier.dashboard', compact('user', 'profile', 'activeDeliveries', 'completedCount', 'todayDeliveries'));
    }

    public function deliveries()
    {
        $user = Auth::user();
        $deliveries = Delivery::with(['order.items.product'])
            ->where('courier_id', $user->id)
            ->whereIn('status', ['assigned', 'picked_up', 'on_delivery'])
            ->latest()
            ->get();

        return view('courier.deliveries', compact('deliveries'));
    }

    public function show(int $id)
    {
        $user = Auth::user();
        $delivery = Delivery::with(['order.items.product.primaryImage', 'proofs'])
            ->where('courier_id', $user->id)
            ->findOrFail($id);

        return view('courier.delivery-detail', compact('delivery'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $user = Auth::user();
        $delivery = Delivery::where('courier_id', $user->id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:picked_up,on_delivery,delivered,failed',
            'notes' => 'nullable|string|max:300',
        ]);

        $newStatus = $request->status;

        // Strict sequential workflow: Courier must pick up flowers at store before delivering
        if ($newStatus === 'on_delivery' && $delivery->status !== 'picked_up') {
            return back()->with('error', 'Anda harus mengonfirmasi pengambilan bunga di toko terlebih dahulu sebelum memulai pengantaran.');
        }

        $delivery->status = $newStatus;
        if ($request->filled('notes')) {
            $delivery->notes = $request->notes;
        }

        $storeLat = (float) StoreSetting::get('store_latitude', '-7.257472');
        $storeLng = (float) StoreSetting::get('store_longitude', '112.752088');

        if (is_null($delivery->current_latitude) || is_null($delivery->current_longitude)) {
            $delivery->current_latitude = $storeLat;
            $delivery->current_longitude = $storeLng;
            $delivery->last_location_updated_at = now();
        }

        if ($newStatus === 'picked_up') {
            $delivery->picked_up_at = now();
            $delivery->order->update(['status' => 'picked_up']);
        } elseif ($newStatus === 'on_delivery') {
            $delivery->order->update(['status' => 'on_delivery']);
        } elseif ($newStatus === 'delivered') {
            $delivery->delivered_at = now();
            $delivery->order->update(['status' => 'delivered']);
        }

        $delivery->save();

        $message = match ($newStatus) {
            'picked_up' => 'Bunga berhasil diambil di toko! Silakan mulai pengantaran ke penerima.',
            'on_delivery' => 'Pengantaran dimulai! GPS pelacakan live kini aktif untuk dipantau oleh pembeli.',
            'delivered' => 'Pesanan berhasil diselesaikan!',
            default => 'Status pengiriman berhasil diperbarui!',
        };

        return back()->with('success', $message);
    }

    public function uploadProof(Request $request, int $id)
    {
        $user = Auth::user();
        $delivery = Delivery::where('courier_id', $user->id)->findOrFail($id);

        if ($delivery->status === 'assigned') {
            return back()->with('error', 'Silakan ambil bunga di toko dan mulai pengantaran terlebih dahulu sebelum menyelesaikan pesanan.');
        }

        $request->validate([
            'type' => 'required|in:photo,signature',
            'proof_image' => 'required|image|max:5120', // max 5MB
        ]);

        $path = $request->file('proof_image')->store('delivery-proofs', 'public');

        DeliveryProof::create([
            'delivery_id' => $delivery->id,
            'type' => $request->type,
            'file_path' => $path,
        ]);

        $delivery->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
        $delivery->order->update(['status' => 'delivered']);

        return back()->with('success', 'Bukti serah terima bunga berhasil diunggah!');
    }

    public function history()
    {
        $user = Auth::user();
        $deliveries = Delivery::with(['order.items.product'])
            ->where('courier_id', $user->id)
            ->where('status', 'delivered')
            ->latest()
            ->paginate(15);

        return view('courier.history', compact('deliveries'));
    }

    public function updateLocation(Request $request, int $id)
    {
        $user = Auth::user();
        $delivery = Delivery::where('courier_id', $user->id)->findOrFail($id);

        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $delivery->update([
            'current_latitude' => $request->latitude,
            'current_longitude' => $request->longitude,
            'last_location_updated_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Koordinat GPS kurir berhasil diperbarui.',
                'last_updated' => now()->format('H:i:s'),
            ]);
        }

        return back()->with('success', 'Lokasi GPS Anda berhasil disinkronkan ke pelanggan.');
    }

    public function profile()
    {
        $user = Auth::user();
        $profile = $user->courierProfile;

        return view('courier.profile', compact('user', 'profile'));
    }
}
