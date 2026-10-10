@extends('layouts.admin')

@section('title', 'Edit Opsi Kustomisasi — Admin Desy Florist')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.custom-options.index') }}" 
           class="w-9 h-9 rounded-xl bg-white border border-[#EADFD4] flex items-center justify-center text-[#6B6565] hover:text-[#B85D66] hover:border-[#B85D66] transition shadow-2xs flex-shrink-0" 
           title="Kembali ke Daftar Opsi">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="min-w-0">
            <nav class="text-[11px] text-[#9B9494] mb-0.5 flex items-center gap-1.5">
                <a href="{{ route('admin.custom-options.index') }}" class="hover:text-[#B85D66] transition">Kustomisasi Buket</a>
                <span>/</span>
                <span class="text-[#2A2626] font-medium">Edit Opsi</span>
            </nav>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2A2626] truncate">Edit: {{ $customOption->name }}</h1>
        </div>
    </div>

    <form action="{{ route('admin.custom-options.update', $customOption->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-5 sm:p-7 rounded-2xl border border-[#EADFD4] shadow-2xs space-y-5">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-xs font-semibold text-[#2A2626] mb-1.5">Tipe Opsi <span class="text-red-500">*</span></label>
            <select name="type" required class="w-full text-sm p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-white">
                <option value="flower" {{ $customOption->type == 'flower' ? 'selected' : '' }}>Bunga (Flower)</option>
                <option value="wrapping" {{ $customOption->type == 'wrapping' ? 'selected' : '' }}>Kertas Pembungkus (Wrapping)</option>
                <option value="ribbon" {{ $customOption->type == 'ribbon' ? 'selected' : '' }}>Pita (Ribbon)</option>
                <option value="addon" {{ $customOption->type == 'addon' ? 'selected' : '' }}>Aksesoris (Addon)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#2A2626] mb-1.5">Nama Opsi <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ $customOption->name }}" required class="w-full text-sm p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#2A2626] mb-1.5">Harga Tambahan (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="price" value="{{ $customOption->price }}" min="0" required class="w-full text-sm p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#2A2626] mb-1.5">Foto (Biarkan kosong jika tidak ingin mengubah)</label>
            @if($customOption->image)
                <div class="mb-3">
                    <img src="{{ $customOption->image }}" id="currentPreview" class="w-24 h-24 object-cover rounded-lg border border-[#D8BABF]">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" id="editFileInput" class="w-full max-w-full text-xs p-2 rounded-xl border border-[#D8BABF] focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#F8E8EA] file:text-[#B85D66] hover:file:bg-[#F3ECE4]">
            <p class="text-[10px] text-[#9B9494] mt-1">Disarankan ukuran 1:1, max 2MB.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[#2A2626] mb-1.5">Urutan Tampil (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ $customOption->sort_order }}" class="w-full text-sm p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
            </div>
            
            <div class="flex items-center sm:items-end pb-1 sm:pb-3">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="status" value="1" {{ $customOption->status ? 'checked' : '' }} class="w-4 h-4 text-[#B85D66] focus:ring-[#B85D66] rounded border-[#D8BABF]">
                    <span class="text-sm font-medium text-[#2A2626]">Aktif (Tersedia)</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-[#EADFD4] flex justify-end">
            <button type="submit" class="w-full sm:w-auto bg-[#B85D66] hover:bg-[#8F3B44] text-white px-6 py-2.5 rounded-xl text-xs font-bold tracking-wider transition text-center shadow-xs">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<!-- Cropper Modal -->
<div id="cropperModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl flex flex-col max-h-[90vh] shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-[#EADFD4] flex justify-between items-center bg-[#FAF8F5]">
            <h3 class="font-serif text-lg text-[#2A2626]">Sesuaikan Ukuran Foto</h3>
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
    const preview = document.getElementById('currentPreview');

    // Cropper elements
    const cropperModal = document.getElementById('cropperModal');
    const cropperImage = document.getElementById('cropperImage');
    const closeCropperBtn = document.getElementById('closeCropperBtn');
    const cancelCropBtn = document.getElementById('cancelCropBtn');
    const applyCropBtn = document.getElementById('applyCropBtn');
    let cropper = null;
    let originalFileName = 'image.jpg';
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
                    cropperModal.classList.remove('hidden');
                    cropperModal.classList.add('flex');
                    cropperImage.src = evt.target.result;

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

    if (applyCropBtn) {
        applyCropBtn.addEventListener('click', function() {
            if (!cropper) return;
            
            const canvas = cropper.getCroppedCanvas({
                maxWidth: 1200,
                maxHeight: 1200,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            canvas.toBlob(function(blob) {
                const croppedFile = new File([blob], "cropped_" + originalFileName, { 
                    type: originalFileType, 
                    lastModified: new Date().getTime() 
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                editFile.files = dataTransfer.files;

                if (preview) {
                    preview.src = URL.createObjectURL(blob);
                }
                
                hideCropperModal();
            }, originalFileType, 0.9);
        });
    }

    const cancelHandlers = [closeCropperBtn, cancelCropBtn];
    cancelHandlers.forEach(btn => {
        if(btn) {
            btn.addEventListener('click', function() {
                hideCropperModal();
                editFile.value = '';
            });
        }
    });
</script>
@endsection
