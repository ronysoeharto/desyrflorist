@extends('layouts.admin')

@section('title', 'Edit Kategori: ' . $category->name . ' — Desy Florist Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}" 
           class="w-9 h-9 rounded-xl bg-white border border-[#EADFD4] flex items-center justify-center text-[#6B6565] hover:text-[#B85D66] hover:border-[#B85D66] transition shadow-2xs flex-shrink-0" 
           title="Kembali ke Daftar Kategori">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="min-w-0">
            <nav class="text-[11px] text-[#9B9494] mb-0.5 flex items-center gap-1.5">
                <a href="{{ route('admin.categories.index') }}" class="hover:text-[#B85D66] transition">Kategori Bunga</a>
                <span>/</span>
                <span class="text-[#2A2626] font-medium">Edit Kategori</span>
            </nav>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2A2626] truncate">Edit: {{ $category->name }}</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-5 sm:p-7 rounded-2xl border border-[#EADFD4] shadow-2xs">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf
            @method('PUT')

            <!-- Current Image & Change Option -->
            <div class="p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-4">
                <div>
                    <label class="block font-medium text-[#2A2626] mb-2">Foto Kategori Saat Ini</label>
                    <div class="flex items-center gap-4">
                        <img id="categoryCurrentPreview" src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-20 h-20 rounded-xl object-cover border border-[#D8BABF] shadow-xs">
                        <div class="text-[11px] text-[#6B6565] space-y-1 min-w-0">
                            <span class="font-semibold text-[#2A2626] block">Pratinjau Foto</span>
                            <span class="text-[#9B9494] block break-all text-[10px]">
                                {{ $category->image ? (str_starts_with($category->image, 'http') ? 'Tautan Eksternal' : 'Tersimpan di Penyimpanan Florist') : 'Belum ada foto khusus' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-[#EADFD4] space-y-3">
                    <div>
                        <span class="block text-[11px] font-semibold text-[#6B6565] mb-1">Opsi A: Unggah Berkas Foto Baru</span>
                        <input type="file" name="image_file" accept="image/*" id="editCategoryFileInput"
                               class="w-full max-w-full text-xs text-[#6B6565] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#B85D66] file:text-white hover:file:bg-[#8F3B44]">
                        <p class="text-[10px] text-[#9B9494] mt-1">Format JPG, PNG, WEBP. Maks 10MB.</p>
                    </div>

                    <div class="relative flex py-1 items-center">
                        <div class="flex-grow border-t border-[#EADFD4]"></div>
                        <span class="flex-shrink mx-2 text-[10px] text-[#9B9494] uppercase tracking-wider">atau</span>
                        <div class="flex-grow border-t border-[#EADFD4]"></div>
                    </div>

                    <div>
                        <span class="block text-[11px] font-semibold text-[#6B6565] mb-1">Opsi B: Tautan / URL Gambar</span>
                        <input type="url" name="image_url" id="editCategoryUrlInput"
                               value="{{ old('image_url', str_starts_with($category->image, 'http') ? $category->image : '') }}"
                               placeholder="https://images.unsplash.com/photo-..."
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] bg-white text-xs">
                    </div>
                </div>
            </div>

            <!-- Nama Kategori -->
            <div>
                <label class="block font-medium mb-1 text-[#2A2626]">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                       class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm font-semibold">
                <span class="text-[10px] text-[#9B9494] mt-0.5 block">Slug saat ini: <code>/categories/{{ $category->slug }}</code></span>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block font-medium mb-1 text-[#2A2626]">Deskripsi Singkat</label>
                <textarea name="description" rows="3" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">{{ old('description', $category->description) }}</textarea>
            </div>

            <!-- Sort Order & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Status</label>
                    <select name="status" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white focus:ring-1 focus:ring-[#B85D66]">
                        <option value="active" {{ old('status', $category->status ? 'active' : 'inactive') === 'active' ? 'selected' : '' }}>Active (Aktif Ditampilkan)</option>
                        <option value="inactive" {{ old('status', $category->status ? 'active' : 'inactive') === 'inactive' ? 'selected' : '' }}>Inactive (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-[#EADFD4] flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.categories.index') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-[#D8BABF] text-[#6B6565] hover:bg-[#FAF8F5] text-center font-medium transition">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-bold uppercase tracking-wider transition text-center shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('editCategoryFileInput');
    const urlInput = document.getElementById('editCategoryUrlInput');
    const previewImg = document.getElementById('categoryCurrentPreview');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
                if (urlInput) urlInput.value = '';
            }
        });
    }

    if (urlInput) {
        urlInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                previewImg.src = this.value;
                if (fileInput) fileInput.value = '';
            }
        });
    }
</script>
@endsection
