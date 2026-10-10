<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Delivery::with(['order.items.product', 'order.user', 'courier', 'proofs'])->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $deliveries = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Delivery::count(),
            'assigned' => Delivery::where('status', 'assigned')->count(),
            'picked_up' => Delivery::where('status', 'picked_up')->count(),
            'on_delivery' => Delivery::where('status', 'on_delivery')->count(),
            'delivered' => Delivery::where('status', 'delivered')->count(),
        ];

        $couriers = User::where('role', 'courier')->get();
        $storeSettings = StoreSetting::pluck('value', 'key');

        return view('admin.deliveries.index', compact('deliveries', 'stats', 'status', 'couriers', 'storeSettings'));
    }

    public function show(Request $request, int $id)
    {
        $delivery = Delivery::with(['order.items.product.primaryImage', 'order.user', 'courier.courierProfile', 'proofs'])
            ->findOrFail($id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => $delivery->status,
                'status_label' => $delivery->status_label,
                'current_latitude' => $delivery->current_latitude,
                'current_longitude' => $delivery->current_longitude,
                'courier_name' => $delivery->courier?->name ?? 'Belum Ditugaskan',
                'last_updated_time' => $delivery->last_location_updated_at ? $delivery->last_location_updated_at->format('H:i:s, d M Y') : null,
                'last_updated_human' => $delivery->last_location_updated_at ? $delivery->last_location_updated_at->diffForHumans() : 'Belum Ada Pembaruan GPS',
            ]);
        }

        $storeSettings = StoreSetting::pluck('value', 'key');

        return view('admin.deliveries.show', compact('delivery', 'storeSettings'));
    }

    public function updateLocation(Request $request, int $id)
    {
        $delivery = Delivery::findOrFail($id);

        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $delivery->update([
            'current_latitude' => $request->latitude,
            'current_longitude' => $request->longitude,
            'last_location_updated_at' => now(),
        ]);

        return back()->with('success', 'Lokasi kurir berhasil diperbarui secara manual.');
    }
}
