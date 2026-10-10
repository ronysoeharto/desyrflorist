@php
    $payment = $order->payment;
    $isPaid = $order->isPaid();
    $isVerifying = $order->isVerifyingPayment();
    $isRejected = $order->isPaymentRejected();
    $isPending = !$isPaid && !$isVerifying && !$isRejected;
@endphp

<div class="rounded-2xl border transition-all duration-300 overflow-hidden shadow-xs
    @if($isPaid) border-[#7B8B75]/40 bg-[#FAFBF9]
    @elseif($isVerifying) border-[#85A2B6]/40 bg-[#F6FAFC]
    @elseif($isRejected) border-[#D9777F]/50 bg-[#FCF6F6]
    @else border-[#EADFD4] bg-white
    @endif">

    <!-- Card Header -->
    <div class="p-5 sm:p-6 border-b
        @if($isPaid) border-[#7B8B75]/20 bg-[#EEF2EB]/50
        @elseif($isVerifying) border-[#85A2B6]/20 bg-[#E8F1F6]/50
        @elseif($isRejected) border-[#D9777F]/20 bg-[#FBECEE]/60
        @else border-[#EADFD4] bg-[#FAF8F5]
        @endif flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold shrink-0
                @if($isPaid) bg-[#5C6A57] text-white
                @elseif($isVerifying) bg-[#4A7B9D] text-white
                @elseif($isRejected) bg-[#C05663] text-white
                @else bg-[#B85D66] text-white
                @endif">
                @if($isPaid) ✓
                @elseif($isVerifying) ⏳
                @elseif($isRejected) ✕
                @else 💳
                @endif
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider block
                    @if($isPaid) text-[#5C6A57]
                    @elseif($isVerifying) text-[#3B6684]
                    @elseif($isRejected) text-[#A83845]
                    @else text-[#8F3B44]
                    @endif">
                    Konfirmasi Pembayaran
                </span>
                <h3 class="font-serif text-lg text-[#2A2626] font-medium leading-tight">
                    @if($isPaid)
                        Pembayaran Berhasil Disetujui
                    @elseif($isVerifying)
                        Bukti Pembayaran Sedang Diverifikasi Admin
                    @elseif($isRejected)
                        Bukti Pembayaran Ditolak — Mohon Unggah Ulang
                    @else
                        Unggah Bukti Pembayaran
                    @endif
                </h3>
            </div>
        </div>

        <div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $order->payment_status_badge }}">
                {{ $order->payment_status_label }}
            </span>
        </div>
    </div>

    <!-- Card Body -->
    <div class="p-5 sm:p-6 space-y-5 text-xs">

        {{-- 1. PAID STATE --}}
        @if($isPaid)
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-4 rounded-xl bg-white border border-[#7B8B75]/30 text-[#434F3F]">
                    <span class="text-xl">🌸</span>
                    <div class="space-y-1">
                        <p class="font-semibold text-[13px] text-[#2A2626]">Terima kasih, pembayaran Anda telah diverifikasi oleh admin Desy Florist!</p>
                        <p class="text-[#6B6565] leading-relaxed">
                            Rangkaian bunga pesanan Anda sedang disiapkan dengan penuh ketelitian oleh tim perangkai kami untuk pengiriman tepat waktu.
                        </p>
                        @if($order->paid_at)
                            <div class="text-[11px] text-[#5C6A57] font-medium pt-1">
                                Disetujui pada: {{ $order->paid_at->format('d M Y, H:i') }} WIB
                            </div>
                        @endif
                    </div>
                </div>

                @if($payment?->proof_image)
                    <div class="pt-2 flex items-center justify-between text-[11px] text-[#6B6565]">
                        <span>Bukti transfer yang disetujui:</span>
                        <a href="{{ $payment->proof_url }}" target="_blank" class="text-[#5C6A57] hover:underline font-semibold flex items-center gap-1">
                            <span>Buka Gambar Bukti</span> ↗
                        </a>
                    </div>
                @endif
            </div>

        {{-- 2. VERIFYING STATE --}}
        @elseif($isVerifying)
            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-white border border-[#85A2B6]/30 space-y-2">
                    <div class="flex items-center gap-2 text-[#2D5A7B] font-semibold text-xs">
                        <span class="animate-spin text-sm">⌛</span>
                        <span>Admin kami sedang memeriksa bukti transfer Anda...</span>
                    </div>
                    <p class="text-[#6B6565] leading-relaxed">
                        Bukti pembayaran telah berhasil dikirimkan. Tim admin Desy Florist akan segera memeriksa mutasi rekening dan menyetujui pesanan Anda. Halaman ini akan otomatis terbarui setelah admin menyetujuinya.
                    </p>
                    @if($payment?->proof_uploaded_at)
                        <div class="text-[11px] text-[#85A2B6] font-mono">
                            Dikirim pada: {{ $payment->proof_uploaded_at->format('d M Y, H:i') }} WIB ({{ $payment->proof_uploaded_at->diffForHumans() }})
                        </div>
                    @endif
                </div>

                <!-- Preview Bukti -->
                @if($payment?->proof_image)
                    <div class="p-3 bg-white rounded-xl border border-[#EADFD4] flex flex-col sm:flex-row items-center gap-4">
                        <a href="{{ $payment->proof_url }}" target="_blank" class="block w-24 h-24 rounded-lg overflow-hidden border border-[#EADFD4] shrink-0 bg-gray-50 group relative">
                            <img src="{{ $payment->proof_url }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition duration-200">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-[10px]">
                                Perbesar
                            </div>
                        </a>
                        <div class="space-y-1 text-center sm:text-left flex-1">
                            <span class="font-semibold text-[#2A2626] block">Bukti Transfer Terkirim</span>
                            <span class="text-[11px] text-[#6B6565] block">Klik gambar untuk melihat resolusi penuh di tab baru.</span>
                            <button type="button" onclick="document.getElementById('reuploadForm_{{ $order->id }}').classList.toggle('hidden')"
                                    class="mt-2 inline-flex items-center gap-1 text-[11px] font-semibold text-[#B85D66] hover:underline">
                                <span>Ingin mengganti / unggah ulang bukti pembayaran?</span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Collapsible Re-upload form for Verifying state -->
                <div id="reuploadForm_{{ $order->id }}" class="hidden pt-2 border-t border-[#85A2B6]/20">
                    <form action="{{ route('checkout.payment-proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="space-y-3 bg-white p-4 rounded-xl border border-[#EADFD4]">
                        @csrf
                        <div class="font-semibold text-[#2A2626] text-xs">Unggah Ulang Bukti Transfer Baru:</div>
                        <div>
                            <input type="file" name="proof" accept="image/*" required
                                   class="w-full text-xs text-[#6B6565] file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#FAF8F5] file:text-[#B85D66] hover:file:bg-[#F8E8EA] file:cursor-pointer cursor-pointer border border-[#D8BABF] rounded-xl p-1 bg-white">
                        </div>
                        <div>
                            <input type="text" name="customer_notes" placeholder="Catatan tambahan (opsional, misal: ganti bukti transfer baru)"
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] text-xs bg-white">
                        </div>
                        <button type="submit" class="py-2.5 px-5 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold text-xs transition">
                            Kirim Ulang Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>

        {{-- 3. REJECTED STATE --}}
        @elseif($isRejected)
            <div class="space-y-4">
                <!-- Rejection Alert Box -->
                <div class="p-4 rounded-xl bg-white border border-[#D9777F]/40 space-y-2">
                    <div class="flex items-center gap-2 text-[#A83845] font-bold text-xs">
                        <span class="text-base">⚠️</span>
                        <span>Bukti Pembayaran Belum Disetujui</span>
                    </div>
                    
                    <div class="p-3 bg-[#FCF6F6] rounded-lg border border-[#D9777F]/30 space-y-1">
                        <span class="text-[10px] text-[#A83845] font-bold uppercase tracking-wider block">Alasan Penolakan dari Admin:</span>
                        <p class="font-medium text-[#7A242E] leading-relaxed text-[13px]">
                            "{{ $payment?->rejection_reason ?? 'Bukti transfer tidak sesuai nominal atau tidak valid.' }}"
                        </p>
                    </div>

                    <p class="text-[#6B6565] text-xs leading-relaxed pt-1">
                        Silakan periksa kembali nominal dan rekening transfer Anda, lalu kirimkan ulang foto bukti transfer yang benar di bawah ini. Admin kami akan segera memverifikasi kembali setelah Anda mengunggahnya.
                    </p>
                </div>

                <!-- Re-upload Form -->
                <div class="bg-white p-5 rounded-xl border border-[#D9777F]/30 space-y-3">
                    <h4 class="font-serif text-base text-[#2A2626]">Formulir Unggah Ulang Bukti Pembayaran</h4>
                    
                    <form action="{{ route('checkout.payment-proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-semibold text-[#2A2626] mb-1">Pilih File Foto Bukti Transfer Baru:</label>
                            <input type="file" name="proof" accept="image/*" required
                                   class="w-full text-xs text-[#6B6565] file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#FCF6F6] file:text-[#A83845] hover:file:bg-[#FBECEE] file:cursor-pointer cursor-pointer border border-[#D8BABF] rounded-xl p-1 bg-[#FAF8F5]/30">
                            <span class="text-[10px] text-[#9B9494] block mt-1">Mendukung format JPG, PNG, WEBP. Maksimal 5 MB.</span>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-[#2A2626] mb-1">Catatan Tambahan (Opsional):</label>
                            <input type="text" name="customer_notes" placeholder="Contoh: Sudah transfer kekurangan Rp 50.000 ke rekening BCA"
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] text-xs bg-white focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
                        </div>

                        <button type="submit" class="w-full py-3 bg-[#C05663] hover:bg-[#A83845] text-white rounded-xl font-semibold uppercase tracking-wider text-xs transition shadow-sm flex items-center justify-center gap-2">
                            <span>📤 Unggah Ulang Bukti Pembayaran Sekarang</span>
                        </button>
                    </form>
                </div>
            </div>

        {{-- 4. PENDING STATE (First time upload) --}}
        @else
            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-white border border-[#EADFD4] text-[#6B6565] space-y-1">
                    <p class="font-semibold text-xs text-[#2A2626]">
                        Segera selesaikan pembayaran dan kirimkan bukti transfer Anda.
                    </p>
                    <p class="leading-relaxed">
                        Agar pesanan dapat segera dirangkai oleh florist kami, silakan unggah foto/screenshot bukti transfer perbankan atau QRIS di bawah ini.
                    </p>
                </div>

                <!-- Upload Form -->
                <form action="{{ route('checkout.payment-proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="bg-white p-5 rounded-xl border border-[#EADFD4] space-y-4">
                    @csrf
                    <div>
                        <label class="block font-semibold text-[#2A2626] text-xs mb-1.5">
                            Pilih Foto / Screenshot Bukti Transfer: <span class="text-[#B85D66]">*</span>
                        </label>
                        <input type="file" name="proof" accept="image/*" required
                               class="w-full text-xs text-[#6B6565] file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#FAF8F5] file:text-[#B85D66] hover:file:bg-[#F8E8EA] file:cursor-pointer cursor-pointer border border-[#D8BABF] rounded-xl p-1 bg-white focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
                        <span class="text-[10px] text-[#9B9494] block mt-1">Format gambar: JPG, PNG, atau WEBP. Maksimal ukuran 5 MB.</span>
                    </div>

                    <div>
                        <label class="block font-semibold text-[#2A2626] text-xs mb-1.5">
                            Catatan Pengirim / Atas Nama Rekening (Opsional):
                        </label>
                        <input type="text" name="customer_notes" placeholder="Contoh: Transfer atas nama Budi Santoso via BCA Mobile"
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] text-xs bg-white focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider text-xs transition shadow-sm flex items-center justify-center gap-2">
                        <span>📤 Kirim Bukti Pembayaran</span>
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
