@extends('layouts.admin')

@section('title', 'Manajemen Pesanan — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Manajemen Pesanan Bunga</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">Kelola status produksi, penugasan kurir, dan pembayaran pesanan.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-[#EADFD4] shadow-xs flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex-1 flex flex-wrap items-center gap-3 w-full">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nomor pesanan, nomor resi, atau penerima..."
                   class="px-3 py-2 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] text-xs flex-1 min-w-[200px]">

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-[#D8BABF] text-xs bg-white">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing (Dirangkai)</option>
                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready (Siap Kirim)</option>
                <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned ke Kurir</option>
                <option value="picked_up" {{ request('status') === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                <option value="on_delivery" {{ request('status') === 'on_delivery' ? 'selected' : '' }}>On Delivery</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered (Selesai)</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-[#FAF8F5] border border-[#D8BABF] rounded-xl font-semibold">Cari</button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-red-600 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
        @if($orders->isEmpty())
            <div class="p-8 text-center text-xs text-[#6B6565]">
                Tidak ada pesanan yang sesuai dengan filter pencarian.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                        <tr>
                            <th class="p-4">No. Pesanan</th>
                            <th class="p-4">Tanggal & Slot Kirim</th>
                            <th class="p-4">Penerima & Alamat</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Status Pesanan</th>
                            <th class="p-4">Kurir Bertugas</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3ECE4]">
                        @foreach($orders as $ord)
                            <tr class="hover:bg-[#FAF8F5]/50 transition">
                                <td class="p-4">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="font-mono font-bold text-[#2A2626] hover:text-[#B85D66] block">
                                        {{ $ord->order_number }}
                                    </a>
                                    @if($ord->tracking_number)
                                        <span class="inline-flex items-center gap-1 font-mono text-[10px] text-[#5C6A57] bg-[#EEF2EB] px-1.5 py-0.5 rounded mt-0.5 font-bold">
                                            <span>📦</span> {{ $ord->tracking_number }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="font-bold text-[#2A2626] block">{{ $ord->delivery_date->format('d M Y') }}</span>
                                    <span class="text-[10px] text-[#6B6565]">Slot: {{ $ord->delivery_slot }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="font-semibold text-[#2A2626] block">{{ $ord->recipient_name }}</span>
                                    <span class="text-[10px] text-[#6B6565] block max-w-xs truncate">{{ $ord->delivery_address }}</span>
                                </td>
                                <td class="p-4 font-serif font-bold text-[#2A2626]">
                                    Rp {{ number_format($ord->total, 0, ',', '.') }}
                                </td>
                                <td class="p-4">
                                    <div class="space-y-1">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $ord->status_badge }}">
                                            {{ $ord->status_label }}
                                        </span>
                                        <div>
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-semibold {{ $ord->payment_status_badge }}">
                                                {{ $ord->payment_status_label }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-[#6B6565]">
                                    {{ $ord->delivery?->courier?->name ?? '—' }}
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.orders.print-resi', $ord->id) }}" target="_blank" title="Cetak Resi Resmi"
                                           class="px-2.5 py-1.5 rounded-lg bg-white border border-[#111827] hover:bg-[#111827] hover:text-white text-[#111827] text-xs font-semibold transition">
                                            🖨️
                                        </a>
                                        <a href="{{ route('admin.orders.show', $ord->id) }}"
                                           class="px-3 py-1.5 rounded-lg border border-[#D8BABF] hover:bg-[#F8E8EA] text-[#B85D66] font-semibold transition">
                                            Kelola
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#EADFD4]">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
