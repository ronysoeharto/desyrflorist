@extends('layouts.app')

@section('title', 'Keranjang Belanja — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <span class="text-[#2A2626]">Keranjang Belanja</span>
        </nav>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Keranjang Belanja Anda</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($cart->items->isEmpty())
        <div class="bg-white rounded-2xl border border-[#EADFD4] p-12 text-center max-w-lg mx-auto space-y-4 shadow-xs">
            <span class="text-5xl block">🛍️</span>
            <h2 class="font-serif text-2xl text-[#2A2626]">Keranjang Anda Masih Kosong</h2>
            <p class="text-xs text-[#6B6565] leading-relaxed">
                Belum ada rangkaian bunga di keranjang Anda. Temukan buket mawar segar, bunga meja, atau buat buket impian Anda sendiri sekarang!
            </p>
            <div class="pt-2 flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('shop.index') }}" class="px-6 py-3 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold uppercase tracking-wider transition">
                    Jelajahi Katalog
                </a>
                <a href="{{ route('custom-bouquet.index') }}" class="px-6 py-3 rounded-xl border border-[#D8BABF] hover:bg-[#F8E8EA] text-xs font-semibold uppercase tracking-wider text-[#2A2626] transition">
                    Custom Buket
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Items Table (8 cols) -->
            <div class="lg:col-span-8 bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
                <div class="p-6 border-b border-[#EADFD4] flex items-center justify-between">
                    <h2 class="font-serif text-xl text-[#2A2626]">Daftar Rangkaian Bunga ({{ $cart->items->count() }})</h2>
                    <a href="{{ route('shop.index') }}" class="text-xs text-[#B85D66] hover:underline font-medium">← Tambah Bunga Lain</a>
                </div>

                <div class="divide-y divide-[#F3ECE4]">
                    @foreach($cart->items as $item)
                        @php
                            $cfg = $item->parsed_config;
                        @endphp
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            
                            <div class="flex items-start gap-4">
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-[#F3ECE4] border border-[#EADFD4] flex-shrink-0">
                                    <img src="{{ $item->product->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=400&q=80' }}"
                                         alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                </div>

                                <div class="space-y-1">
                                    <h3 class="font-serif text-base text-[#2A2626]">
                                        <a href="{{ route('shop.show', $item->product->slug) }}" class="hover:text-[#B85D66] transition">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>

                                    @if($item->variation)
                                        <span class="inline-block text-[11px] text-[#6B6565] bg-[#FAF8F5] px-2 py-0.5 rounded border border-[#EADFD4]">
                                            {{ $item->variation->name }}: {{ $item->variation->value }}
                                        </span>
                                    @endif

                                    <!-- Custom bouquet configuration details -->
                                    @if($cfg)
                                        <div class="text-[11px] text-[#6B6565] space-y-0.5 pt-1">
                                            @if(!empty($cfg['flowers']))
                                                <div>• Bunga: 
                                                    @foreach($cfg['flowers'] as $f)
                                                        {{ $f['qty'] }}x {{ $f['name'] }}{{ !$loop->last ? ',' : '' }}
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if(!empty($cfg['wrapping']))
                                                <div>• Kertas: {{ $cfg['wrapping'] }}</div>
                                            @endif
                                            @if(!empty($cfg['ribbon']))
                                                <div>• Pita: {{ $cfg['ribbon'] }}</div>
                                            @endif
                                            @if(!empty($cfg['card']['message']))
                                                <div class="italic text-[10px] text-[#9B9494]">💌 "{{ Str::limit($cfg['card']['message'], 60) }}"</div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="text-xs font-semibold text-[#B85D66] sm:hidden pt-1">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Qty & Subtotal -->
                            <div class="flex items-center justify-between sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-[#F3ECE4]">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-[#D8BABF] rounded-lg overflow-hidden">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="px-2.5 py-1 text-xs text-[#6B6565] hover:bg-[#F3ECE4]">-</button>
                                    <span class="w-8 text-center text-xs font-semibold border-x border-[#D8BABF] py-1">{{ $item->quantity }}</span>
                                    <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="px-2.5 py-1 text-xs text-[#6B6565] hover:bg-[#F3ECE4]">+</button>
                                </form>

                                <div class="text-right">
                                    <div class="text-xs text-[#9B9494] hidden sm:block">@ Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                                    <div class="text-sm font-bold text-[#2A2626]">
                                        Rp {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}
                                    </div>
                                </div>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[#9B9494] hover:text-[#8F3B44] p-1 transition" title="Hapus Item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Summary & Coupon (4 cols) -->
            <div class="lg:col-span-4 space-y-4">
                
                <!-- Coupon Input -->
                <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#2A2626]">Voucher Diskon</h3>
                    @if($coupon)
                        <div class="p-3 bg-[#EEF2EB] rounded-xl border border-[#7B8B75]/40 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-[#5C6A57]">{{ $coupon->code }}</span>
                                <div class="text-[10px] text-[#6B6565]">
                                    Potongan Rp {{ number_format($discountAmount, 0, ',', '.') }}
                                </div>
                            </div>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" placeholder="Kode kupon (contoh: DESYWELCOME)"
                                   class="flex-1 px-3 py-2 border border-[#D8BABF] rounded-xl text-xs uppercase focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
                            <button type="submit" class="px-4 py-2 bg-[#FAF8F5] border border-[#D8BABF] hover:bg-[#F8E8EA] hover:border-[#B85D66] text-[#2A2626] rounded-xl text-xs font-semibold transition">
                                Gunakan
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <h3 class="font-serif text-xl text-[#2A2626] pb-3 border-b border-[#EADFD4]">
                        Ringkasan Pesanan
                    </h3>

                    <div class="space-y-2 text-xs text-[#6B6565]">
                        <div class="flex justify-between">
                            <span>Subtotal Produk</span>
                            <span class="font-semibold text-[#2A2626]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($discountAmount > 0)
                            <div class="flex justify-between text-[#5C6A57]">
                                <span>Potongan Kupon</span>
                                <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-[11px] text-[#9B9494] pt-1">
                            <span>Ongkos Kirim</span>
                            <span>Dihitung saat checkout</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#EADFD4] flex items-baseline justify-between">
                        <span class="text-xs uppercase tracking-wider font-semibold text-[#2A2626]">Total:</span>
                        <span class="font-serif text-2xl font-bold text-[#B85D66]">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    <a href="{{ route('checkout.index') }}"
                       class="block w-full py-3.5 px-4 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm text-center">
                        Lanjut ke Pembayaran →
                    </a>

                    <div class="pt-2 text-center text-[10px] text-[#9B9494]">
                        🛡️ Transaksi aman dengan verifikasi pembayaran otomatis & manual
                    </div>
                </div>

            </div>

        </div>
    @endif
</div>
@endsection
