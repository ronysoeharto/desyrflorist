@extends('layouts.admin')

@section('title', 'Riwayat Pelanggan — ' . $customer->name)

@section('content')
<div class="space-y-6">
    <div>
        <nav class="text-xs text-[#9B9494] mb-1">
            <a href="{{ route('admin.customers.index') }}" class="hover:underline">← Kembali ke Daftar Pelanggan</a>
        </nav>
        <h1 class="font-serif text-3xl text-[#2A2626]">Profil Pelanggan: {{ $customer->name }}</h1>
    </div>

    <!-- Customer Overview -->
    <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs text-xs space-y-4">
        <div class="flex items-center gap-4 pb-4 border-b border-[#F3ECE4]">
            <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}" class="w-14 h-14 rounded-full object-cover border border-[#D8BABF]">
            <div>
                <h2 class="font-bold text-base text-[#2A2626]">{{ $customer->name }}</h2>
                <span class="text-[#6B6565] block">{{ $customer->email }} • {{ $customer->phone ?? '-' }}</span>
                <span class="text-[10px] text-[#9B9494] block mt-0.5">Bergabung sejak {{ $customer->created_at->format('d M Y') }}</span>
            </div>
        </div>

        <div class="space-y-2">
            <h3 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px]">Buku Alamat Tersimpan:</h3>
            @if($customer->addresses->isEmpty())
                <p class="text-[#9B9494] italic">Belum ada alamat tersimpan.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($customer->addresses as $ad)
                        <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4]">
                            <span class="font-bold text-[#2A2626] block">{{ $ad->label }}</span>
                            <p class="text-[11px] text-[#6B6565] mt-1">{{ $ad->recipient_name }} • {{ $ad->address }}, {{ $ad->city }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Order History Table -->
    <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
        <div class="p-5 border-b border-[#EADFD4]">
            <h2 class="font-serif text-lg text-[#2A2626]">Semua Pesanan Pelanggan Ini ({{ $customer->orders->count() }})</h2>
        </div>

        @if($customer->orders->isEmpty())
            <div class="p-8 text-center text-xs text-[#6B6565]">Belum ada pesanan.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                        <tr>
                            <th class="p-4">No. Pesanan</th>
                            <th class="p-4">Tanggal Kirim</th>
                            <th class="p-4">Item Bunga</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3ECE4]">
                        @foreach($customer->orders as $ord)
                            <tr class="hover:bg-[#FAF8F5]/50 transition">
                                <td class="p-4 font-mono font-bold text-[#2A2626]">{{ $ord->order_number }}</td>
                                <td class="p-4 text-[#6B6565]">{{ $ord->delivery_date->format('d M Y') }}</td>
                                <td class="p-4 text-[#6B6565]">{{ $ord->items->pluck('name')->implode(', ') }}</td>
                                <td class="p-4 font-serif font-bold text-[#2A2626]">Rp {{ number_format($ord->total, 0, ',', '.') }}</td>
                                <td class="p-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $ord->status_badge }}">
                                        {{ $ord->status_label }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="text-[#B85D66] font-semibold hover:underline">
                                        Rincian →
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
