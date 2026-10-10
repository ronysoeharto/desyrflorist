@extends('layouts.admin')

@section('title', 'Kupon Promosi — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="font-serif text-3xl text-[#2A2626]">Kupon & Voucher Promosi</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Coupons Table (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                        <tr>
                            <th class="p-4">Kode Kupon</th>
                            <th class="p-4">Tipe & Potongan</th>
                            <th class="p-4">Min. Belanja</th>
                            <th class="p-4">Masa Berlaku</th>
                            <th class="p-4">Digunakan</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3ECE4]">
                        @foreach($coupons as $cp)
                            <tr class="hover:bg-[#FAF8F5]/50 transition">
                                <td class="p-4 font-mono font-bold text-[#B85D66] text-sm">
                                    {{ $cp->code }}
                                </td>
                                <td class="p-4">
                                    @if($cp->discount_type === 'percentage')
                                        <span class="font-bold text-[#2A2626]">{{ $cp->discount_amount }}%</span>
                                        <span class="text-[10px] text-[#9B9494] block">Maks: Rp {{ number_format($cp->max_discount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="font-bold text-[#2A2626]">Rp {{ number_format($cp->discount_amount, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="p-4 text-[#6B6565]">
                                    Rp {{ number_format($cp->min_purchase, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-[#6B6565]">
                                    {{ $cp->start_date->format('d/m/y') }} - {{ $cp->end_date->format('d/m/y') }}
                                </td>
                                <td class="p-4 font-semibold text-[#2A2626]">
                                    {{ $cp->used_count }}x
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('admin.promotions.destroy', $cp->id) }}" method="POST" onsubmit="return confirm('Hapus kupon ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Coupon Form (4 cols) -->
        <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
            <h2 class="font-serif text-lg text-[#2A2626]">Buat Kupon Baru</h2>

            <form action="{{ route('admin.promotions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-medium mb-1">Kode Kupon *</label>
                    <input type="text" name="code" required placeholder="Contoh: BLOOM10" class="w-full p-2.5 rounded-xl border border-[#D8BABF] uppercase focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium mb-1">Tipe Diskon *</label>
                        <select name="discount_type" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white text-xs">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Nilai Potongan *</label>
                        <input type="number" name="discount_amount" required placeholder="10 atau 50000" class="w-full p-2.5 rounded-xl border border-[#D8BABF]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium mb-1">Min. Belanja (Rp)</label>
                        <input type="number" name="min_purchase" value="0" class="w-full p-2.5 rounded-xl border border-[#D8BABF]">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Maks. Diskon (Rp)</label>
                        <input type="number" name="max_discount" placeholder="50000" class="w-full p-2.5 rounded-xl border border-[#D8BABF]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium mb-1">Mulai Berlaku</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full p-2.5 rounded-xl border border-[#D8BABF]">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Berakhir</label>
                        <input type="date" name="end_date" value="{{ date('Y-m-d', strtotime('+3 months')) }}" required class="w-full p-2.5 rounded-xl border border-[#D8BABF]">
                    </div>
                </div>

                <input type="hidden" name="status" value="active">

                <button type="submit" class="w-full py-3 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider transition">
                    + Simpan Kupon Promo
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
