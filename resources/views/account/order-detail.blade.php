@extends('layouts.app')

@section('title', 'Rincian Pesanan ' . $order->order_number . ' — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <a href="{{ route('account.orders') }}" class="hover:text-[#B85D66]">Riwayat Pesanan</a>
            <span>/</span>
            <span class="text-[#2A2626]">{{ $order->order_number }}</span>
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-serif text-2xl sm:text-3xl text-[#2A2626] font-normal">Pesanan: {{ $order->order_number }}</h1>
                <p class="text-xs text-[#6B6565] mt-1">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('checkout.tracking', $order->tracking_number ?? $order->order_number) }}"
                   class="px-3.5 py-1.5 rounded-xl bg-[#FAF8F5] border border-[#B85D66] text-[#B85D66] hover:bg-[#F8E8EA] text-xs font-semibold transition flex items-center gap-1.5 shadow-2xs">
                    <span>🔍 Lacak Pengiriman Bunga</span>
                </a>
                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $order->status_badge }}">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>
    </div>
</div>

<x-account-nav />

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8 space-y-8">
    
    <!-- Items Table -->
    <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
        <div class="p-5 border-b border-[#EADFD4]">
            <h2 class="font-serif text-lg text-[#2A2626]">Rangkaian Bunga</h2>
        </div>

        <div class="divide-y divide-[#F3ECE4]">
            @foreach($order->items as $item)
                @php $cfg = $item->parsed_config; @endphp
                <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                    <div class="flex items-start gap-4">
                        <img src="{{ $item->product?->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=200&q=80' }}"
                             alt="{{ $item->name }}" class="w-16 h-16 rounded-xl object-cover border border-[#EADFD4]">
                        <div class="space-y-1">
                            <h3 class="font-serif text-base text-[#2A2626]">{{ $item->name }}</h3>
                            <span class="text-[11px] text-[#6B6565]">Qty: {{ $item->quantity }} • Rp {{ number_format($item->unit_price, 0, ',', '.') }} / item</span>

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

                    <div class="font-serif text-base font-bold text-[#2A2626] text-right">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-5 bg-[#FAF8F5] border-t border-[#EADFD4] space-y-2 text-xs text-[#6B6565]">
            <div class="flex justify-between">
                <span>Subtotal Produk</span>
                <span class="font-semibold text-[#2A2626]">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($order->discount > 0)
                <div class="flex justify-between text-[#5C6A57]">
                    <span>Diskon Kupon</span>
                    <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span>Biaya Pengiriman</span>
                <span class="font-semibold text-[#2A2626]">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-[#EADFD4] text-sm">
                <span class="font-bold text-[#2A2626]">Total Pembayaran:</span>
                <span class="font-serif text-lg font-bold text-[#B85D66]">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Delivery & Notes Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
        <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-[#F3ECE4]">
                <h3 class="font-semibold uppercase tracking-wider text-[11px] text-[#2A2626]">Informasi Pengiriman</h3>
                <a href="{{ route('checkout.tracking', $order->tracking_number ?? $order->order_number) }}"
                   class="text-[11px] text-[#B85D66] font-bold hover:underline flex items-center gap-1">
                    <span>Lacak Live</span> ↗
                </a>
            </div>

            @if($order->tracking_number)
                <div class="p-3 rounded-xl bg-[#EEF2EB] border border-[#7B8B75]/30 flex items-center justify-between gap-2">
                    <div>
                        <span class="text-[10px] text-[#5C6A57] uppercase font-bold tracking-wider block">No. Resi Resmi:</span>
                        <span class="font-mono font-bold text-xs text-[#111827] select-all">{{ $order->tracking_number }}</span>
                    </div>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}'); alert('Nomor resi disalin: {{ $order->tracking_number }}');"
                            class="px-2.5 py-1 rounded-md bg-white border border-[#D8BABF] text-[10px] font-semibold text-[#2A2626] hover:bg-[#F8E8EA] transition">
                        📋 Salin
                    </button>
                </div>
            @endif

            <p><strong>Penerima:</strong> {{ $order->recipient_name }} ({{ $order->recipient_phone }})</p>
            <p><strong>Alamat:</strong> {{ $order->delivery_address }}</p>
            <p><strong>Jadwal:</strong> {{ $order->delivery_date->format('d M Y') }} • Slot {{ $order->delivery_slot }}</p>
            @if($order->delivery_notes)
                <p><strong>Catatan:</strong> {{ $order->delivery_notes }}</p>
            @endif
        </div>

        <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-3">
            <h3 class="font-semibold uppercase tracking-wider text-[11px] text-[#2A2626]">Kartu Ucapan & Pembayaran</h3>
            @if($order->greeting_message)
                <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] italic text-[11px] text-[#6B6565]">
                    "{{ $order->greeting_message }}"
                </div>
            @else
                <p class="text-[#9B9494] italic">Tidak ada pesan kartu ucapan.</p>
            @endif

            <div class="pt-2">
                <span class="block text-[11px] text-[#9B9494]">Metode Pembayaran:</span>
                <span class="font-bold text-[#2A2626] uppercase">{{ $order->payment?->method_label ?? 'Transfer' }}</span>
                <span class="ml-2 px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $order->payment_status_badge }}">
                    {{ $order->payment_status_label }}
                </span>
            </div>
        </div>
    </div>

    <!-- Payment Proof Upload & Status Card -->
    @include('partials.payment-proof-box', ['order' => $order])

    <!-- Courier Delivery Proof Photos (If Delivered) -->
    @if($order->delivery && $order->delivery->proofs->isNotEmpty())
        <div class="bg-white p-6 rounded-2xl border border-[#7B8B75]/40 bg-[#EEF2EB]/30 shadow-xs space-y-3 text-xs">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                    <span>🌸</span> Bukti Serah Terima Penerimaan Bunga
                </h3>
                <span class="text-[10px] text-[#5C6A57] font-semibold">
                    Diterima {{ $order->delivery->delivered_at?->format('d M Y, H:i') }} WIB
                </span>
            </div>
            <p class="text-xs text-[#6B6565]">Foto bukti penerimaan rangkaian bunga yang diunggah oleh kurir kami (Klik foto untuk memperbesar):</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                @foreach($order->delivery->proofs as $prf)
                    @php $imgUrl = asset('storage/' . $prf->file_path); @endphp
                    <a href="{{ $imgUrl }}" target="_blank" onclick="openImageModal(event, '{{ $imgUrl }}')"
                       class="group relative block aspect-square rounded-xl overflow-hidden border border-[#7B8B75]/30 hover:border-[#5C6A57] transition shadow-2xs cursor-pointer bg-white">
                        <img src="{{ $imgUrl }}" alt="Bukti Serah Terima Bunga" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-[10px] font-semibold gap-1 p-2 text-center">
                            <span>🔍 Lihat Foto Penuh</span> ↗
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Review Form / Submitted Review Display if order is delivered or completed -->
    @if(in_array($order->status, ['delivered', 'completed']))
        <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
            <h3 class="font-serif text-xl text-[#2A2626]">Ulasan & Testimoni Rangkaian Bunga</h3>
            <p class="text-xs text-[#6B6565]">Ulasan Anda akan langsung secara otomatis tampil pada testimoni toko publik kami.</p>

            @foreach($order->items as $it)
                @if($it->product)
                    @php
                        $existingReview = $order->reviews ? $order->reviews->where('product_id', $it->product->id)->first() : null;
                    @endphp

                    @if($existingReview)
                        <div class="p-4 rounded-xl bg-[#EEF2EB] border border-[#7B8B75]/40 space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-[#2A2626]">{{ $it->product->name }}</span>
                                <span class="text-amber-400 font-bold text-base tracking-wider">
                                    {{ str_repeat('★', $existingReview->rating) }}<span class="text-gray-300">{{ str_repeat('☆', 5 - $existingReview->rating) }}</span>
                                </span>
                            </div>
                            @if($existingReview->comment)
                                <p class="text-[#6B6565] italic leading-relaxed">"{{ $existingReview->comment }}"</p>
                            @endif
                            <div class="flex items-center justify-between text-[10px] text-[#5C6A57] pt-2 border-t border-[#7B8B75]/20 font-medium">
                                <span>✓ Ulasan telah terbit di testimoni toko</span>
                                <span>{{ $existingReview->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('account.reviews.store') }}" method="POST" class="p-4 rounded-xl bg-[#FAF8F5] border border-[#EADFD4] space-y-3 text-xs">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="product_id" value="{{ $it->product->id }}">

                            <div class="font-semibold text-[#2A2626]">{{ $it->product->name }}</div>

                            <div>
                                <label class="block font-medium mb-1 text-[#2A2626]">Rating Bintang (1 - 5):</label>
                                <select name="rating" class="p-2 border border-[#D8BABF] rounded-lg text-xs bg-white text-amber-500 font-bold focus:outline-none focus:ring-1 focus:ring-[#B85D66] cursor-pointer">
                                    <option value="5" selected>★ ★ ★ ★ ★ (5/5) — Sangat Puas & Cantik Sekali</option>
                                    <option value="4">★ ★ ★ ★ ☆ (4/5) — Puas & Segar</option>
                                    <option value="3">★ ★ ★ ☆ ☆ (3/5) — Cukup Baik</option>
                                    <option value="2">★ ★ ☆ ☆ ☆ (2/5) — Kurang Sesuai Ekspektasi</option>
                                    <option value="1">★ ☆ ☆ ☆ ☆ (1/5) — Kecewa</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-medium mb-1 text-[#2A2626]">Ulasan Anda:</label>
                                <textarea name="comment" rows="2" placeholder="Bagikan kesan Anda tentang keindahan dan kesegaran bunga..."
                                          class="w-full p-2.5 border border-[#D8BABF] rounded-lg text-xs bg-white focus:outline-none focus:ring-1 focus:ring-[#B85D66]"></textarea>
                            </div>

                            <button type="submit" class="py-2 px-4 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-lg font-semibold text-xs transition">
                                Kirim Ulasan
                            </button>
                        </form>
                    @endif
                @endif
            @endforeach
        </div>
    @endif

</div>
@endsection
