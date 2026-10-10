@extends('layouts.admin')

@section('title', 'Kategori Bunga — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="font-serif text-3xl text-[#2A2626]">Kategori Rangkaian Bunga</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Categories Table (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                        <tr>
                            <th class="p-4">Foto & Kategori</th>
                            <th class="p-4">Deskripsi</th>
                            <th class="p-4">Jumlah Produk</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3ECE4]">
                        @foreach($categories as $cat)
                            <tr class="hover:bg-[#FAF8F5]/50 transition">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $cat->image ?? 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=200&q=80' }}"
                                             alt="{{ $cat->name }}" class="w-12 h-12 rounded-xl object-cover border border-[#EADFD4]">
                                        <div>
                                            <span class="font-bold text-[#2A2626] block">{{ $cat->name }}</span>
                                            <span class="text-[10px] text-[#9B9494] font-mono">/{{ $cat->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-[#6B6565] max-w-xs truncate">
                                    {{ $cat->description }}
                                </td>
                                <td class="p-4 font-semibold text-[#2A2626]">
                                    {{ $cat->products_count }} produk
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $cat->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($cat->status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
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

        <!-- Right: Add New Category Form (4 cols) -->
        <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
            <h2 class="font-serif text-lg text-[#2A2626]">Tambah Kategori Baru</h2>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-medium mb-1">Nama Kategori *</label>
                    <input type="text" name="name" required placeholder="Contoh: Bloom Box" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div>
                    <label class="block font-medium mb-1">URL Foto Sampul</label>
                    <input type="url" name="image" placeholder="https://images.unsplash.com/..." class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div>
                    <label class="block font-medium mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" placeholder="Penjelasan kategori..." class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium mb-1">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="1" class="w-full p-2.5 rounded-xl border border-[#D8BABF]">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Status</label>
                        <select name="status" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider transition">
                    + Simpan Kategori
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
