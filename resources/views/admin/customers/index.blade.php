@extends('layouts.admin')

@section('title', 'Daftar Pelanggan — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Pelanggan Terdaftar</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">Daftar pelanggan terdaftar dan riwayat pembelian bunga.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                    <tr>
                        <th class="p-4">Pelanggan</th>
                        <th class="p-4">Email & Telepon</th>
                        <th class="p-4">Total Pesanan</th>
                        <th class="p-4">Terdaftar Sejak</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3ECE4]">
                    @foreach($customers as $cust)
                        <tr class="hover:bg-[#FAF8F5]/50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $cust->avatar_url }}" alt="{{ $cust->name }}" class="w-9 h-9 rounded-full object-cover border border-[#D8BABF]">
                                    <span class="font-bold text-[#2A2626]">{{ $cust->name }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-[#6B6565]">
                                <div>{{ $cust->email }}</div>
                                <div class="text-[10px] text-[#9B9494]">{{ $cust->phone ?? 'Belum ada nomor HP' }}</div>
                            </td>
                            <td class="p-4 font-bold text-[#2A2626]">
                                {{ $cust->orders_count }} pesanan
                            </td>
                            <td class="p-4 text-[#6B6565]">
                                {{ $cust->created_at->format('d M Y') }}
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.customers.show', $cust->id) }}"
                                   class="px-3 py-1.5 border border-[#D8BABF] hover:bg-[#F8E8EA] text-[#B85D66] font-semibold rounded-lg transition">
                                    Riwayat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#EADFD4]">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
