@extends('layouts.admin')

@section('title', 'Tambah Produk Bunga Baru — Desy Florist')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <nav class="text-xs text-[#9B9494] mb-1">
                <a href="{{ route('admin.products.index') }}" class="hover:underline">← Kembali ke Katalog</a>
            </nav>
            <h1 class="font-serif text-3xl text-[#2A2626]">Tambah Produk Bunga Baru</h1>
        </div>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs">
        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Nama Rangkaian Bunga *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm"
                           placeholder="Contoh: Sweet Peony & Rose Romance">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Kategori *</label>
                    <select name="category_id" required class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] bg-white text-xs">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Harga Normal (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" required
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm"
                           placeholder="450000">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Harga Promo/Diskon (Opsional)</label>
                    <input type="number" name="discount_price" value="{{ old('discount_price') }}"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm"
                           placeholder="399000">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Kode SKU *</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" required
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm"
                           placeholder="HB-ROSE-01">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Stok Tersedia *</label>
                    <input type="number" name="stock" value="{{ old('stock', 20) }}" required
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Status Publikasi *</label>
                    <select name="status" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white text-xs">
                        <option value="active">Active (Tampil di Toko)</option>
                        <option value="inactive">Inactive (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-medium mb-1 text-[#2A2626]">URL Foto Utama Bunga</label>
                <input type="url" name="image_url" value="{{ old('image_url') }}"
                       placeholder="https://images.unsplash.com/..."
                       class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-xs">
            </div>

            <div>
                <label class="block font-medium mb-1 text-[#2A2626]">Deskripsi Lengkap & Komposisi Bunga *</label>
                <textarea name="description" rows="4" required
                          placeholder="Jelaskan jenis bunga, nuansa warna, pembungkus, dan saran momen yang cocok..."
                          class="w-full p-3 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-xs">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="rounded text-[#B85D66] focus:ring-[#B85D66]">
                    <span>Tampilkan di Produk Pilihan (Featured)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_best_seller" value="1" class="rounded text-[#B85D66] focus:ring-[#B85D66]">
                    <span>Tandai sebagai Best Seller</span>
                </label>
            </div>

            <div class="pt-4 border-t border-[#EADFD4]">
                <button type="submit" class="py-3 px-8 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider text-xs transition shadow-sm">
                    Simpan Produk Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
