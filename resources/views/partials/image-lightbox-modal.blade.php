<!-- GLOBAL IMAGE LIGHTBOX MODAL -->
<div id="imageLightboxModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6 bg-black/85 backdrop-blur-md transition-opacity duration-300" onclick="closeImageModal(event)">
    <div class="relative max-w-4xl w-full max-h-[92vh] bg-[#1A1A1A] rounded-2xl overflow-hidden shadow-2xl border border-white/20 flex flex-col" onclick="event.stopPropagation()">
        
        <!-- Modal Top Bar with High-Visibility Red Close Button -->
        <div class="px-4 py-3 bg-[#262626] border-b border-white/10 text-white flex items-center justify-between text-xs sm:text-sm">
            <span class="font-bold flex items-center gap-2 text-white">
                <span class="text-base">🖼️</span> Bukti Serah Terima
            </span>
            
            <div class="flex items-center gap-2 sm:gap-3">
                <a id="imageLightboxDownload" href="#" target="_blank" class="px-3 py-1.5 bg-white/15 hover:bg-white/30 text-white rounded-xl transition font-semibold text-xs flex items-center gap-1 border border-white/20">
                    <span>Buka Resolusi Penuh</span> ↗
                </a>
                
                <!-- Highly Visible Prominent Red Close Button -->
                <button type="button" onclick="closeImageModal()"
                        class="px-3.5 py-1.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-xl font-bold text-xs shadow-md transition-all flex items-center gap-1.5 border border-red-400/30 cursor-pointer">
                    <span>Tutup</span>
                    <span class="text-sm leading-none">✕</span>
                </button>
            </div>
        </div>

        <!-- Floating Quick Close Button on Corner for Mobile/Desktop -->
        <button type="button" onclick="closeImageModal()" aria-label="Tutup Pratinjau"
                class="absolute top-2 right-2 sm:hidden z-10 w-9 h-9 rounded-full bg-red-600 text-white font-bold shadow-lg flex items-center justify-center text-lg border border-white/40">
            ✕
        </button>

        <!-- Modal Image Body -->
        <div class="p-3 bg-black/95 flex items-center justify-center overflow-auto max-h-[78vh] min-h-[250px]">
            <img id="imageLightboxSrc" src="" alt="Pratinjau Foto Bukti" class="max-w-full max-h-[73vh] object-contain rounded-xl shadow-2xl border border-white/10">
        </div>

        <!-- Modal Footer Helper -->
        <div class="px-4 py-2 bg-[#1A1A1A] border-t border-white/10 text-[11px] text-gray-400 text-center flex items-center justify-between">
            <span>💡 Tekan <kbd class="px-1.5 py-0.5 bg-gray-800 text-white rounded font-mono text-[10px]">ESC</kbd> atau sentuh area luar gambar untuk menutup</span>
            <button type="button" onclick="closeImageModal()" class="text-red-400 hover:text-red-300 font-semibold underline text-xs">
                Tutup Pratinjau ✕
            </button>
        </div>
    </div>
</div>

<script>
    function openImageModal(e, url) {
        if (e) e.preventDefault();
        const modal = document.getElementById('imageLightboxModal');
        const img = document.getElementById('imageLightboxSrc');
        const dl = document.getElementById('imageLightboxDownload');
        if (modal && img) {
            img.src = url;
            if (dl) dl.href = url;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        } else {
            window.open(url, '_blank');
        }
    }

    function closeImageModal(e) {
        if (e && e.target !== e.currentTarget && !e.target.closest('button')) return;
        const modal = document.getElementById('imageLightboxModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeImageModal();
    });
</script>
