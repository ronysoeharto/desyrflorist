@extends('layouts.app')

@section('title', $product->name . ' — Desy Florist')
@section('meta_description', Str::limit(strip_tags($product->description), 150))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb -->
    <nav class="text-xs text-[#9B9494] mb-6 flex items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-[#B85D66]">Katalog</a>
        @if($product->category)
            <span>/</span>
            <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-[#B85D66]">{{ $product->category->name }}</a>
        @endif
        <span>/</span>
        <span class="text-[#2A2626] truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Product Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">
        
        <!-- Left: Image Gallery (5 cols) -->
        <div class="lg:col-span-6 space-y-4">
            <div class="aspect-square rounded-2xl overflow-hidden border border-[#EADFD4] bg-[#F3ECE4] shadow-xs">
                <img id="mainImage"
                     src="{{ $product->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=1000&q=80' }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover transition duration-300">
            </div>

            @if($product->images->count() > 1)
                <div class="flex items-center gap-3 overflow-x-auto pb-2">
                    @foreach($product->images as $img)
                        <button type="button" onclick="document.getElementById('mainImage').src = '{{ $img->image_path }}'"
                                class="w-16 h-16 rounded-lg overflow-hidden border border-[#EADFD4] hover:border-[#B85D66] focus:border-[#B85D66] flex-shrink-0 transition">
                            <img src="{{ $img->image_path }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Floral Care Guarantee Box -->
            <div class="p-4 rounded-xl bg-[#FAF8F5] border border-[#EADFD4] text-xs text-[#6B6565] space-y-2">
                <div class="font-semibold text-[#2A2626] flex items-center gap-2">
                    <span>🌸</span> Jaminan Kesegaran Desy Florist:
                </div>
                <p class="leading-relaxed text-[11px]">
                    Setiap tangkai bunga dirangkai pada hari pengiriman. Tim kami akan mengirimkan foto hasil akhir sebelum kurir berangkat untuk memastikan kesesuaian dan kepuasan Anda.
                </p>
            </div>
        </div>

        <!-- Right: Order Configurator (7 cols) -->
        <div class="lg:col-span-6 space-y-6">
            
            <!-- Category & Title -->
            <div>
                @if($product->category)
                    <span class="text-xs uppercase tracking-widest text-[#B85D66] font-semibold block mb-1">
                        {{ $product->category->name }}
                    </span>
                @endif
                <h1 class="font-serif text-2xl sm:text-3xl text-[#2A2626] font-normal leading-snug">
                    {{ $product->name }}
                </h1>
                <div class="mt-2 flex items-center gap-3 text-xs text-[#9B9494]">
                    <span>SKU: {{ $product->sku }}</span>
                    <span>•</span>
                    <span class="text-[#5C6A57] font-medium">Stok Tersedia: {{ $product->stock }} rangkaian</span>
                </div>
            </div>

            <!-- Price -->
            <div class="p-4 rounded-xl bg-white border border-[#EADFD4] flex items-baseline gap-3">
                @if($product->discount_price)
                    <span class="font-serif text-3xl font-bold text-[#B85D66]">
                        Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                    </span>
                    <span class="text-sm text-[#9B9494] line-through">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-[#EEF2EB] text-[#5C6A57]">
                        Hemat Rp {{ number_format($product->price - $product->discount_price, 0, ',', '.') }}
                    </span>
                @else
                    <span class="font-serif text-3xl font-bold text-[#2A2626]">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                @endif
            </div>

            <!-- Description -->
            <div class="text-xs sm:text-sm text-[#6B6565] leading-relaxed font-light">
                <p>{{ $product->description }}</p>
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" class="space-y-5 pt-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Variations if any -->
                @if($product->variations->isNotEmpty())
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#2A2626] mb-1.5">
                            Pilihan Variasi / Ukuran:
                        </label>
                        <select name="variation_id" class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-white text-xs">
                            @foreach($product->variations as $var)
                                <option value="{{ $var->id }}">
                                    {{ $var->name }}: {{ $var->value }} 
                                    @if($var->price_adjustment > 0)
                                        (+ Rp {{ number_format($var->price_adjustment, 0, ',', '.') }})
                                    @elseif($var->price_adjustment < 0)
                                        (- Rp {{ number_format(abs($var->price_adjustment), 0, ',', '.') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Delivery Date Selection -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#2A2626] mb-1.5">
                        Rencana Tanggal Pengiriman:
                    </label>
                    <input type="date" name="delivery_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-white text-xs">
                    <span class="text-[11px] text-[#9B9494] mt-1 block">Pesanan sebelum 16:00 dapat dikirimkan hari ini.</span>
                </div>

                <!-- Custom Card Message Note -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#2A2626]">
                            Pesan Kartu Ucapan (Gratis):
                        </label>
                        <span class="text-[10px] text-[#9B9494]">Maks. 500 karakter</span>
                    </div>
                    <textarea name="card_message" rows="3"
                              placeholder="Tuliskan ucapan tulus Anda di sini (Contoh: Happy Birthday dear Anna! Dari: Kevin)"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-white text-xs"></textarea>
                </div>

                <!-- Quantity & Buttons -->
                <div class="flex items-center gap-4 pt-2">
                    <div class="flex items-center border border-[#D8BABF] rounded-xl overflow-hidden bg-white">
                        <button type="button" onclick="changeQty(-1)" class="px-3 py-2.5 text-[#6B6565] hover:bg-[#F3ECE4] text-xs font-bold">-</button>
                        <input type="number" id="qtyInput" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-12 text-center text-xs font-semibold focus:outline-none border-x border-[#D8BABF] py-2.5">
                        <button type="button" onclick="changeQty(1)" class="px-3 py-2.5 text-[#6B6565] hover:bg-[#F3ECE4] text-xs font-bold">+</button>
                    </div>

                    <button type="submit" class="flex-1 py-3.5 px-6 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm text-center">
                        Tambah ke Keranjang
                    </button>
                </div>
            </form>

        </div>

    </div>

    <!-- Product Reviews & Care Instructions Tabs -->
    <div class="mt-16 pt-10 border-t border-[#EADFD4]">
        <div class="max-w-3xl mx-auto space-y-8">
            <div>
                <h3 class="font-serif text-2xl text-[#2A2626] mb-4">Ulasan Pelanggan</h3>
                
                @if($product->reviews->isEmpty())
                    <p class="text-xs text-[#9B9494] italic">Belum ada ulasan untuk rangkaian bunga ini. Jadilah yang pertama memberikan ulasan setelah pesanan Anda selesai!</p>
                @else
                    <div class="space-y-4">
                        @foreach($product->reviews as $rev)
                            <div class="p-4 rounded-xl border border-[#EADFD4] bg-white space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-[#2A2626]">{{ $rev->user?->name ?? 'Pelanggan Desy Florist' }}</span>
                                    <span class="text-amber-400 font-bold tracking-wider text-sm">
                                        {{ str_repeat('★', $rev->rating) }}<span class="text-gray-300">{{ str_repeat('☆', 5 - $rev->rating) }}</span>
                                    </span>
                                </div>
                                <p class="text-xs text-[#6B6565]">{{ $rev->comment }}</p>
                                <span class="text-[10px] text-[#9B9494] block">{{ $rev->created_at->format('d M Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="p-5 rounded-xl bg-[#F8E8EA]/40 border border-[#E5B2B6] text-xs space-y-2">
                <h4 class="font-semibold text-[#2A2626]">💡 Tips Merawat Bunga Potong Agar Tahan Lama:</h4>
                <ul class="list-disc list-inside space-y-1 text-[#6B6565] text-[11px]">
                    <li>Potong batang secara diagonal kira-kira 1-2 cm sebelum diletakkan di vas berisi air bersih.</li>
                    <li>Ganti air vas setiap 1-2 hari sekali dan jauhkan dari paparan sinar matahari terik langsung atau hembusan AC kencang.</li>
                    <li>Semprotkan sedikit air embun segar ke mahkota bunga setiap pagi.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="mt-20 pt-10 border-t border-[#EADFD4]">
            <div class="mb-8">
                <span class="text-[11px] uppercase tracking-widest text-[#B85D66] font-semibold block mb-1">Rekomendasi Terkait</span>
                <h3 class="font-serif text-2xl text-[#2A2626]">Rangkaian Bunga Serupa</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    <x-product-card :product="$rel" />
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
    function changeQty(delta) {
        const input = document.getElementById('qtyInput');
        let current = parseInt(input.value) || 1;
        current += delta;
        if (current < 1) current = 1;
        input.value = current;
    }
</script>
@endsection
