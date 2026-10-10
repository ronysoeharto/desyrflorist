@extends('layouts.admin')

@section('title', 'Katalog Produk — Admin Desy Florist')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Katalog Produk Bunga</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">Kelola rangkaian bunga, harga, stok, dan foto produk.</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="px-5 py-2.5 bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold rounded-xl uppercase tracking-wider transition shadow-sm inline-flex items-center gap-1.5">
            <span>+ Tambah Produk Baru</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-[#EADFD4] shadow-xs flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex-1 flex flex-wrap items-center gap-3 w-full">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama bunga atau SKU..."
                   class="px-3 py-2 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] text-xs flex-1 min-w-[200px]">

            <select name="category_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-[#D8BABF] text-xs bg-white">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-[#FAF8F5] border border-[#D8BABF] rounded-xl font-semibold">Cari</button>
            @if(request()->anyFilled(['search', 'category_id']))
                <a href="{{ route('admin.products.index') }}" class="text-xs text-red-600 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                    <tr>
                        <th class="p-4">Foto & Nama Produk</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Harga Normal / Diskon</th>
                        <th class="p-4">Stok</th>
                        <th class="p-4">Status & Tag</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3ECE4]">
                    @foreach($products as $prod)
                        <tr class="hover:bg-[#FAF8F5]/50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $prod->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=200&q=80' }}"
                                         alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-cover border border-[#EADFD4]">
                                    <div>
                                        <a href="{{ route('shop.show', $prod->slug) }}" target="_blank" class="font-bold text-[#2A2626] hover:text-[#B85D66]">
                                            {{ $prod->name }}
                                        </a>
                                        <span class="text-[10px] text-[#9B9494] block font-mono">SKU: {{ $prod->sku }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-[#6B6565]">
                                {{ $prod->category?->name ?? '-' }}
                            </td>
                            <td class="p-4">
                                @if($prod->discount_price)
                                    <span class="text-xs font-bold text-[#B85D66] block">Rp {{ number_format($prod->discount_price, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-[#9B9494] line-through">Rp {{ number_format($prod->price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-xs font-bold text-[#2A2626]">Rp {{ number_format($prod->price, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="p-4 font-semibold {{ $prod->stock <= 5 ? 'text-red-600' : 'text-[#2A2626]' }}">
                                {{ $prod->stock }} rangkaian
                            </td>
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $prod->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($prod->status) }}
                                    </span>
                                    @if($prod->is_best_seller)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#B85D66] text-white">Best Seller</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $prod->id) }}"
                                       class="px-3 py-1.5 rounded-lg border border-[#D8BABF] hover:bg-[#F8E8EA] text-[#B85D66] font-semibold transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-500 hover:text-red-700" title="Hapus">
                                            &times;
                                        </button>
                                    </form>
                                </div>
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
</div>
@endsection
