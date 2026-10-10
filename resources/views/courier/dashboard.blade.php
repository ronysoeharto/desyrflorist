@extends('layouts.courier')

@section('title', 'Dashboard Kurir — Desy Florist')

@section('content')
<div class="space-y-6">
    
    <!-- Status Banner -->
    <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-2xl text-[#2A2626]">Halo, {{ $user->name }}!</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">
                Kendaraan: <strong>{{ $profile->vehicle_number ?? 'Motor' }}</strong> (Plat: {{ $profile->plate_number ?? '-' }})
            </p>
        </div>
        <div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-[#EEF2EB] text-[#5C6A57]">
                Status: Mitra Aktif (Disetujui)
            </span>
        </div>
    </div>

    <!-- Quick Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
        <div class="bg-white p-4 rounded-xl border border-[#EADFD4]">
            <span class="text-[#9B9494] block">Tugas Pengantaran Aktif</span>
            <span class="font-serif text-2xl font-bold text-[#B85D66] mt-1 block">{{ $activeDeliveries->count() }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#EADFD4]">
            <span class="text-[#9B9494] block">Terkirim Hari Ini</span>
            <span class="font-serif text-2xl font-bold text-[#5C6A57] mt-1 block">{{ $todayDeliveries }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#EADFD4] col-span-2 sm:col-span-1">
            <span class="text-[#9B9494] block">Total Selesai</span>
            <span class="font-serif text-2xl font-bold text-[#2A2626] mt-1 block">{{ $completedCount }}</span>
        </div>
    </div>

    <!-- Active Deliveries Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="font-serif text-xl text-[#2A2626]">Tugas Pengantaran Bunga</h2>
            <span class="text-xs text-[#6B6565]">{{ $activeDeliveries->count() }} tugas menanti</span>
        </div>

        @if($activeDeliveries->isEmpty())
            <div class="bg-white p-8 rounded-2xl border border-[#EADFD4] text-center text-xs text-[#6B6565] space-y-2">
                <span class="text-3xl block">🛵</span>
                <p>Tidak ada pengantaran aktif saat ini. Admin akan menugaskan pesanan saat bunga siap dikirim.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($activeDeliveries as $del)
                    <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                        <div class="flex items-center justify-between pb-3 border-b border-[#F3ECE4]">
                            <div>
                                <span class="font-mono font-bold text-sm text-[#2A2626]">{{ $del->order->order_number }}</span>
                                <span class="text-[11px] text-[#6B6565] block">Slot: {{ $del->order->delivery_slot }} • {{ $del->order->delivery_date->format('d M Y') }}</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $del->status === 'on_delivery' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $del->status_label }}
                            </span>
                        </div>

                        <div class="space-y-1.5">
                            <p><strong>Penerima:</strong> {{ $del->order->recipient_name }} (<a href="tel:{{ $del->order->recipient_phone }}" class="text-[#5C6A57] font-semibold underline">{{ $del->order->recipient_phone }}</a>)</p>
                            <p><strong>Alamat:</strong> {{ $del->order->delivery_address }}</p>
                            @if($del->order->delivery_notes)
                                <p class="text-[11px] text-[#6B6565]"><strong>Catatan:</strong> {{ $del->order->delivery_notes }}</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-[#F3ECE4]">
                            <a href="{{ $del->order->google_maps_url }}" target="_blank"
                               class="text-[#5C6A57] hover:underline flex items-center gap-1 font-semibold">
                                🗺️ Buka di Google Maps
                            </a>

                            <a href="{{ route('courier.deliveries.show', $del->id) }}"
                               class="px-4 py-2 bg-[#5C6A57] hover:bg-[#434F3F] text-white rounded-xl font-semibold transition">
                                Update Status & Bukti →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
