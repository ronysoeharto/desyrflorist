<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items', 'delivery.courier', 'payment'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('recipient_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();
        $couriers = User::where('role', 'courier')->whereHas('courierProfile', function ($q) {
            $q->where('status', 'approved');
        })->get();

        return view('admin.orders.index', compact('orders', 'couriers'));
    }

    public function show(int $id)
    {
        $order = Order::with(['user', 'items.product.primaryImage', 'delivery.courier', 'delivery.proofs', 'payment', 'coupon'])->findOrFail($id);
        $couriers = User::where('role', 'courier')->whereHas('courierProfile', function ($q) {
            $q->where('status', 'approved');
        })->get();

        return view('admin.orders.show', compact('order', 'couriers'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,paid,confirmed,processing,ready,assigned,picked_up,on_delivery,delivered,completed,cancelled',
            'payment_status' => 'nullable|in:pending,verifying,paid,rejected,failed,refunded',
        ]);

        $order->status = $request->status;
        if ($request->filled('payment_status')) {
            $order->payment_status = $request->payment_status;
            if ($order->payment) {
                $order->payment->update(['status' => $request->payment_status]);
            }
        }

        $order->save();

        // Sync with delivery status if applicable
        if (in_array($request->status, ['assigned', 'picked_up', 'on_delivery', 'delivered'])) {
            $delivery = $order->delivery ?? Delivery::create(['order_id' => $order->id, 'status' => $request->status]);
            $delivery->update(['status' => $request->status]);
        }

        return back()->with('success', 'Status pesanan '.$order->order_number.' berhasil diperbarui.');
    }

    public function assignCourier(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'courier_id' => 'required|exists:users,id',
        ]);

        $order->courier_id = $request->courier_id;
        $order->status = 'assigned';
        $order->save();

        $delivery = Delivery::firstOrCreate(['order_id' => $order->id]);
        $delivery->update([
            'courier_id' => $request->courier_id,
            'status' => 'assigned',
            'notes' => 'Telah ditugaskan ke kurir.',
        ]);

        return back()->with('success', 'Kurir berhasil ditugaskan untuk pesanan ini.');
    }

    public function approvePayment(Request $request, int $id)
    {
        $order = Order::with('payment')->findOrFail($id);

        $order->payment_status = 'paid';
        $order->paid_at = now();

        if (in_array($order->status, ['pending'])) {
            $order->status = 'confirmed';
            $order->confirmed_at = now();
        }

        $order->save();

        if ($order->payment) {
            $order->payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'rejection_reason' => null,
                'admin_notes' => $request->input('admin_notes', 'Pembayaran disetujui oleh admin.'),
            ]);
        }

        return back()->with('success', 'Pembayaran pesanan '.$order->order_number.' berhasil disetujui! Status pesanan kini Dikonfirmasi.');
    }

    public function rejectPayment(Request $request, int $id)
    {
        $order = Order::with('payment')->findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'admin_notes' => 'nullable|string|max:500',
        ], [
            'rejection_reason.required' => 'Mohon sertakan alasan penolakan bukti pembayaran agar pelanggan mengetahui penyebabnya.',
        ]);

        $order->payment_status = 'rejected';
        $order->save();

        if ($order->payment) {
            $order->payment->update([
                'status' => 'rejected',
                'rejection_reason' => $request->input('rejection_reason'),
                'admin_notes' => $request->input('admin_notes'),
            ]);
        }

        return back()->with('success', 'Bukti pembayaran pesanan '.$order->order_number.' telah ditolak. Pelanggan dapat mengunggah bukti pembayaran yang baru.');
    }

    public function updateTrackingNumber(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        if ($request->input('action') === 'generate') {
            $trackingNumber = Order::generateTrackingNumber();
        } else {
            $request->validate([
                'tracking_number' => 'required|string|max:100|unique:orders,tracking_number,'.$order->id,
            ], [
                'tracking_number.required' => 'Nomor resi wajib diisi.',
                'tracking_number.unique' => 'Nomor resi ini sudah digunakan oleh pesanan lain.',
            ]);
            $trackingNumber = trim($request->tracking_number);
        }

        $order->update([
            'tracking_number' => $trackingNumber,
        ]);

        return back()->with('success', 'Nomor resi resmi untuk pesanan '.$order->order_number.' berhasil diperbarui: '.$trackingNumber);
    }

    public function printResi(int $id)
    {
        $order = Order::with(['user', 'items.product.primaryImage', 'delivery.courier', 'payment'])->findOrFail($id);

        if (! $order->tracking_number) {
            $order->update(['tracking_number' => Order::generateTrackingNumber()]);
        }

        $storeSettings = StoreSetting::pluck('value', 'key');

        return view('admin.orders.print-resi', compact('order', 'storeSettings'));
    }
}
