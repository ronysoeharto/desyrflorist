@extends('layouts.admin')

@section('title', 'Stok & Inventori — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Stok & Inventori Rangkaian Bunga</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">Pantau ketersediaan bunga dan lakukan penyesuaian stok secara cepat.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Inventory List (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                        <tr>
                            <th class="p-4">Produk Bunga</th>
                            <th class="p-4">SKU</th>
                            <th class="p-4">Stok Saat Ini</th>
                            <th class="p-4">Penyesuaian Stok Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3ECE4]">
                        @foreach($products as $prod)
                            <tr class="hover:bg-[#FAF8F5]/50 transition">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $prod->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=200&q=80' }}"
                                             alt="{{ $prod->name }}" class="w-10 h-10 rounded-lg object-cover">
                                        <div>
                                            <span class="font-bold text-[#2A2626] block">{{ $prod->name }}</span>
                                            <span class="text-[10px] text-[#9B9494]">{{ $prod->category?->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 font-mono text-[#6B6565]">
                                    {{ $prod->sku }}
                                </td>
                                <td class="p-4">
                                    <span class="font-bold text-sm {{ $prod->stock <= 5 ? 'text-red-600' : 'text-[#2A2626]' }}">
                                        {{ $prod->stock }} unit
                                    </span>
                                    @if($prod->stock <= 5)
                                        <span class="text-[9px] bg-red-100 text-red-700 px-1.5 py-0.2 rounded font-semibold block mt-0.5">Hampir Habis</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <form action="{{ route('admin.inventory.adjust', $prod->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <select name="type" class="p-1.5 border border-[#D8BABF] rounded-lg text-[11px] bg-white">
                                            <option value="in">+ Tambah</option>
                                            <option value="out">- Kurang</option>
                                            <option value="adjustment">= Set Nilai</option>
                                        </select>
                                        <input type="number" name="quantity" min="1" value="5" class="w-16 p-1.5 border border-[#D8BABF] rounded-lg text-xs text-center">
                                        <button type="submit" class="px-2.5 py-1.5 bg-[#FAF8F5] border border-[#D8BABF] hover:bg-[#F8E8EA] hover:border-[#B85D66] text-[#2A2626] rounded-lg text-xs font-semibold">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#EADFD4]">
                {{ $products->links() }}
            </div>
        </div>

        <!-- Recent Transactions Log (4 cols) -->
        <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
            <h2 class="font-serif text-lg text-[#2A2626]">Log Mutasi Stok Terakhir</h2>

            @if($transactions->isEmpty())
                <p class="text-[#9B9494] text-center py-4">Belum ada catatan mutasi stok.</p>
            @else
                <div class="space-y-3">
                    @foreach($transactions as $trx)
                        <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-[#2A2626]">{{ $trx->product?->name }}</span>
                                <span class="font-bold {{ $trx->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $trx->type === 'in' ? '+' : '-' }}{{ $trx->quantity }}
                                </span>
                            </div>
                            <p class="text-[10px] text-[#6B6565]">{{ $trx->notes }}</p>
                            <span class="text-[9px] text-[#9B9494] block">{{ $trx->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
