<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourierProfile;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::whereIn('status', ['paid', 'confirmed', 'processing', 'ready', 'assigned', 'picked_up', 'on_delivery', 'delivered', 'completed'])->sum('total');
        $todayRevenue = Order::whereDate('created_at', today())
            ->whereIn('status', ['paid', 'confirmed', 'processing', 'ready', 'assigned', 'picked_up', 'on_delivery', 'delivered', 'completed'])
            ->sum('total');

        $totalOrders = Order::count();
        $pendingOrders = Order::whereIn('status', ['pending', 'paid'])->count();
        $processingOrders = Order::whereIn('status', ['confirmed', 'processing', 'ready'])->count();
        $activeDeliveries = Order::whereIn('status', ['assigned', 'picked_up', 'on_delivery'])->count();

        $totalCustomers = User::where('role', 'customer')->count();
        $pendingCouriers = CourierProfile::where('status', 'pending')->count();
        $lowStockProducts = Product::where('stock', '<=', 5)->count();

        $recentOrders = Order::with(['user', 'items.product', 'delivery.courier'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'todayRevenue',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'activeDeliveries',
            'totalCustomers',
            'pendingCouriers',
            'lowStockProducts',
            'recentOrders'
        ));
    }
}
