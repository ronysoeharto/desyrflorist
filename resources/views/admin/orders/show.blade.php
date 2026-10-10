@extends('layouts.admin')

@section('title', 'Kelola Pesanan ' . $order->order_number . ' — Admin Desy Florist')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="text-xs text-[#9B9494] mb-1">
                <a href="{{ route('admin.orders.index') }}" class="hover:underline">← Kembali ke Daftar Pesanan</a>
            </nav>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2A2626]">Pesanan: {{ $order->order_number }}</h1>
            <span class="text-xs text-[#6B6565]">Dibuat {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.orders.print-resi', $order->id) }}" target="_blank"
               class="px-3 py-1.5 rounded-xl bg-white border border-[#111827] text-[#111827] hover:bg-[#111827] hover:text-white text-xs font-semibold transition flex items-center gap-1 shadow-2xs">
                <span>🖨️ Cetak Resi Asli</span>
            </a>
            <a href="{{ route('checkout.tracking', $order->tracking_number ?? $order->order_number) }}" target="_blank"
               class="px-3 py-1.5 rounded-xl bg-[#FAF8F5] border border-[#D8BABF] text-[#B85D66] hover:bg-[#F8E8EA] text-xs font-semibold transition flex items-center gap-1">
                <span>🔍 Lacak Pesanan</span> ↗
            </a>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->status_badge }}">
                {{ $order->status_label }}
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->payment_status_badge }}">
                {{ $order->payment_status_label }}
            </span>
        </div>
    </div>

    <!-- Management Controls Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Items & Customer Details (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Items Box -->
            <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
                <div class="p-5 border-b border-[#EADFD4]">
                    <h2 class="font-serif text-lg text-[#2A2626]">Rangkaian Bunga Pesanan</h2>
                </div>

                <div class="divide-y divide-[#F3ECE4]">
                    @foreach($order->items as $it)
                        @php $cfg = $it->parsed_config; @endphp
                        <div class="p-5 flex items-start justify-between gap-4 text-xs">
                            <div class="flex items-start gap-4">
                                <img src="{{ $it->product?->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=200&q=80' }}"
                                     alt="{{ $it->name }}" class="w-16 h-16 rounded-xl object-cover border border-[#EADFD4]">
                                <div>
                                    <h3 class="font-serif text-base text-[#2A2626]">{{ $it->name }}</h3>
                                    <span class="text-[11px] text-[#6B6565] block">{{ $it->quantity }}x @ Rp {{ number_format($it->unit_price, 0, ',', '.') }}</span>

                                    @if($cfg)
                                        <div class="text-[10px] text-[#6B6565] pt-1 space-y-0.5">
                                            @if(!empty($cfg['flowers']))
                                                <div>Bunga: 
                                                    @foreach($cfg['flowers'] as $f)
                                                        {{ $f['qty'] }}x {{ $f['name'] }}{{ !$loop->last ? ',' : '' }}
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if(!empty($cfg['wrapping'])) <div>Wrapping: {{ $cfg['wrapping'] }}</div> @endif
                                            @if(!empty($cfg['ribbon'])) <div>Pita: {{ $cfg['ribbon'] }}</div> @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <span class="font-serif font-bold text-sm text-[#2A2626]">
                                Rp {{ number_format($it->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="p-5 bg-[#FAF8F5] border-t border-[#EADFD4] text-xs space-y-2 text-[#6B6565]">
                    <div class="flex justify-between"><span>Subtotal:</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-[#5C6A57]"><span>Diskon:</span><span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="flex justify-between"><span>Ongkos Kirim:</span><span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between pt-2 border-t border-[#EADFD4] text-sm font-bold text-[#2A2626]">
                        <span>Total:</span>
                        <span class="font-serif text-base text-[#B85D66]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Recipient & Greeting Message -->
            <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                <h3 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px]">Tujuan & Kartu Ucapan</h3>
                <div class="space-y-2">
                    <p><strong>Penerima:</strong> {{ $order->recipient_name }} (<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->recipient_phone) }}" target="_blank" class="text-[#5C6A57] font-bold underline">{{ $order->recipient_phone }}</a>)</p>
                    <p><strong>Alamat:</strong> {{ $order->delivery_address }}</p>
                    <p><strong>Jadwal Kirim:</strong> {{ $order->delivery_date->format('d M Y') }} • Slot {{ $order->delivery_slot }}</p>
                    @if($order->delivery_notes)
                        <p><strong>Catatan:</strong> {{ $order->delivery_notes }}</p>
                    @endif

                    @if($order->greeting_message)
                        <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] italic text-[11px] text-[#6B6565] mt-2">
                            💌 "{{ $order->greeting_message }}"
                        </div>
                    @endif
                </div>
            </div>

            <!-- Delivery Proof If Available -->
            @if($order->delivery && $order->delivery->proofs->isNotEmpty())
                <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px]">Bukti Pengiriman Kurir</h3>
                        <span class="text-[10px] text-[#9B9494]">🔍 Klik foto untuk memperbesar</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($order->delivery->proofs as $prf)
                            @php $imgUrl = asset('storage/' . $prf->file_path); @endphp
                            <a href="{{ $imgUrl }}" target="_blank" onclick="openImageModal(event, '{{ $imgUrl }}')"
                               class="group relative block aspect-square rounded-xl overflow-hidden border border-[#EADFD4] hover:border-[#B85D66] transition shadow-2xs cursor-pointer">
                                <img src="{{ $imgUrl }}" alt="Bukti Pengantaran" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-[10px] font-semibold gap-1 p-2 text-center">
                                    <span>🔍 Lihat Resolusi Penuh</span> ↗
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Right: Order Actions (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Resi & Airway Bill Management Card -->
            <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                <div class="flex items-center justify-between pb-3 border-b border-[#EADFD4]">
                    <div>
                        <span class="text-[10px] text-[#9B9494] font-bold uppercase tracking-wider block">Logistik & Pengiriman</span>
                        <h2 class="font-serif text-lg text-[#2A2626] flex items-center gap-1.5">
                            <span>📦 Nomor Resi Resmi</span>
                        </h2>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EEF2EB] text-[#5C6A57] border border-[#7B8B75]/30">
                        Tersambung ke Lacak
                    </span>
                </div>

                <div class="p-3.5 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-2">
                    <div class="text-[10px] text-[#6B6565] uppercase font-bold tracking-wider">No. Resi Pengiriman Saat Ini:</div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-base font-bold text-[#111827] select-all">
                            {{ $order->tracking_number ?? 'Belum ada nomor resi' }}
                        </span>
                        @if($order->tracking_number)
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}'); alert('Nomor resi disalin: {{ $order->tracking_number }}');"
                                    class="px-2.5 py-1 rounded-lg bg-white border border-[#D8BABF] hover:bg-[#F8E8EA] text-[11px] font-semibold text-[#2A2626] transition">
                                📋 Salin
                            </button>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] text-[#5C6A57] pt-1 border-t border-[#EADFD4]">
                        <span>✓</span>
                        <span>Pembeli cukup klik tombol <strong>Lacak</strong> untuk langsung memantau perjalanan bunga ini.</span>
                    </div>
                </div>

                <!-- Action Buttons: Print Resi & Live Tracking -->
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.orders.print-resi', $order->id) }}" target="_blank"
                       class="py-2.5 px-3 bg-[#111827] hover:bg-black text-white rounded-xl font-semibold text-xs transition text-center shadow-xs flex items-center justify-center gap-1.5">
                        <span>🖨️ Cetak Resi Asli</span>
                    </a>
                    <a href="{{ route('checkout.tracking', $order->tracking_number ?? $order->order_number) }}" target="_blank"
                       class="py-2.5 px-3 bg-white border border-[#D8BABF] hover:bg-[#FAF8F5] text-[#2A2626] rounded-xl font-semibold text-xs transition text-center flex items-center justify-center gap-1.5">
                        <span>🔍 Buka Lacak Live</span> ↗
                    </a>
                </div>

                <!-- Update / Custom Resi Form -->
                <div class="pt-3 border-t border-[#EADFD4] space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-[#2A2626]">Atur / Ubah Nomor Resi:</span>
                        <form action="{{ route('admin.orders.tracking-number', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="generate">
                            <button type="submit" onclick="return confirm('Generate nomor resi otomatis baru untuk pesanan ini?');"
                                    class="text-[11px] text-[#B85D66] hover:underline font-semibold">
                                ⚡ Generate Otomatis Baru
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('admin.orders.tracking-number', $order->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" required
                               placeholder="Input resi custom (cth: JNE / DF-RESI-...)"
                               class="flex-1 p-2 rounded-lg border border-[#D8BABF] bg-[#FAF8F5]/40 text-xs font-mono uppercase focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
                        <button type="submit"
                                class="px-3 py-2 bg-[#5C6A57] hover:bg-[#434F3F] text-white rounded-lg font-semibold text-xs transition">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Payment Verification Card -->
            @php
                $payment = $order->payment;
                $isPaid = $order->isPaid();
                $isVerifying = $order->isVerifyingPayment();
                $isRejected = $order->isPaymentRejected();
            @endphp
            <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                <div class="flex items-center justify-between pb-3 border-b border-[#EADFD4]">
                    <div>
                        <span class="text-[10px] text-[#9B9494] font-bold uppercase tracking-wider block">Verifikasi Finansial</span>
                        <h2 class="font-serif text-lg text-[#2A2626]">Bukti Transfer Pelanggan</h2>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $order->payment_status_badge }}">
                        {{ $order->payment_status_label }}
                    </span>
                </div>

                <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-1">
                    <div class="flex justify-between">
                        <span class="text-[#6B6565]">Metode:</span>
                        <span class="font-bold text-[#2A2626] uppercase">{{ $payment?->method_label ?? 'Transfer Bank' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#6B6565]">Total Tagihan:</span>
                        <span class="font-serif font-bold text-sm text-[#B85D66]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    @if($order->paid_at)
                        <div class="flex justify-between text-[#5C6A57] pt-1 border-t border-[#EADFD4]">
                            <span>Waktu Disetujui:</span>
                            <span>{{ $order->paid_at->format('d/m/Y H:i') }} WIB</span>
                        </div>
                    @endif
                </div>

                @if($payment?->proof_image)
                    <!-- Proof Image Preview -->
                    <div class="space-y-2">
                        <span class="font-semibold text-[#2A2626] block">Foto Bukti Pembayaran:</span>
                        <div class="relative group rounded-xl overflow-hidden border border-[#D8BABF] bg-[#FAF8F5]">
                            <img src="{{ $payment->proof_url }}" alt="Bukti Transfer" class="w-full max-h-64 object-contain mx-auto">
                            <a href="{{ $payment->proof_url }}" target="_blank"
                               class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1">
                                <span>Buka Resolusi Penuh di Tab Baru ↗</span>
                            </a>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-[#6B6565] pt-1">
                            <span>Diunggah: {{ $payment->proof_uploaded_at?->format('d M Y, H:i') }} WIB</span>
                            <a href="{{ $payment->proof_url }}" target="_blank" class="text-[#B85D66] font-semibold hover:underline">
                                Lihat Asli ↗
                            </a>
                        </div>
                        @if(!empty($payment->payment_data['customer_notes']))
                            <div class="p-2.5 bg-[#FAF8F5] rounded-lg border border-[#EADFD4] text-[11px] text-[#6B6565]">
                                <strong>Catatan Pelanggan:</strong> "{{ $payment->payment_data['customer_notes'] }}"
                            </div>
                        @endif
                    </div>

                    @if($isRejected && $payment->rejection_reason)
                        <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-800 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider block">Alasan Penolakan Terakhir:</span>
                            <p class="font-medium text-xs">"{{ $payment->rejection_reason }}"</p>
                            <span class="text-[10px] text-red-600 block">Menunggu pelanggan mengunggah ulang bukti pembayaran baru.</span>
                        </div>
                    @endif

                    @if($isPaid)
                        <div class="p-3.5 rounded-xl bg-[#EEF2EB] border border-[#7B8B75]/30 text-[#434F3F] flex items-center gap-2">
                            <span class="text-base font-bold">✓</span>
                            <div>
                                <span class="font-bold block">Pembayaran Telah Disetujui</span>
                                <span class="text-[11px] text-[#5C6A57]">Pesanan dapat dilanjutkan ke tahap perangkaian dan pengiriman.</span>
                            </div>
                        </div>
                    @else
                        <!-- Action Buttons for Proof: Approve & Reject -->
                        <div class="space-y-3 pt-2 border-t border-[#EADFD4]">
                            <div class="grid grid-cols-2 gap-2">
                                <!-- Approve Form -->
                                <form action="{{ route('admin.orders.payment.approve', $order->id) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI bukti pembayaran pesanan ini? Status pesanan akan otomatis menjadi Dikonfirmasi.');">
                                    @csrf
                                    <button type="submit"
                                            class="w-full py-2.5 px-3 bg-[#5C6A57] hover:bg-[#434F3F] text-white rounded-xl font-semibold text-xs tracking-wide transition shadow-xs flex items-center justify-center gap-1.5">
                                        <span>✓ Setujui Pembayaran</span>
                                    </button>
                                </form>

                                <!-- Toggle Reject Form Button -->
                                <button type="button"
                                        onclick="document.getElementById('rejectFormBox_{{ $order->id }}').classList.toggle('hidden')"
                                        class="w-full py-2.5 px-3 bg-[#C05663] hover:bg-[#A83845] text-white rounded-xl font-semibold text-xs tracking-wide transition shadow-xs flex items-center justify-center gap-1.5">
                                    <span>✕ Tolak Bukti</span>
                                </button>
                            </div>

                            <!-- Reject Form Box (Hidden by default) -->
                            <div id="rejectFormBox_{{ $order->id }}" class="hidden p-4 rounded-xl bg-[#FCF6F6] border border-[#D9777F]/40 space-y-3">
                                <div class="font-semibold text-[#A83845] text-xs">Form Penolakan Bukti Pembayaran:</div>
                                <p class="text-[11px] text-[#6B6565]">
                                    Sertakan alasan penolakan agar pelanggan tahu apa yang perlu diperbaiki saat mengunggah ulang.
                                </p>

                                <form action="{{ route('admin.orders.payment.reject', $order->id) }}" method="POST" class="space-y-2.5">
                                    @csrf
                                    <!-- Quick template reasons -->
                                    <div class="space-y-1">
                                        <label class="block text-[10px] font-semibold text-[#6B6565]">Pilih Alasan Cepat (atau ketik manual):</label>
                                        <div class="flex flex-wrap gap-1">
                                            <button type="button" onclick="document.getElementById('rejectionReasonInput_{{ $order->id }}').value='Nominal transfer tidak sesuai dengan total tagihan pesanan.'"
                                                    class="px-2 py-0.5 rounded bg-white border border-[#D8BABF] text-[10px] text-[#2A2626] hover:bg-[#F8E8EA]">
                                                Nominal Kurang/Salah
                                            </button>
                                            <button type="button" onclick="document.getElementById('rejectionReasonInput_{{ $order->id }}').value='Foto bukti transfer buram, terpotong, atau tidak terbaca.'"
                                                    class="px-2 py-0.5 rounded bg-white border border-[#D8BABF] text-[10px] text-[#2A2626] hover:bg-[#F8E8EA]">
                                                Foto Buram
                                            </button>
                                            <button type="button" onclick="document.getElementById('rejectionReasonInput_{{ $order->id }}').value='Rekening tujuan transfer tidak sesuai dengan rekening resmi Desy Florist.'"
                                                    class="px-2 py-0.5 rounded bg-white border border-[#D8BABF] text-[10px] text-[#2A2626] hover:bg-[#F8E8EA]">
                                                Rekening Salah
                                            </button>
                                            <button type="button" onclick="document.getElementById('rejectionReasonInput_{{ $order->id }}').value='Mutasi rekening bank belum tercatat. Mohon lampirkan struk resmi atau mutasi terbaru.'"
                                                    class="px-2 py-0.5 rounded bg-white border border-[#D8BABF] text-[10px] text-[#2A2626] hover:bg-[#F8E8EA]">
                                                Mutasi Belum Masuk
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <textarea id="rejectionReasonInput_{{ $order->id }}" name="rejection_reason" rows="2" required
                                                  placeholder="Tuliskan alasan penolakan secara jelas untuk pelanggan..."
                                                  class="w-full p-2 rounded-lg border border-[#D8BABF] text-xs bg-white focus:outline-none focus:ring-1 focus:ring-[#B85D66]"></textarea>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                class="flex-1 py-2 bg-[#C05663] hover:bg-[#A83845] text-white rounded-lg font-semibold text-xs transition">
                                            Kirim Penolakan ke Pelanggan
                                        </button>
                                        <button type="button" onclick="document.getElementById('rejectFormBox_{{ $order->id }}').classList.add('hidden')"
                                                class="px-3 py-2 bg-white border border-[#EADFD4] text-[#6B6565] rounded-lg text-xs hover:bg-[#FAF8F5]">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- No proof yet -->
                    <div class="p-3.5 rounded-xl bg-[#FAF8F5] border border-[#EADFD4] text-[#6B6565] space-y-2">
                        <p class="text-[11px] leading-relaxed">
                            ⚠️ Pelanggan belum mengunggah bukti pembayaran untuk pesanan ini.
                        </p>
                        @if(!$isPaid)
                            <form action="{{ route('admin.orders.payment.approve', $order->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda ingin menyetujui dan menandai lunas pesanan ini tanpa bukti unggahan?');">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-[#5C6A57] hover:bg-[#434F3F] text-white rounded-lg font-semibold text-[11px] transition">
                                    Tandai Lunas Manual (Tanpa Bukti)
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Update Order & Payment Status Form -->
            <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                <h2 class="font-serif text-lg text-[#2A2626]">Perbarui Status Pesanan</h2>

                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium mb-1">Status Alur Pesanan:</label>
                        <select name="status" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white text-xs">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid (Sudah Bayar)</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed (Pesanan Dikonfirmasi)</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Sedang Dirangkai)</option>
                            <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready (Rangkaian Siap Kirim)</option>
                            <option value="assigned" {{ $order->status === 'assigned' ? 'selected' : '' }}>Assigned (Ditugaskan ke Kurir)</option>
                            <option value="picked_up" {{ $order->status === 'picked_up' ? 'selected' : '' }}>Picked Up (Kurir Ambil Bunga)</option>
                            <option value="on_delivery" {{ $order->status === 'on_delivery' ? 'selected' : '' }}>On Delivery (Diantar ke Lokasi)</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered (Selesai Diterima)</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed (Tuntas)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Status Pembayaran:</label>
                        <select name="payment_status" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white text-xs">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="verifying" {{ $order->payment_status === 'verifying' ? 'selected' : '' }}>Verifying (Menunggu Verifikasi)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                            <option value="rejected" {{ $order->payment_status === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak / Upload Ulang)</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed (Gagal)</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded (Dikembalikan)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider text-xs transition">
                        Simpan Perubahan Status
                    </button>
                </form>
            </div>

            <!-- Assign Courier Form -->
            <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                <h2 class="font-serif text-lg text-[#2A2626]">Tugaskan Mitra Kurir</h2>

                <form action="{{ route('admin.orders.assign', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-medium mb-1">Pilih Kurir Terverifikasi:</label>
                        <select name="courier_id" class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white text-xs" required>
                            <option value="">-- Pilih Kurir --</option>
                            @foreach($couriers as $cr)
                                <option value="{{ $cr->id }}" {{ $order->courier_id === $cr->id ? 'selected' : '' }}>
                                    {{ $cr->name }} ({{ $cr->courierProfile?->vehicle_number ?? 'Motor' }} - Plat: {{ $cr->courierProfile?->plate_number ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#5C6A57] hover:bg-[#434F3F] text-white rounded-xl font-semibold uppercase tracking-wider text-xs transition">
                        Tugaskan Kurir Ini
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
