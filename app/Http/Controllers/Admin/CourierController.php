<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourierProfile;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = CourierProfile::with('user')->latest();
        if ($status) {
            $query->where('status', $status);
        }

        $couriers = $query->paginate(15);
        $pendingCount = CourierProfile::where('status', 'pending')->count();
        $approvedCount = CourierProfile::where('status', 'approved')->count();

        return view('admin.couriers.index', compact('couriers', 'pendingCount', 'approvedCount', 'status'));
    }

    public function show(int $id)
    {
        $courier = CourierProfile::with(['user.assignedDeliveries.order'])->findOrFail($id);

        return view('admin.couriers.show', compact('courier'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,suspended',
            'rejection_reason' => 'nullable|string|max:300',
        ]);

        $profile = CourierProfile::findOrFail($id);
        $user = $profile->user;

        $profile->status = $request->status;
        if ($request->status === 'approved') {
            $profile->approved_at = now();
            $user->status = 'active';
        } elseif ($request->status === 'rejected') {
            $user->status = 'inactive';
        } elseif ($request->status === 'suspended') {
            $user->status = 'suspended';
        }

        $profile->save();
        $user->save();

        $statusLabel = [
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            'suspended' => 'ditangguhkan',
        ][$request->status] ?? $request->status;

        return back()->with('success', "Status kurir {$user->name} berhasil {$statusLabel}.");
    }
}
