@extends('layouts.admin')

@section('title', 'Kelola Banner Beranda — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Banner Beranda (Hero Showcase)</h1>
            <p class="text-xs text-[#6B6565] mt-1">
                Atur foto banner, teks promosi, dan tombol yang tampil pada bagian depan toko Anda.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#EADFD4] text-xs font-semibold text-[#6B6565] hover:text-[#B85D66] hover:border-[#B85D66] transition shadow-xs">
                <span>Lihat di Beranda</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left: Daftar Banner (7 Cols) -->
        <div class="lg:col-span-7 space-y-4 min-w-0">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#2A2626] uppercase tracking-wider">
                    Daftar Banner Aktif & Tersimpan ({{ $banners->count() }})
                </span>
                <span class="text-[11px] text-[#9B9494]">
                    Jika lebih dari 1 aktif, beranda akan otomatis menjadi slider/carousel
                </span>
            </div>

            @if($banners->isEmpty())
                <div class="bg-white p-8 rounded-2xl border border-[#EADFD4] text-center space-y-3">
                    <div class="w-12 h-12 mx-auto rounded-full bg-[#FAF8F5] border border-[#EADFD4] flex items-center justify-center text-xl">
                        🖼️
                    </div>
                    <p class="text-xs text-[#6B6565]">Belum ada banner yang dikonfigurasi. Tambahkan banner pertama Anda melalui formulir di samping.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($banners as $b)
                        <div class="bg-white rounded-2xl border border-[#EADFD4] p-5 shadow-xs transition hover:shadow-md">
                            <div class="flex gap-4">
                                <!-- Banner Image Thumbnail -->
                                <div class="w-24 sm:w-32 rounded-xl overflow-hidden border border-[#EADFD4] bg-[#F3ECE4] flex-shrink-0 relative group">
                                    <img src="{{ $b->image_url }}" alt="{{ $b->title ?? 'Banner' }}" class="w-full object-cover">
                                    <a href="{{ $b->image_url }}" target="_blank" class="absolute inset-0 bg-black/40 text-white flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">
                                        Perbesar ↗
                                    </a>
                                </div>

                                <!-- Banner Info -->
                                <div class="flex-1 space-y-2 text-xs min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $b->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $b->status === 'active' ? '● Aktif di Beranda' : '○ Nonaktif' }}
                                            </span>
                                            <span class="text-[10px] text-[#9B9494]">Urutan: #{{ $b->sort_order }}</span>
                                        </div>

                                        <!-- Toggle Status Form -->
                                        <form action="{{ route('admin.banners.toggle', $b->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-medium {{ $b->status === 'active' ? 'text-amber-700 hover:underline' : 'text-green-700 hover:underline' }}">
                                                {{ $b->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>

                                    @if($b->subtitle)
                                        <span class="inline-block text-[10px] font-semibold text-[#B85D66] uppercase tracking-wider">
                                            {{ $b->subtitle }}
                                        </span>
                                    @endif

                                    <h3 class="font-serif text-base text-[#2A2626] line-clamp-2 leading-snug">
                                        {{ $b->title ?: 'Sentuhan Bunga Segar (Default)' }}
                                    </h3>

                                    @if($b->description)
                                        <p class="text-[#6B6565] line-clamp-2 text-[11px]">
                                            {{ $b->description }}
                                        </p>
                                    @endif

                                    <!-- Link Info & Floating Quote Badge -->
                                    <div class="pt-2 border-t border-[#F3ECE4] space-y-1 text-[11px] text-[#9B9494]">
                                        @if($b->link_url)
                                            <div class="flex items-center gap-1.5 truncate">
                                                <span>🔗 Tujuan:</span>
                                                <a href="{{ $b->link_url }}" target="_blank" class="text-[#B85D66] hover:underline font-mono truncate">
                                                    {{ $b->link_url }}
                                                </a>
                                                @if($b->button_text)
                                                    <span class="text-[#2A2626] font-medium">({{ $b->button_text }})</span>
                                                @endif
                                            </div>
                                        @endif

                                        @if($b->badge_text)
                                            <div class="flex items-start gap-1.5 text-[#2A2626]">
                                                <span class="text-[#C5A059]">★</span>
                                                <span class="italic truncate">{{ $b->badge_text }}</span>
                                                @if($b->badge_author)
                                                    <span class="text-[#9B9494]">{{ $b->badge_author }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="pt-2 flex items-center justify-end gap-3 text-xs">
                                        <a href="{{ route('admin.banners.edit', $b->id) }}"
                                           class="px-3 py-1.5 rounded-lg border border-[#D8BABF] text-[#B85D66] hover:bg-[#F8E8EA] transition font-medium">
                                            ✏️ Edit
                                        </a>

                                        <form action="{{ route('admin.banners.destroy', $b->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition font-medium">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Form Tambah Banner Baru (5 Cols) -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-5 text-xs">
            <div>
                <h2 class="font-serif text-xl text-[#2A2626]">Tambah Banner Baru</h2>
                <p class="text-[11px] text-[#9B9494] mt-0.5">Unggah foto baru atau tempel URL untuk dijadikan banner utama.</p>
            </div>

            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Upload Image or URL -->
                <div class="space-y-2">
                    <label class="block font-medium text-[#2A2626]">
                        Foto Banner * <span class="text-[10px] text-[#9B9494] font-normal">(Rasio bebas, bisa landscape atau potret)</span>
                    </label>

                    <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-3">
                        <div>
                            <span class="block text-[11px] font-semibold text-[#6B6565] mb-1">Opsi A: Unggah Berkas Foto</span>
                            <input type="file" name="image_file" accept="image/*"
                                   id="bannerFileInput"
                                   class="w-full text-[11px] text-[#6B6565] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#B85D66] file:text-white hover:file:bg-[#8F3B44]">
                        </div>

                        <div class="relative flex py-1 items-center">
                            <div class="flex-grow border-t border-[#EADFD4]"></div>
                            <span class="flex-shrink mx-2 text-[10px] text-[#9B9494] uppercase tracking-wider">atau</span>
                            <div class="flex-grow border-t border-[#EADFD4]"></div>
                        </div>

                        <div>
                            <span class="block text-[11px] font-semibold text-[#6B6565] mb-1">Opsi B: Tautan / URL Gambar</span>
                            <input type="url" name="image_url"
                                   id="bannerUrlInput"
                                   value="{{ old('image_url') }}"
                                   placeholder="https://images.unsplash.com/photo-..."
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] bg-white">
                        </div>

                        <!-- Live Image Preview -->
                        <div id="imagePreviewContainer" class="hidden pt-2 border-t border-[#EADFD4]">
                            <span class="block text-[10px] text-[#9B9494] mb-1">Pratinjau Foto:</span>
                            <img id="imagePreviewImg" src="" alt="Pratinjau" class="w-24 object-cover rounded-lg border border-[#EADFD4]">
                        </div>
                    </div>
                </div>

                <!-- Judul Utama -->
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">
                        Judul Utama Banner
                        <span class="text-[10px] text-[#9B9494] font-normal">(Biarkan kosong untuk menggunakan teks default Desy Florist)</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           placeholder="Contoh: Sentuhan Bunga Segar, Dirangkai Tulus"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <!-- Subtitle / Badge -->
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">
                        Label Badge / Subjudul
                        <span class="text-[10px] text-[#9B9494] font-normal">(Muncul sebagai pill di atas judul)</span>
                    </label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                           placeholder="Contoh: Artisan Florist & Same-Day Delivery Jakarta"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <!-- Deskripsi Singkat -->
                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Deskripsi Singkat</label>
                    <textarea name="description" rows="2"
                              placeholder="Dari buket tangan romantis bernuansa pastel hingga bunga meja berkelas..."
                              class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">{{ old('description') }}</textarea>
                </div>

                <!-- Tombol & Link -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Teks Tombol Utama</label>
                        <input type="text" name="button_text" value="{{ old('button_text', 'Jelajahi Katalog Bunga') }}"
                               placeholder="Jelajahi Katalog Bunga"
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Link Tombol / Foto</label>
                        <input type="text" name="link_url" value="{{ old('link_url', '/shop') }}"
                               placeholder="/shop atau /categories/bunga-meja"
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>
                </div>

                <!-- Tombol Kedua -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Teks Tombol Kedua</label>
                        <input type="text" name="secondary_button_text" value="{{ old('secondary_button_text', '✨ Rangkai Buket Sendiri') }}"
                               placeholder="✨ Rangkai Buket Sendiri"
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Link Tombol Kedua</label>
                        <input type="text" name="secondary_button_url" value="{{ old('secondary_button_url', '/custom-bouquet') }}"
                               placeholder="/custom-bouquet"
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>
                </div>

                <!-- Badge Mengambang (Floating Testimonial / Quality Tag) -->
                <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-2">
                    <span class="block text-[11px] font-semibold text-[#6B6565]">
                        Badge Mengambang di Atas Foto (Opsional)
                    </span>
                    <div>
                        <label class="block text-[10px] text-[#9B9494] mb-0.5">Teks Kutipan / Ulasan</label>
                        <input type="text" name="badge_text" value="{{ old('badge_text') }}"
                               placeholder='"Bunganya sangat segar dan wangi, kemasannya rapi sekali!"'
                               class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] text-[#9B9494] mb-0.5">Keterangan / Nama Pengutip</label>
                        <input type="text" name="badge_author" value="{{ old('badge_author') }}"
                               placeholder="— Alika, Jakarta Selatan"
                               class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white text-xs">
                    </div>
                </div>

                <!-- Sort Order & Status -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 1) }}"
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Status</label>
                        <select name="status" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white focus:ring-1 focus:ring-[#B85D66]">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif (Tampil)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif (Draft)</option>
                        </select>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider text-xs transition shadow-sm">
                    + Simpan Banner Baru
                </button>
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
    // Live preview for file upload & image URL
    const fileInput = document.getElementById('bannerFileInput');
    const urlInput = document.getElementById('bannerUrlInput');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImg = document.getElementById('imagePreviewImg');

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

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
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
                        aspectRatio: NaN, // Bebas
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
            
            // Get cropped canvas (max width to prevent huge files)
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
                fileInput.files = dataTransfer.files;

                // Show preview
                previewImg.src = URL.createObjectURL(blob);
                previewContainer.classList.remove('hidden');
                
                // Clear URL input if file is cropped
                if(urlInput) urlInput.value = '';

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
                fileInput.value = ''; // Reset file input if canceled
            });
        }
    });

    if (urlInput) {
        urlInput.addEventListener('input', function() {
            if (this.value.trim().length > 10) {
                previewImg.src = this.value.trim();
                previewContainer.classList.remove('hidden');
            } else if (!fileInput.files || !fileInput.files[0]) {
                previewContainer.classList.add('hidden');
            }
        });
    }
</script>
@endsection
