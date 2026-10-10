@extends('layouts.admin')

@section('title', 'Kategori Bunga — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2A2626]">Kategori Rangkaian Bunga</h1>
            <p class="text-xs text-[#6B6565] mt-1">Kelola foto sampul, nama kategori, dan deskripsi untuk katalog toko.</p>
        </div>
        <div class="text-xs text-[#9B9494]">
            Total: <strong class="text-[#2A2626]">{{ $categories->count() }} Kategori</strong>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3 sm:p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-3 sm:p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Categories Table (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                        <tr>
                            <th class="p-3.5 sm:p-4">Foto & Kategori</th>
                            <th class="p-3.5 sm:p-4">Deskripsi</th>
                            <th class="p-3.5 sm:p-4">Jumlah Produk</th>
                            <th class="p-3.5 sm:p-4">Status</th>
                            <th class="p-3.5 sm:p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3ECE4]">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-[#FAF8F5]/50 transition">
                                <td class="p-3.5 sm:p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $cat->image_url }}"
                                             alt="{{ $cat->name }}" class="w-12 h-12 rounded-xl object-cover border border-[#EADFD4] flex-shrink-0">
                                        <div class="min-w-0">
                                            <span class="font-bold text-[#2A2626] block truncate">{{ $cat->name }}</span>
                                            <span class="text-[10px] text-[#9B9494] font-mono block">/{{ $cat->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3.5 sm:p-4 text-[#6B6565] max-w-xs truncate">
                                    {{ $cat->description ?: '-' }}
                                </td>
                                <td class="p-3.5 sm:p-4 font-semibold text-[#2A2626] whitespace-nowrap">
                                    {{ $cat->products_count }} produk
                                </td>
                                <td class="p-3.5 sm:p-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $cat->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $cat->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="p-3.5 sm:p-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5 sm:gap-2">
                                        <button type="button"
                                           class="open-edit-modal-btn px-2.5 py-1 rounded-lg bg-[#FAF8F5] border border-[#EADFD4] text-[#B85D66] hover:bg-[#F8E8EA] hover:border-[#B85D66] font-semibold transition inline-flex items-center gap-1 cursor-pointer"
                                           data-id="{{ $cat->id }}"
                                           data-name="{{ $cat->name }}"
                                           data-description="{{ $cat->description }}"
                                           data-image="{{ $cat->image }}"
                                           data-image-url="{{ $cat->image_url }}"
                                           data-sort-order="{{ $cat->sort_order }}"
                                           data-status="{{ $cat->status ? 'active' : 'inactive' }}"
                                           data-action="{{ route('admin.categories.update', $cat->id) }}"
                                           title="Edit Foto & Data Kategori">
                                            ✏️ Edit
                                        </button>

                                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Kategori">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-[#9B9494]">
                                    Belum ada kategori rangkaian bunga.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Add New Category Form (4 cols) -->
        <div class="lg:col-span-4 bg-white p-5 sm:p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
            <div>
                <h2 class="font-serif text-lg text-[#2A2626]">Tambah Kategori Baru</h2>
                <p class="text-[11px] text-[#9B9494] mt-0.5">Unggah foto sampul bunga atau masukkan URL untuk kategori baru.</p>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Nama Kategori *</label>
                    <input type="text" name="name" required placeholder="Contoh: Bloom Box Cantik" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <!-- Foto Sampul Kategori -->
                <div class="space-y-2">
                    <label class="block font-medium text-[#2A2626]">
                        Foto Sampul Kategori <span class="text-[10px] text-[#9B9494] font-normal">(Opsional)</span>
                    </label>

                    <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-3">
                        <div>
                            <span class="block text-[11px] font-semibold text-[#6B6565] mb-1">Opsi A: Unggah Berkas Foto</span>
                            <input type="file" name="image_file" accept="image/*" id="createCategoryFileInput"
                                   class="w-full max-w-full text-[11px] text-[#6B6565] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#B85D66] file:text-white hover:file:bg-[#8F3B44]">
                        </div>

                        <div class="relative flex py-1 items-center">
                            <div class="flex-grow border-t border-[#EADFD4]"></div>
                            <span class="flex-shrink mx-2 text-[10px] text-[#9B9494] uppercase tracking-wider">atau</span>
                            <div class="flex-grow border-t border-[#EADFD4]"></div>
                        </div>

                        <div>
                            <span class="block text-[11px] font-semibold text-[#6B6565] mb-1">Opsi B: Tautan / URL Gambar</span>
                            <input type="url" name="image_url" id="createCategoryUrlInput"
                                   placeholder="https://images.unsplash.com/photo-..."
                                   class="w-full p-2 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] bg-white text-xs">
                        </div>

                        <!-- Pratinjau Foto Baru -->
                        <div id="createCategoryPreviewContainer" class="hidden pt-2 border-t border-[#EADFD4]">
                            <span class="block text-[10px] text-[#9B9494] mb-1">Pratinjau Foto:</span>
                            <img id="createCategoryPreviewImg" src="" alt="Pratinjau" class="w-16 h-16 rounded-xl object-cover border border-[#D8BABF]">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Penjelasan rangkaian kategori ini..." class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="1" class="w-full p-2.5 rounded-xl border border-[#D8BABF]">
                    </div>
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Status</label>
                        <select name="status" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white">
                            <option value="active">Active (Tampil)</option>
                            <option value="inactive">Inactive (Sembunyi)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider transition shadow-2xs">
                    + Simpan Kategori
                </button>
            </form>
        </div>

    </div>
</div>

<!-- Modal Edit Kategori Cepat (Interactive & Mobile Friendly) -->
<div id="categoryEditModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-3 sm:p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl border border-[#EADFD4] overflow-hidden flex flex-col max-h-[92vh]">
        <!-- Modal Header -->
        <div class="p-4 sm:p-5 border-b border-[#EADFD4] flex items-center justify-between bg-[#FAF8F5]">
            <div>
                <h3 class="font-serif text-lg sm:text-xl text-[#2A2626]" id="modalCategoryTitle">Edit Kategori Bunga</h3>
                <p class="text-[11px] text-[#9B9494]">Perbarui foto sampul, deskripsi, atau nama kategori.</p>
            </div>
            <button type="button" id="closeCategoryModalBtn" class="w-8 h-8 rounded-lg text-[#9B9494] hover:text-[#2A2626] hover:bg-[#EADFD4] flex items-center justify-center text-xl transition">
                &times;
            </button>
        </div>

        <!-- Modal Body Form -->
        <form id="modalEditCategoryForm" action="" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 overflow-y-auto space-y-4 text-xs">
            @csrf
            @method('PUT')

            <!-- Foto Saat Ini & Pilihan Ganti Foto -->
            <div class="p-3.5 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-3">
                <label class="block font-medium text-[#2A2626]">Foto Sampul Saat Ini</label>
                <div class="flex items-center gap-3">
                    <img id="modalPreviewImg" src="" alt="Pratinjau Kategori" class="w-16 h-16 rounded-xl object-cover border border-[#D8BABF] shadow-2xs flex-shrink-0">
                    <div class="text-[11px] text-[#6B6565] min-w-0">
                        <span class="font-semibold text-[#2A2626] block">Pratinjau Foto</span>
                        <span class="text-[10px] text-[#9B9494]">Foto akan langsung diperbarui setelah disimpan.</span>
                    </div>
                </div>

                <div class="pt-2.5 border-t border-[#EADFD4] space-y-2.5">
                    <div>
                        <span class="block text-[11px] font-semibold text-[#6B6565] mb-1">Unggah Foto Baru (Berkas)</span>
                        <input type="file" name="image_file" accept="image/*" id="modalFileInput"
                               class="w-full max-w-full text-[11px] text-[#6B6565] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#B85D66] file:text-white hover:file:bg-[#8F3B44]">
                    </div>

                    <div class="relative flex py-0.5 items-center">
                        <div class="flex-grow border-t border-[#EADFD4]"></div>
                        <span class="flex-shrink mx-2 text-[10px] text-[#9B9494] uppercase tracking-wider">atau ganti URL</span>
                        <div class="flex-grow border-t border-[#EADFD4]"></div>
                    </div>

                    <div>
                        <input type="url" name="image_url" id="modalUrlInput" placeholder="https://images.unsplash.com/..."
                               class="w-full p-2 rounded-xl border border-[#D8BABF] bg-white text-xs focus:ring-1 focus:ring-[#B85D66]">
                    </div>
                </div>
            </div>

            <!-- Nama Kategori -->
            <div>
                <label class="block font-medium mb-1 text-[#2A2626]">Nama Kategori *</label>
                <input type="text" name="name" id="modalNameInput" required
                       class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm font-semibold">
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block font-medium mb-1 text-[#2A2626]">Deskripsi Singkat</label>
                <textarea name="description" id="modalDescInput" rows="2"
                          class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]"></textarea>
            </div>

            <!-- Sort Order & Status -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="modalSortInput"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF]">
                </div>
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Status</label>
                    <select name="status" id="modalStatusInput" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white">
                        <option value="active">Active (Tampil)</option>
                        <option value="inactive">Inactive (Sembunyi)</option>
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-3 border-t border-[#EADFD4] flex items-center justify-end gap-2.5">
                <button type="button" id="cancelCategoryModalBtn" class="px-4 py-2 rounded-xl border border-[#D8BABF] text-[#6B6565] hover:bg-[#FAF8F5] transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-bold uppercase tracking-wider transition shadow-2xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Create category preview
        const createFileInput = document.getElementById('createCategoryFileInput');
        const createUrlInput = document.getElementById('createCategoryUrlInput');
        const createPreviewContainer = document.getElementById('createCategoryPreviewContainer');
        const createPreviewImg = document.getElementById('createCategoryPreviewImg');

        if (createFileInput) {
            createFileInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        createPreviewImg.src = e.target.result;
                        createPreviewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(this.files[0]);
                    if (createUrlInput) createUrlInput.value = '';
                }
            });
        }

        if (createUrlInput) {
            createUrlInput.addEventListener('input', function () {
                if (this.value.trim() !== '') {
                    createPreviewImg.src = this.value;
                    createPreviewContainer.classList.remove('hidden');
                    if (createFileInput) createFileInput.value = '';
                }
            });
        }

        // Edit Modal elements
        const modal = document.getElementById('categoryEditModal');
        const form = document.getElementById('modalEditCategoryForm');
        const titleEl = document.getElementById('modalCategoryTitle');
        const nameInput = document.getElementById('modalNameInput');
        const descInput = document.getElementById('modalDescInput');
        const sortInput = document.getElementById('modalSortInput');
        const statusInput = document.getElementById('modalStatusInput');
        const previewImg = document.getElementById('modalPreviewImg');
        const fileInput = document.getElementById('modalFileInput');
        const urlInput = document.getElementById('modalUrlInput');
        const closeBtn = document.getElementById('closeCategoryModalBtn');
        const cancelBtn = document.getElementById('cancelCategoryModalBtn');

        function openModal(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const desc = btn.getAttribute('data-description') || '';
            const imageUrl = btn.getAttribute('data-image-url') || '';
            const rawImage = btn.getAttribute('data-image') || '';
            const sort = btn.getAttribute('data-sort-order') || 0;
            const status = btn.getAttribute('data-status') || 'active';
            const action = btn.getAttribute('data-action');

            form.action = action;
            titleEl.textContent = 'Edit Kategori: ' + name;
            nameInput.value = name;
            descInput.value = desc;
            sortInput.value = sort;
            statusInput.value = status;
            previewImg.src = imageUrl;

            if (rawImage.startsWith('http')) {
                urlInput.value = rawImage;
            } else {
                urlInput.value = '';
            }
            fileInput.value = '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.querySelectorAll('.open-edit-modal-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                openModal(this);
            });
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });
        }

        // Live Preview for modal
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                    urlInput.value = '';
                }
            });
        }

        if (urlInput) {
            urlInput.addEventListener('input', function () {
                if (this.value.trim() !== '') {
                    previewImg.src = this.value;
                    fileInput.value = '';
                }
            });
        }
    });
</script>
@endsection
