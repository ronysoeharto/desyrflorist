@extends('layouts.admin')

@section('title', 'Manajemen Pengiriman — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Manajemen Pengiriman Bunga</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">
                Pantau seluruh penugasan kurir, pergerakan armada, dan status pengantaran bunga segar Desy Florist secara langsung.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.couriers.index') }}"
               class="px-4 py-2.5 rounded-xl bg-white border border-[#EADFD4] hover:border-[#B85D66] text-xs font-semibold text-[#2A2626] transition flex items-center gap-1.5 shadow-2xs">
                <span>🛵</span>
                <span>Kelola Mitra Kurir</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <a href="{{ route('admin.deliveries.index') }}"
           class="p-4 rounded-2xl border transition bg-white {{ !$status || $status === 'all' ? 'border-[#B85D66] shadow-xs' : 'border-[#EADFD4]' }}">
            <span class="text-[10px] text-[#9B9494] uppercase font-bold block">Total Pengiriman</span>
            <span class="font-serif text-2xl font-bold text-[#2A2626]">{{ $stats['total'] }}</span>
        </a>

        <a href="{{ route('admin.deliveries.index', ['status' => 'assigned']) }}"
           class="p-4 rounded-2xl border transition bg-white {{ $status === 'assigned' ? 'border-[#B85D66] shadow-xs' : 'border-[#EADFD4]' }}">
            <span class="text-[10px] text-[#C5A059] uppercase font-bold block">Ditugaskan</span>
            <span class="font-serif text-2xl font-bold text-[#C5A059]">{{ $stats['assigned'] }}</span>
        </a>

        <a href="{{ route('admin.deliveries.index', ['status' => 'picked_up']) }}"
           class="p-4 rounded-2xl border transition bg-white {{ $status === 'picked_up' ? 'border-[#B85D66] shadow-xs' : 'border-[#EADFD4]' }}">
            <span class="text-[10px] text-blue-600 uppercase font-bold block">Diambil Studio</span>
            <span class="font-serif text-2xl font-bold text-blue-600">{{ $stats['picked_up'] }}</span>
        </a>

        <a href="{{ route('admin.deliveries.index', ['status' => 'on_delivery']) }}"
           class="p-4 rounded-2xl border transition bg-white {{ $status === 'on_delivery' ? 'border-[#B85D66] shadow-xs' : 'border-[#EADFD4]' }}">
            <span class="text-[10px] text-purple-600 uppercase font-bold block">Sedang Diantar</span>
            <span class="font-serif text-2xl font-bold text-purple-600">{{ $stats['on_delivery'] }}</span>
        </a>

        <a href="{{ route('admin.deliveries.index', ['status' => 'delivered']) }}"
           class="p-4 rounded-2xl border transition bg-white {{ $status === 'delivered' ? 'border-[#B85D66] shadow-xs' : 'border-[#EADFD4]' }}">
            <span class="text-[10px] text-[#5C6A57] uppercase font-bold block">Terkirim Sukses</span>
            <span class="font-serif text-2xl font-bold text-[#5C6A57]">{{ $stats['delivered'] }}</span>
        </a>
    </div>

    <!-- Deliveries Table Card -->
    <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
        <div class="p-5 border-b border-[#EADFD4] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <span class="font-bold text-[#2A2626]">Daftar Pengantaran ({{ $deliveries->total() }} Pesanan)</span>
            <div class="flex items-center gap-2">
                <span class="text-[#6B6565]">Filter:</span>
                <select onchange="location = this.value;" class="p-1.5 rounded-lg border border-[#D8BABF] bg-[#FAF8F5] text-xs">
                    <option value="{{ route('admin.deliveries.index') }}" {{ !$status || $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="{{ route('admin.deliveries.index', ['status' => 'assigned']) }}" {{ $status === 'assigned' ? 'selected' : '' }}>Ditugaskan</option>
                    <option value="{{ route('admin.deliveries.index', ['status' => 'picked_up']) }}" {{ $status === 'picked_up' ? 'selected' : '' }}>Diambil Studio</option>
                    <option value="{{ route('admin.deliveries.index', ['status' => 'on_delivery']) }}" {{ $status === 'on_delivery' ? 'selected' : '' }}>Sedang Diantar</option>
                    <option value="{{ route('admin.deliveries.index', ['status' => 'delivered']) }}" {{ $status === 'delivered' ? 'selected' : '' }}>Selesai / Terkirim</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FAF8F5] text-[#6B6565] uppercase text-[10px] font-bold tracking-wider border-b border-[#EADFD4]">
                    <tr>
                        <th class="p-4">Pesanan</th>
                        <th class="p-4">Penerima & Alamat</th>
                        <th class="p-4">Jadwal Slot</th>
                        <th class="p-4">Kurir Bertugas</th>
                        <th class="p-4">Status Pengiriman</th>
                        <th class="p-4">Posisi GPS Kurir</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3ECE4]">
                    @forelse($deliveries as $del)
                        <tr class="hover:bg-[#FAF8F5]/60 transition">
                            <!-- Order Number -->
                            <td class="p-4">
                                <a href="{{ route('admin.orders.show', $del->order_id) }}" class="font-mono font-bold text-[#B85D66] hover:underline block">
                                    {{ $del->order->order_number }}
                                </a>
                                <span class="text-[10px] text-[#9B9494] block">Rp {{ number_format($del->order->total, 0, ',', '.') }}</span>
                            </td>

                            <!-- Recipient & Address -->
                            <td class="p-4 max-w-xs">
                                <strong class="text-[#2A2626] block truncate">{{ $del->order->recipient_name }}</strong>
                                <span class="text-[11px] text-[#6B6565] line-clamp-1">{{ $del->order->delivery_district ?? '' }} {{ $del->order->delivery_address }}</span>
                                <span class="text-[10px] text-[#9B9494] block mt-0.5">📞 {{ $del->order->recipient_phone }}</span>
                            </td>

                            <!-- Schedule & Slot -->
                            <td class="p-4">
                                <span class="text-[#2A2626] font-medium block">{{ $del->order->delivery_date->format('d M Y') }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded bg-[#FAF8F5] border border-[#EADFD4] text-[#6B6565] inline-block mt-0.5">
                                    {{ $del->order->delivery_slot }}
                                </span>
                            </td>

                            <!-- Assigned Courier -->
                            <td class="p-4">
                                @if($del->courier)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-[#FAF8F5] border border-[#D8BABF] flex items-center justify-center text-xs">
                                            🛵
                                        </div>
                                        <div>
                                            <span class="font-semibold text-[#2A2626] block">{{ $del->courier->name }}</span>
                                            <span class="text-[10px] text-[#6B6565]">{{ $del->courier->phone ?? '-' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[11px] text-[#9B9494] italic">Belum Ditugaskan</span>
                                @endif
                            </td>

                            <!-- Delivery Status -->
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    @if($del->status === 'delivered') bg-[#EEF2EB] text-[#5C6A57]
                                    @elseif($del->status === 'on_delivery') bg-purple-100 text-purple-700
                                    @elseif($del->status === 'picked_up') bg-blue-100 text-blue-700
                                    @else bg-[#FAF8F5] text-[#C5A059] border border-[#C5A059]/30 @endif">
                                    {{ $del->status_label }}
                                </span>
                            </td>

                            <!-- Real-time GPS Position -->
                            <td class="p-4">
                                @if($del->current_latitude && $del->current_longitude)
                                    <div class="space-y-0.5">
                                        <span class="font-mono text-[10px] text-[#2A2626] block font-semibold">
                                            {{ round($del->current_latitude, 5) }}, {{ round($del->current_longitude, 5) }}
                                        </span>
                                        <span class="text-[9px] text-[#5C6A57] block">
                                            Update: {{ $del->last_location_updated_at ? $del->last_location_updated_at->diffForHumans() : '-' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-[10px] text-[#9B9494] italic">GPS Belum Aktif</span>
                                @endif
                            </td>

                            <!-- Action -->
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.deliveries.show', $del->id) }}"
                                   class="px-3 py-1.5 bg-[#FAF8F5] hover:bg-[#B85D66] hover:text-white border border-[#D8BABF] rounded-lg transition font-semibold text-[11px] inline-flex items-center gap-1">
                                    <span>Lacak Rute</span>
                                    <span>›</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-[#9B9494]">
                                Belum ada data pengantaran pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deliveries->hasPages())
            <div class="p-4 border-t border-[#EADFD4]">
                {{ $deliveries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
