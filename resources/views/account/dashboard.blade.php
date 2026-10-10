@extends('layouts.app')

@section('title', 'Akun Saya — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Dashboard Pelanggan</h1>
        <p class="text-xs sm:text-sm text-[#6B6565] mt-1 font-light">Halo, <strong>{{ $user->name }}</strong>! Selamat datang di area akun Anda.</p>
    </div>
</div>

<x-account-nav />

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left Account Sidebar Navigation (Desktop) -->
        <aside class="space-y-4 hidden lg:block">
            <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                <div class="flex items-center gap-3 pb-4 border-b border-[#F3ECE4]">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border border-[#D8BABF]">
                    <div class="min-w-0">
                        <span class="font-semibold text-xs text-[#2A2626] block truncate">{{ $user->name }}</span>
                        <span class="text-[11px] text-[#9B9494] block truncate">{{ $user->email }}</span>
                    </div>
                </div>

                <nav class="space-y-1 text-xs">
                    <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-[#F8E8EA] text-[#B85D66] font-semibold">
                        <span>📊</span> Ringkasan Akun
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626] transition">
                        <span>📦</span> Riwayat Pesanan
                    </a>
                    <a href="{{ route('account.addresses') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626] transition">
                        <span>📍</span> Buku Alamat
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626] transition">
                        <span>🤍</span> Wishlist Favorit
                    </a>
                    <a href="{{ route('account.profile') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626] transition">
                        <span>⚙️</span> Pengaturan Profil
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Right Content Area -->
        <div class="lg:col-span-3 space-y-8">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs">
                    <span class="text-[10px] uppercase tracking-wider text-[#9B9494] block mb-1">Total Pesanan</span>
                    <span class="font-serif text-3xl font-bold text-[#2A2626]">{{ $totalOrders }}</span>
                    <span class="text-[11px] text-[#6B6565] block mt-1">Sepanjang waktu</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs">
                    <span class="text-[10px] uppercase tracking-wider text-[#9B9494] block mb-1">Pesanan Aktif</span>
                    <span class="font-serif text-3xl font-bold text-[#B85D66]">{{ $activeOrders }}</span>
                    <span class="text-[11px] text-[#6B6565] block mt-1">Sedang diproses / diantar</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs">
                    <span class="text-[10px] uppercase tracking-wider text-[#9B9494] block mb-1">Status Akun</span>
                    <span class="inline-block mt-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EEF2EB] text-[#5C6A57]">
                        Aktif Terverifikasi
                    </span>
                    <span class="text-[11px] text-[#6B6565] block mt-2">Member Pelanggan</span>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
                <div class="p-6 border-b border-[#EADFD4] flex items-center justify-between">
                    <h2 class="font-serif text-xl text-[#2A2626]">Pesanan Terbaru</h2>
                    <a href="{{ route('account.orders') }}" class="text-xs text-[#B85D66] hover:underline font-medium">Lihat Semua →</a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="p-8 text-center text-xs text-[#6B6565]">
                        Anda belum memiliki pesanan.
                        <a href="{{ route('shop.index') }}" class="text-[#B85D66] font-semibold hover:underline block mt-1">Pilih Bunga Sekarang →</a>
                    </div>
                @else
                    <div class="divide-y divide-[#F3ECE4]">
                        @foreach($recentOrders as $order)
                            <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-[#2A2626]">{{ $order->order_number }}</span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $order->status_badge }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-[#6B6565] text-[11px]">
                                        {{ $order->items->count() }} item bunga • {{ $order->delivery_date->format('d M Y') }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-4">
                                    <div class="text-right">
                                        <span class="font-serif text-sm font-bold text-[#2A2626]">
                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('account.orders.detail', $order->id) }}"
                                       class="px-3.5 py-1.5 rounded-lg border border-[#D8BABF] hover:border-[#B85D66] hover:bg-[#F8E8EA] text-xs font-semibold text-[#B85D66] transition">
                                        Detail Pesanan
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
