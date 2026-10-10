@extends('layouts.app')

@section('title', 'Custom Bouquet Builder — Rangkai Buket Personalisasi Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <span class="text-[#2A2626]">Custom Bouquet Builder</span>
        </nav>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Rancang Buket Impian Anda</h1>
        <p class="text-xs sm:text-sm text-[#6B6565] mt-1 max-w-xl font-light">
            Ekspresikan perasaan Anda dengan memilih kombinasi bunga segar, warna wrapping kertas matte, pita sutra ganda, dan kartu ucapan spesial.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <form action="{{ route('custom-bouquet.add') }}" method="POST" id="customBouquetForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Configurator Steps (8 cols) -->
            <div class="lg:col-span-8 space-y-10">
                
                <!-- Step 1: Flowers Selection -->
                <section class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-xs font-bold">1</span>
                        <div>
                            <h2 class="font-serif text-xl text-[#2A2626]">Pilih Bunga & Jumlah Tangkai</h2>
                            <p class="text-xs text-[#6B6565]">Tentukan jenis bunga segar yang ingin dirangkai (minimal 1 jenis bunga)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        @foreach($flowers as $idx => $fl)
                            <div class="p-3.5 rounded-xl border border-[#EADFD4] hover:border-[#D8BABF] flex items-center justify-between gap-3 bg-[#FAF8F5]/30">
                                <div class="flex items-center gap-3">
                                    @if($fl->image)
                                        <img src="{{ $fl->image }}" alt="{{ $fl->name }}" class="w-12 h-12 rounded-lg object-cover">
                                    @endif
                                    <div>
                                        <h4 class="text-xs font-semibold text-[#2A2626]">{{ $fl->name }}</h4>
                                        <span class="text-[11px] text-[#B85D66] font-medium">Rp {{ number_format($fl->price, 0, ',', '.') }} / tangkai</span>
                                    </div>
                                </div>

                                <div class="flex items-center border border-[#D8BABF] rounded-lg overflow-hidden bg-white">
                                    <input type="hidden" name="flowers[{{ $idx }}][id]" value="{{ $fl->id }}">
                                    <button type="button" onclick="updateFlowerQty({{ $idx }}, -1)" class="px-2 py-1 text-xs text-[#6B6565] hover:bg-[#F3ECE4]">-</button>
                                    <input type="number" id="flower_qty_{{ $idx }}" name="flowers[{{ $idx }}][qty]" value="0" min="0" max="50"
                                           data-price="{{ $fl->price }}" data-name="{{ $fl->name }}"
                                           onchange="recalculateTotal()"
                                           class="w-10 text-center text-xs font-semibold border-x border-[#D8BABF] py-1 focus:outline-none">
                                    <button type="button" onclick="updateFlowerQty({{ $idx }}, 1)" class="px-2 py-1 text-xs text-[#6B6565] hover:bg-[#F3ECE4]">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Step 2: Wrapping Paper -->
                <section class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-xs font-bold">2</span>
                        <div>
                            <h2 class="font-serif text-xl text-[#2A2626]">Pilih Kertas Pembungkus (Wrapping)</h2>
                            <p class="text-xs text-[#6B6565]">Kertas matte impor bergaya Korea yang lembut dan mewah</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        @foreach($wrappings as $wrap)
                            <label class="p-3.5 rounded-xl border border-[#EADFD4] has-checked:border-[#B85D66] has-checked:bg-[#F8E8EA]/40 flex items-center justify-between cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="wrapping_id" value="{{ $wrap->id }}" data-price="{{ $wrap->price }}" data-name="{{ $wrap->name }}"
                                           {{ $loop->first ? 'checked' : '' }} onchange="recalculateTotal()" class="text-[#B85D66] focus:ring-[#B85D66]">
                                    
                                    @if($wrap->image)
                                        <img src="{{ $wrap->image }}" alt="{{ $wrap->name }}" class="w-12 h-12 rounded-lg object-cover border border-[#EADFD4]">
                                    @endif

                                    <div>
                                        <span class="text-xs font-semibold text-[#2A2626] block">{{ $wrap->name }}</span>
                                        <span class="text-[11px] text-[#6B6565]">+ Rp {{ number_format($wrap->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </section>

                <!-- Step 3: Ribbon -->
                <section class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-xs font-bold">3</span>
                        <div>
                            <h2 class="font-serif text-xl text-[#2A2626]">Pilih Pita Satin</h2>
                            <p class="text-xs text-[#6B6565]">Sentuhan pita berkualitas tinggi dengan simpul ganda artistik</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                        @foreach($ribbons as $ribbon)
                            <label class="p-3 rounded-xl border border-[#EADFD4] has-checked:border-[#B85D66] has-checked:bg-[#F8E8EA]/40 flex items-center gap-3 cursor-pointer transition">
                                <input type="radio" name="ribbon_id" value="{{ $ribbon->id }}" data-price="{{ $ribbon->price }}" data-name="{{ $ribbon->name }}"
                                       {{ $loop->first ? 'checked' : '' }} onchange="recalculateTotal()" class="text-[#B85D66] focus:ring-[#B85D66]">
                                
                                @if($ribbon->image)
                                    <img src="{{ $ribbon->image }}" alt="{{ $ribbon->name }}" class="w-10 h-10 rounded-lg object-cover border border-[#EADFD4]">
                                @endif

                                <div>
                                    <span class="text-xs font-semibold text-[#2A2626] block">{{ $ribbon->name }}</span>
                                    <span class="text-[11px] text-[#6B6565]">+ Rp {{ number_format($ribbon->price, 0, ',', '.') }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </section>

                <!-- Step 4: Add-ons (Optional) -->
                <section class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-xs font-bold">4</span>
                        <div>
                            <h2 class="font-serif text-xl text-[#2A2626]">Aksesoris Tambahan (Opsional)</h2>
                            <p class="text-xs text-[#6B6565]">Lengkapi buket Anda dengan cokelat, lampu peri, atau boneka</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        @foreach($addons as $addon)
                            <label class="p-3.5 rounded-xl border border-[#EADFD4] has-checked:border-[#B85D66] has-checked:bg-[#F8E8EA]/40 flex items-center justify-between cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="addons[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}" data-name="{{ $addon->name }}"
                                           onchange="recalculateTotal()" class="rounded text-[#B85D66] focus:ring-[#B85D66]">
                                    
                                    @if($addon->image)
                                        <img src="{{ $addon->image }}" alt="{{ $addon->name }}" class="w-10 h-10 rounded-lg object-cover border border-[#EADFD4]">
                                    @endif

                                    <div>
                                        <span class="text-xs font-semibold text-[#2A2626] block">{{ $addon->name }}</span>
                                        <span class="text-[11px] text-[#B85D66]">+ Rp {{ number_format($addon->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </section>

                <!-- Step 5: Card Message -->
                <section class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-xs font-bold">5</span>
                        <div>
                            <h2 class="font-serif text-xl text-[#2A2626]">Pesan Kartu Ucapan</h2>
                            <p class="text-xs text-[#6B6565]">Ditulis tangan rapi di atas kartu motif bunga Desy Florist</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2">
                        <div>
                            <label class="block font-medium text-[#2A2626] mb-1">Kepada (Untuk):</label>
                            <input type="text" name="card_to" placeholder="Nama Penerima" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
                        </div>
                        <div>
                            <label class="block font-medium text-[#2A2626] mb-1">Dari:</label>
                            <input type="text" name="card_from" placeholder="Nama Anda" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block font-medium text-[#2A2626] mb-1">Pesan Ucapan Tulus:</label>
                            <textarea name="card_message" rows="3" placeholder="Tuliskan pesan Anda..." class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66]"></textarea>
                        </div>
                    </div>
                </section>

            </div>

            <!-- Right: Realtime Summary Sticky Card (4 cols) -->
            <div class="lg:col-span-4 sticky top-28 space-y-4">
                <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-sm space-y-4">
                    <h3 class="font-serif text-xl text-[#2A2626] pb-3 border-b border-[#EADFD4]">
                        Ringkasan Buket
                    </h3>

                    <div class="space-y-2 text-xs text-[#6B6565]">
                        <div class="flex justify-between">
                            <span>Jasa Merangkai & Konsultasi</span>
                            <span class="font-semibold text-[#2A2626]">Rp 50.000</span>
                        </div>

                        <!-- Dynamic Flower Items -->
                        <div id="summaryFlowers" class="pt-2 border-t border-[#F3ECE4] space-y-1">
                            <span class="text-[11px] text-[#9B9494] italic">Belum ada bunga dipilih</span>
                        </div>

                        <!-- Wrapping & Ribbon -->
                        <div id="summaryWrapping" class="flex justify-between pt-2 border-t border-[#F3ECE4]">
                            <span>Wrapping Paper</span>
                            <span class="font-semibold text-[#2A2626]" id="wrapPriceLabel">Rp 0</span>
                        </div>

                        <div id="summaryRibbon" class="flex justify-between">
                            <span>Pita Satin</span>
                            <span class="font-semibold text-[#2A2626]" id="ribbonPriceLabel">Rp 0</span>
                        </div>

                        <!-- Addons -->
                        <div id="summaryAddons" class="pt-2 border-t border-[#F3ECE4] space-y-1"></div>
                    </div>

                    <!-- Grand Total -->
                    <div class="pt-4 border-t border-[#EADFD4] flex items-baseline justify-between">
                        <span class="text-xs uppercase tracking-wider font-semibold text-[#2A2626]">Estimasi Total:</span>
                        <span class="font-serif text-2xl font-bold text-[#B85D66]" id="totalEstimateLabel">
                            Rp 50.000
                        </span>
                    </div>

                    <button type="submit" id="submitBouquetBtn"
                            class="w-full py-3.5 px-4 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
                        Masukkan ke Keranjang Belanja
                    </button>

                    <p class="text-[10px] text-center text-[#9B9494]">
                        Foto hasil akhir buket akan dikirimkan via WhatsApp sebelum pengiriman.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function updateFlowerQty(index, delta) {
        const input = document.getElementById('flower_qty_' + index);
        let val = parseInt(input.value) || 0;
        val += delta;
        if (val < 0) val = 0;
        if (val > 50) val = 50;
        input.value = val;
        recalculateTotal();
    }

    function recalculateTotal() {
        let baseFee = 50000;
        let total = baseFee;

        // Flowers
        let flowerSummaryHtml = '';
        let totalFlowerCount = 0;
        const flowerInputs = document.querySelectorAll('input[id^="flower_qty_"]');
        flowerInputs.forEach(inp => {
            const qty = parseInt(inp.value) || 0;
            if (qty > 0) {
                const price = parseFloat(inp.getAttribute('data-price')) || 0;
                const name = inp.getAttribute('data-name');
                const sub = price * qty;
                total += sub;
                totalFlowerCount += qty;
                flowerSummaryHtml += `<div class="flex justify-between text-[11px]"><span>• ${qty}x ${name}</span><span class="font-medium">Rp ${sub.toLocaleString('id-ID')}</span></div>`;
            }
        });

        const flowerContainer = document.getElementById('summaryFlowers');
        if (flowerSummaryHtml) {
            flowerContainer.innerHTML = flowerSummaryHtml;
        } else {
            flowerContainer.innerHTML = '<span class="text-[11px] text-[#9B9494] italic">Belum ada bunga dipilih</span>';
        }

        // Wrapping
        const checkedWrap = document.querySelector('input[name="wrapping_id"]:checked');
        if (checkedWrap) {
            const wPrice = parseFloat(checkedWrap.getAttribute('data-price')) || 0;
            total += wPrice;
            document.getElementById('wrapPriceLabel').textContent = 'Rp ' + wPrice.toLocaleString('id-ID');
        }

        // Ribbon
        const checkedRibbon = document.querySelector('input[name="ribbon_id"]:checked');
        if (checkedRibbon) {
            const rPrice = parseFloat(checkedRibbon.getAttribute('data-price')) || 0;
            total += rPrice;
            document.getElementById('ribbonPriceLabel').textContent = 'Rp ' + rPrice.toLocaleString('id-ID');
        }

        // Addons
        let addonSummaryHtml = '';
        const checkedAddons = document.querySelectorAll('input[name="addons[]"]:checked');
        checkedAddons.forEach(ad => {
            const aPrice = parseFloat(ad.getAttribute('data-price')) || 0;
            const aName = ad.getAttribute('data-name');
            total += aPrice;
            addonSummaryHtml += `<div class="flex justify-between text-[11px]"><span>+ ${aName}</span><span class="font-medium">Rp ${aPrice.toLocaleString('id-ID')}</span></div>`;
        });
        document.getElementById('summaryAddons').innerHTML = addonSummaryHtml;

        document.getElementById('totalEstimateLabel').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Pre-select 5 roses for quick start
        const firstFlower = document.getElementById('flower_qty_0');
        if (firstFlower) {
            firstFlower.value = 5;
        }
        recalculateTotal();
    });
</script>
@endsection
