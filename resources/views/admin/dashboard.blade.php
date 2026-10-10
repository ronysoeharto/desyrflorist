@extends('layouts.admin')

@section('title', 'Admin Dashboard — Desy Florist')

@section('content')
<div class="space-y-8">
    
    <!-- Top Greeting -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Dashboard Studio</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">Ringkasan aktivitas penjualan bunga dan pengiriman hari ini.</p>
        </div>
        <div class="text-xs text-[#6B6565]">
            📅 Hari ini: <strong>{{ now()->translatedFormat('l, d F Y') }}</strong>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
        
        <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-2">
            <div class="flex items-center justify-between text-[#9B9494]">
                <span class="uppercase tracking-wider text-[10px] font-bold">Total Pendapatan</span>
                <span>💰</span>
            </div>
            <div class="font-serif text-2xl font-bold text-[#2A2626]">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-[#5C6A57] font-semibold block">Hari ini: Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-2">
            <div class="flex items-center justify-between text-[#9B9494]">
                <span class="uppercase tracking-wider text-[10px] font-bold">Pesanan Perlu Diproses</span>
                <span>💐</span>
            </div>
            <div class="font-serif text-2xl font-bold text-[#B85D66]">
                {{ $pendingOrders + $processingOrders }}
            </div>
            <span class="text-[10px] text-[#6B6565] block">
                {{ $pendingOrders }} pending, {{ $processingOrders }} sedang dirangkai
            </span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-2">
            <div class="flex items-center justify-between text-[#9B9494]">
                <span class="uppercase tracking-wider text-[10px] font-bold">Kurir Menunggu Verifikasi</span>
                <span>🛵</span>
            </div>
            <div class="font-serif text-2xl font-bold text-[#C5A059]">
                {{ $pendingCouriers }}
            </div>
            <a href="{{ route('admin.couriers.index') }}" class="text-[10px] text-[#B85D66] hover:underline font-semibold block">
                Tinjau calon kurir →
            </a>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-2">
            <div class="flex items-center justify-between text-[#9B9494]">
                <span class="uppercase tracking-wider text-[10px] font-bold">Stok Bunga Rendah</span>
                <span>⚠️</span>
            </div>
            <div class="font-serif text-2xl font-bold {{ $lowStockProducts > 0 ? 'text-red-600' : 'text-[#5C6A57]' }}">
                {{ $lowStockProducts }}
            </div>
            <a href="{{ route('admin.inventory.index') }}" class="text-[10px] text-[#6B6565] hover:underline block">
                Lihat daftar produk →
            </a>
        </div>

    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
        <div class="p-6 border-b border-[#EADFD4] flex items-center justify-between">
            <div>
                <h2 class="font-serif text-xl text-[#2A2626]">Pesanan Terbaru</h2>
                <p class="text-xs text-[#6B6565]">Daftar 8 pesanan terakhir yang masuk ke studio.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-[#B85D66] font-semibold hover:underline">
                Lihat Semua Pesanan →
            </a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="p-8 text-center text-xs text-[#6B6565]">
                Belum ada pesanan masuk.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                        <tr>
                            <th class="p-4">No. Pesanan</th>
                            <th class="p-4">Penerima & Jadwal</th>
                            <th class="p-4">Rangkaian Bunga</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Status Pesanan</th>
                            <th class="p-4">Kurir</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3ECE4]">
                        @foreach($recentOrders as $ord)
                            <tr class="hover:bg-[#FAF8F5]/50 transition">
                                <td class="p-4 font-mono font-bold text-[#2A2626]">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="hover:text-[#B85D66]">
                                        {{ $ord->order_number }}
                                    </a>
                                </td>
                                <td class="p-4">
                                    <span class="font-semibold block text-[#2A2626]">{{ $ord->recipient_name }}</span>
                                    <span class="text-[10px] text-[#6B6565]">{{ $ord->delivery_date->format('d M') }} (Slot: {{ $ord->delivery_slot }})</span>
                                </td>
                                <td class="p-4 text-[#6B6565]">
                                    {{ $ord->items->pluck('name')->implode(', ') }}
                                </td>
                                <td class="p-4 font-serif font-bold text-[#2A2626]">
                                    Rp {{ number_format($ord->total, 0, ',', '.') }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $ord->status_badge }}">
                                        {{ $ord->status_label }}
                                    </span>
                                </td>
                                <td class="p-4 text-[#6B6565]">
                                    {{ $ord->delivery?->courier?->name ?? 'Belum Ditugaskan' }}
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}"
                                       class="px-3 py-1.5 rounded-lg border border-[#D8BABF] hover:bg-[#F8E8EA] text-[#B85D66] font-semibold transition">
                                        Kelola
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
