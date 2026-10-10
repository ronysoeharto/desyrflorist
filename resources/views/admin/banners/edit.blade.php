@extends('layouts.admin')

@section('title', 'Edit Banner Beranda — Desy Florist Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.banners.index') }}" class="text-xs text-[#B85D66] hover:underline flex items-center gap-1">
                    ← Kembali ke Daftar Banner
                </a>
            </div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Edit Banner Beranda</h1>
        </div>

        <a href="{{ route('home') }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#EADFD4] text-xs font-semibold text-[#6B6565] hover:text-[#B85D66] hover:border-[#B85D66] transition">
            Lihat Beranda ↗
        </a>
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

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs text-xs">
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Image Section -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start pb-6 border-b border-[#EADFD4]">
                <!-- Current Image Preview -->
                <div class="md:col-span-4 space-y-2">
                    <label class="block font-medium text-[#2A2626]">Foto Banner Saat Ini</label>
                    <div class="rounded-xl overflow-hidden border border-[#EADFD4] bg-[#F3ECE4] relative">
                        <img id="currentBannerPreview" src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full object-cover">
                    </div>
                </div>

                <!-- Replace Image Controls -->
                <div class="md:col-span-8 space-y-4">
                    <label class="block font-medium text-[#2A2626]">
                        Ganti Foto Banner <span class="text-[10px] text-[#9B9494] font-normal">(Opsional, biarkan kosong jika tetap memakai foto saat ini)</span>
                    </label>

                    <div class="p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-3">
                        <div>
                            <span class="block text-[11px] font-semibold text-[#6B6565] mb-1">Unggah Berkas Baru (JPG/PNG/WebP, maks 5MB)</span>
                            <input type="file" name="image_file" accept="image/*" id="editFileInput"
                                   class="w-full text-[11px] text-[#6B6565] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#B85D66] file:text-white hover:file:bg-[#8F3B44]">
                        </div>

                        <div class="relative flex py-1 items-center">
                            <div class="flex-grow border-t border-[#EADFD4]"></div>
                            <span class="flex-shrink mx-2 text-[10px] text-[#9B9494] uppercase tracking-wider">atau</span>
                            <div class="flex-grow border-t border-[#EADFD4]"></div>
                        </div>

                        <div>
                            <span class="block text-[11px] font-semibold text-[#6B6565] mb-1">Tautan / URL Gambar Baru</span>
                            <input type="url" name="image_url" id="editUrlInput"
                                   value="{{ old('image_url', str_starts_with($banner->image_path, 'http') ? $banner->image_path : '') }}"
                                   placeholder="https://images.unsplash.com/photo-..."
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] bg-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1 text-[#2A2626]">
                        Judul Utama Banner
                    </label>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}"
                           placeholder="Contoh: Buket Bunga Segar & Momen Istimewa"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-1 text-[#2A2626]">Label Badge / Subjudul <span class="text-[10px] text-[#9B9494] font-normal">(Opsional)</span></label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}"
                           placeholder="Contoh: Koleksi Spesial Desy Florist"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-1 text-[#2A2626]">Deskripsi Banner <span class="text-[10px] text-[#9B9494] font-normal">(Opsional)</span></label>
                    <textarea name="description" rows="3"
                              placeholder="Tuliskan deskripsi atau pesan promosi singkat..."
                              class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">{{ old('description', $banner->description) }}</textarea>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Teks Tombol Utama</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}"
                           placeholder="Jelajahi Katalog Bunga"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Link Tombol / Foto Banner</label>
                    <input type="text" name="link_url" value="{{ old('link_url', $banner->link_url) }}"
                           placeholder="/shop"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Teks Tombol Kedua <span class="text-[10px] text-[#9B9494] font-normal">(Opsional)</span></label>
                    <input type="text" name="secondary_button_text" value="{{ old('secondary_button_text', $banner->secondary_button_text) }}"
                           placeholder="Contoh: ✨ Rangkai Buket Sendiri"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Link Tombol Kedua <span class="text-[10px] text-[#9B9494] font-normal">(Opsional)</span></label>
                    <input type="text" name="secondary_button_url" value="{{ old('secondary_button_url', $banner->secondary_button_url) }}"
                           placeholder="Contoh: /custom-bouquet"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <!-- Floating Quote Badge -->
                <div class="md:col-span-2 p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-2">
                    <span class="block text-[11px] font-semibold text-[#6B6565]">
                        Badge Mengambang di Atas Foto (Opsional, kosongkan jika tidak ingin ditampilkan)
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] text-[#9B9494] mb-0.5">Teks Kutipan / Badge</label>
                            <input type="text" name="badge_text" value="{{ old('badge_text', $banner->badge_text) }}"
                                   placeholder='Contoh: "Bunga segar dirangkai dengan sepenuh hati"'
                                   class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] text-[#9B9494] mb-0.5">Keterangan / Nama Pengutip (Opsional)</label>
                            <input type="text" name="badge_author" value="{{ old('badge_author', $banner->badge_author) }}"
                                   placeholder="Contoh: Desy Florist Signature"
                                   class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white text-xs">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Urutan Tampil (Sort Order)</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Status Banner</label>
                    <select name="status" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white focus:ring-1 focus:ring-[#B85D66]">
                        <option value="active" {{ old('status', $banner->status) === 'active' ? 'selected' : '' }}>Aktif (Tampil di Beranda)</option>
                        <option value="inactive" {{ old('status', $banner->status) === 'inactive' ? 'selected' : '' }}>Nonaktif (Draft)</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#EADFD4]">
                <a href="{{ route('admin.banners.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-[#D8BABF] text-[#6B6565] hover:bg-[#FAF8F5] transition font-medium">
                    Batal
                </a>
                <button type="submit"
                        class="px-7 py-2.5 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider transition shadow-sm">
                    Simpan Perubahan Banner
                </button>
            </div>
        </form>
    </div>
    </div>
</div>

<!-- Cropper Modal -->
<div id="cropperModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl flex flex-col max-h-[90vh] shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-[#EADFD4] flex justify-between items-center bg-[#FAF8F5]">
            <h3 class="font-serif text-lg text-[#2A2626]">Sesuaikan Ukuran Foto (Bebas)</h3>
            <button type="button" id="closeCropperBtn" class="text-[#9B9494] hover:text-red-500 text-2xl leading-none">&times;</button>
        </div>
        <div class="p-4 flex-1 overflow-hidden min-h-[300px] md:min-h-[400px] bg-gray-900 flex items-center justify-center">
            <div class="w-full h-full relative flex items-center justify-center">
                <img id="cropperImage" src="" alt="Cropper" class="max-w-full block" style="max-height: 70vh;">
            </div>
        </div>
        <div class="p-4 border-t border-[#EADFD4] flex justify-end gap-3 bg-[#FAF8F5]">
            <button type="button" id="cancelCropBtn" class="px-5 py-2.5 rounded-xl border border-[#D8BABF] text-[#6B6565] hover:bg-white text-xs font-medium transition">Batal</button>
            <button type="button" id="applyCropBtn" class="px-6 py-2.5 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold shadow-sm transition">Terapkan Potongan</button>
        </div>
    </div>
</div>

<!-- Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<script>
    const editFile = document.getElementById('editFileInput');
    const editUrl = document.getElementById('editUrlInput');
    const preview = document.getElementById('currentBannerPreview');

    // Cropper elements
    const cropperModal = document.getElementById('cropperModal');
    const cropperImage = document.getElementById('cropperImage');
    const closeCropperBtn = document.getElementById('closeCropperBtn');
    const cancelCropBtn = document.getElementById('cancelCropBtn');
    const applyCropBtn = document.getElementById('applyCropBtn');
    let cropper = null;
    let originalFileName = 'banner.jpg';
    let originalFileType = 'image/jpeg';

    function hideCropperModal() {
        cropperModal.classList.add('hidden');
        cropperModal.classList.remove('flex');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    }

    if (editFile) {
        editFile.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                originalFileName = file.name;
                originalFileType = file.type;

                const reader = new FileReader();
                reader.onload = function(evt) {
                    // Show modal
                    cropperModal.classList.remove('hidden');
                    cropperModal.classList.add('flex');
                    cropperImage.src = evt.target.result;

                    // Initialize cropper
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: NaN,
                        viewMode: 2,
                        autoCropArea: 1,
                        background: false,
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Apply Crop
    if (applyCropBtn) {
        applyCropBtn.addEventListener('click', function() {
            if (!cropper) return;
            
            // Get cropped canvas
            const canvas = cropper.getCroppedCanvas({
                maxWidth: 1600,
                maxHeight: 1600,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            // Convert to Blob and set it back to the file input
            canvas.toBlob(function(blob) {
                // Create a new File object
                const croppedFile = new File([blob], "cropped_" + originalFileName, { 
                    type: originalFileType, 
                    lastModified: new Date().getTime() 
                });

                // Use DataTransfer to simulate a user file selection
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                editFile.files = dataTransfer.files;

                // Show preview
                preview.src = URL.createObjectURL(blob);
                
                // Clear URL input if file is cropped
                if(editUrl) editUrl.value = '';

                hideCropperModal();
            }, originalFileType, 0.9);
        });
    }

    // Cancel Crop
    const cancelHandlers = [closeCropperBtn, cancelCropBtn];
    cancelHandlers.forEach(btn => {
        if(btn) {
            btn.addEventListener('click', function() {
                hideCropperModal();
                editFile.value = ''; // Reset file input if canceled
            });
        }
    });

    if (editUrl) {
        editUrl.addEventListener('input', function() {
            if (this.value.trim().length > 10) {
                preview.src = this.value.trim();
            }
        });
    }
</script>
@endsection
