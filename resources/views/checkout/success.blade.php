@extends('layouts.app')

@section('title', 'Pesanan Berhasil — ' . $order->order_number . ' — Desy Florist')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    <div class="bg-white rounded-2xl border border-[#EADFD4] p-8 sm:p-10 shadow-xs space-y-8">
        
        <!-- Header Success Icon -->
        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-full bg-[#EEF2EB] text-[#5C6A57] mx-auto flex items-center justify-center text-2xl shadow-xs">
                ✓
            </div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Pesanan Berhasil Dibuat!</h1>
            <p class="text-xs text-[#6B6565]">
                Terima kasih atas kepercayaan Anda kepada Desy Florist. Tim florist kami akan segera memproses rangkaian bunga Anda.
            </p>
            <div class="inline-block mt-2 px-3 py-1 bg-[#FAF8F5] rounded-full border border-[#EADFD4] text-xs font-mono font-bold text-[#B85D66]">
                Nomor Pesanan: {{ $order->order_number }}
            </div>
        </div>

        <!-- Payment Instructions Box -->
        <div class="p-5 rounded-xl bg-[#FAF8F5] border border-[#EADFD4] space-y-3 text-xs">
            <h3 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px]">
                Instruksi Pembayaran ({{ strtoupper($order->payment?->method_label ?? 'Transfer') }}):
            </h3>

            @if($order->payment?->method === 'bca')
                <div class="p-3 bg-white rounded-lg border border-[#EADFD4] space-y-1">
                    <div class="text-[11px] text-[#6B6565]">Bank Central Asia (BCA)</div>
                    <div class="font-mono text-base font-bold text-[#2A2626]">883-059-1234</div>
                    <div class="text-[11px] text-[#6B6565]">Atas Nama: <strong>Desy Florist Studio</strong></div>
                </div>
            @elseif($order->payment?->method === 'mandiri')
                <div class="p-3 bg-white rounded-lg border border-[#EADFD4] space-y-1">
                    <div class="text-[11px] text-[#6B6565]">Bank Mandiri</div>
                    <div class="font-mono text-base font-bold text-[#2A2626]">137-00-1234567-8</div>
                    <div class="text-[11px] text-[#6B6565]">Atas Nama: <strong>Desy Florist Studio</strong></div>
                </div>
            @elseif($order->payment?->method === 'qris')
                <div class="p-3 bg-white rounded-lg border border-[#EADFD4] text-center space-y-2">
                    <div class="text-xs font-semibold text-[#2A2626]">Pindai Kode QRIS di Bawah Ini:</div>
                    <div class="w-40 h-40 mx-auto bg-[#F3ECE4] rounded-lg border border-[#D8BABF] flex items-center justify-center font-mono text-xs text-[#6B6565]">
                        [ QRIS DESY FLORIST ]
                    </div>
                    <div class="text-[10px] text-[#9B9494]">Mendukung GoPay, OVO, ShopeePay, DANA, BCA Mobile</div>
                </div>
            @else
                <div class="p-3 bg-white rounded-lg border border-[#EADFD4]">
                    <p class="text-xs text-[#2A2626]">
                        Metode COD: Silakan siapkan uang pas sebesar <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong> saat kurir mengantarkan bunga.
                    </p>
                </div>
            @endif

            <div class="flex items-center justify-between pt-2 border-t border-[#EADFD4]">
                <span class="text-[#6B6565]">Total yang Harus Dibayarkan:</span>
                <span class="font-serif text-lg font-bold text-[#B85D66]">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Payment Verification & Proof Upload Section -->
        @include('partials.payment-proof-box', ['order' => $order])

        <!-- Order Summary Details -->
        <div class="space-y-4 text-xs">
            <h3 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px]">Rincian Pesanan</h3>
            <div class="border border-[#EADFD4] rounded-xl overflow-hidden divide-y divide-[#F3ECE4]">
                <div class="p-3.5 bg-[#FAF8F5] flex justify-between">
                    <span class="text-[#6B6565]">Penerima</span>
                    <span class="font-medium text-[#2A2626]">{{ $order->recipient_name }} ({{ $order->recipient_phone }})</span>
                </div>
                <div class="p-3.5 bg-white flex justify-between">
                    <span class="text-[#6B6565]">Alamat Pengiriman</span>
                    <span class="font-medium text-[#2A2626] text-right max-w-xs">{{ $order->delivery_address }}</span>
                </div>
                <div class="p-3.5 bg-[#FAF8F5] flex justify-between">
                    <span class="text-[#6B6565]">Jadwal Pengiriman</span>
                    <span class="font-medium text-[#2A2626]">{{ $order->delivery_date->format('d M Y') }} • Slot {{ $order->delivery_slot }}</span>
                </div>
                @if($order->greeting_message)
                    <div class="p-3.5 bg-white flex justify-between">
                        <span class="text-[#6B6565]">Kartu Ucapan</span>
                        <span class="font-medium italic text-[#2A2626] text-right max-w-xs">"{{ $order->greeting_message }}"</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-3 pt-4 border-t border-[#EADFD4]">
            <a href="{{ route('checkout.tracking', $order->order_number) }}"
               class="w-full sm:flex-1 py-3 px-4 rounded-xl bg-[#FAF8F5] border border-[#D8BABF] hover:bg-[#F8E8EA] hover:border-[#B85D66] text-xs font-semibold text-[#2A2626] text-center transition">
                🔍 Lacak Status Pesanan Ini
            </a>

            <a href="https://wa.me/6281234567890?text=Halo%20Desy%20Florist,%20saya%20sudah%20melakukan%20pemesanan%20nomor%20{{ $order->order_number }}%20dan%20ingin%20mengonfirmasi%20pembayaran"
               target="_blank"
               class="w-full sm:flex-1 py-3 px-4 rounded-xl bg-[#5C6A57] hover:bg-[#434F3F] text-white text-xs font-semibold tracking-wider uppercase text-center transition shadow-xs flex items-center justify-center gap-1.5">
                <span>Konfirmasi via WhatsApp</span>
            </a>
        </div>

    </div>
</div>
@endsection
