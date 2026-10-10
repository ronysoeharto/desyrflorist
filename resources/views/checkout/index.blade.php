@extends('layouts.app')

@section('title', 'Checkout — Desy Florist')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .leaflet-pane img,
    .leaflet-tile,
    .leaflet-marker-icon,
    .leaflet-marker-shadow,
    .leaflet-tile-container img {
        max-width: none !important;
        max-height: none !important;
        box-shadow: none !important;
    }
    .leaflet-container {
        font-family: inherit;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-[#B85D66]">Keranjang</a>
            <span>/</span>
            <span class="text-[#2A2626]">Checkout</span>
        </nav>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Selesaikan Pemesanan Bunga</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Checkout Sections (8 cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Section 1: Recipient Information -->
                <section class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-xs font-bold">1</span>
                        <div>
                            <h2 class="font-serif text-xl text-[#2A2626]">Informasi Penerima Bunga</h2>
                            <p class="text-xs text-[#6B6565]">Pastikan nama dan nomor penerima benar agar kurir dapat berkoordinasi</p>
                        </div>
                    </div>

                    @if($defaultAddress)
                        <div class="p-3 bg-[#EEF2EB] rounded-xl border border-[#7B8B75]/40 text-xs flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-[#2A2626]">Gunakan Alamat Utama: {{ $defaultAddress->label }}</span>
                                <div class="text-[11px] text-[#6B6565]">{{ $defaultAddress->address }}, {{ $defaultAddress->city }}</div>
                            </div>
                            <button type="button" onclick="fillAddress('{{ addslashes($defaultAddress->recipient_name) }}', '{{ addslashes($defaultAddress->phone) }}', '{{ addslashes($defaultAddress->address) }}')"
                                    class="text-xs text-[#5C6A57] font-bold hover:underline">
                                Pasang Alamat Ini
                            </button>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2">
                        <div>
                            <label class="block font-medium text-[#2A2626] mb-1">Nama Penerima Bunga *</label>
                            <input type="text" id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $user?->name) }}" required
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                                   placeholder="Contoh: Clara Maharani">
                        </div>

                        <div>
                            <label class="block font-medium text-[#2A2626] mb-1">Nomor WhatsApp / HP Penerima *</label>
                            <input type="text" id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone', $user?->phone) }}" required
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                                   placeholder="08123456789">
                        </div>

                        <div class="sm:col-span-2 space-y-3 pt-2">
                            <!-- Label & Instructions -->
                            <div class="flex items-center justify-between">
                                <label class="block font-medium text-[#2A2626]">
                                    Wilayah Pengiriman *
                                </label>
                                <span class="text-[11px] text-[#B85D66] font-semibold">Pilih Wilayah Terstruktur</span>
                            </div>

                            <!-- Shopee-Style Trigger Box -->
                            <div id="regionPickerTrigger" onclick="openRegionModal()"
                                 class="p-3.5 rounded-xl border border-[#D8BABF] bg-[#FAF8F5]/60 hover:bg-[#F8E8EA]/30 cursor-pointer flex items-center justify-between transition group shadow-2xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white border border-[#EADFD4] flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition shadow-2xs">
                                        📍
                                    </div>
                                    <div class="space-y-0.5">
                                        <span class="text-[10px] text-[#9B9494] font-bold uppercase tracking-wider block">
                                            Provinsi, Kota/Kabupaten, Kecamatan, Kode Pos
                                        </span>
                                        <div id="selectedRegionText" class="text-xs font-semibold text-[#2A2626] flex items-center gap-1.5">
                                            <span class="text-[#B85D66]">Pilih Wilayah Pengiriman</span>
                                            <span class="text-[11px] text-[#6B6565] font-normal">(Klik untuk membuka pilihan)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs font-bold text-[#B85D66] group-hover:underline">
                                    <span id="regionActionText">Pilih</span>
                                    <span>›</span>
                                </div>
                            </div>

                            <!-- Hidden Fields for Backend Sync -->
                            <input type="hidden" id="selected_province" name="province" value="{{ old('province', 'Jawa Timur') }}">
                            <input type="hidden" id="selected_city" name="city" value="{{ old('city', 'Kota Surabaya') }}">
                            <input type="hidden" id="selected_district" name="district" value="{{ old('district', 'Bubutan') }}">
                            <input type="hidden" id="selected_subdistrict" name="subdistrict" value="{{ old('subdistrict', 'Bubutan') }}">
                            <input type="hidden" id="selected_postal_code" name="postal_code" value="{{ old('postal_code', '60174') }}">

                            <!-- Quick Inline Selectors Toggle -->
                            <div class="pt-1">
                                <button type="button" onclick="toggleInlineDropdowns()" class="text-[11px] text-[#6B6565] hover:text-[#B85D66] flex items-center gap-1 transition">
                                    <span id="toggleDropdownIcon">▼</span>
                                    <span>Atau pilih via dropdown langsung (Provinsi / Kota / Kecamatan)</span>
                                </button>
                                
                                <div id="inlineDropdownsContainer" class="hidden grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-2 text-xs">
                                    <div>
                                        <label class="block text-[11px] text-[#6B6565] mb-1">Provinsi:</label>
                                        <select id="inlineProvinceSelect" onchange="onInlineProvinceChange()" class="w-full p-2 rounded-xl border border-[#D8BABF] bg-white text-xs">
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-[#6B6565] mb-1">Kota/Kabupaten:</label>
                                        <select id="inlineCitySelect" onchange="onInlineCityChange()" class="w-full p-2 rounded-xl border border-[#D8BABF] bg-white text-xs">
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-[#6B6565] mb-1">Kecamatan:</label>
                                        <select id="inlineDistrictSelect" onchange="onInlineDistrictChange()" class="w-full p-2 rounded-xl border border-[#D8BABF] bg-white text-xs">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Street Detail & House Location Input -->
                        <div class="sm:col-span-2 space-y-1.5 pt-2">
                            <label class="block font-medium text-[#2A2626]">
                                Titik Tujuan Rumah / Nama Jalan & Nomor Rumah (Manual) *
                            </label>
                            <textarea id="street_address" name="street_address" rows="2" required oninput="syncCombinedAddress()"
                                      placeholder="Nama jalan, nomor rumah/gedung, RT/RW, blok, nomor lantai/unit (Contoh: Jl. Bubutan No. 45, RT 02 / RW 03, Pagar Cokelat)"
                                      class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-xs">{{ old('street_address') }}</textarea>
                            <span class="text-[10px] text-[#9B9494] block">Tuliskan nama jalan dan nomor rumah Anda secara spesifik untuk mempermudah kurir toko.</span>
                        </div>

                        <!-- Manual Landmark / Delivery Notes & GPS Pin -->
                        <div class="sm:col-span-2 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block font-medium text-[#2A2626]">
                                    Catatan Patokan / Titik Antar Rumah Kurir (Opsional)
                                </label>
                                <button type="button" onclick="detectGpsLocation()"
                                        id="gpsLocBtn"
                                        class="text-[11px] font-bold text-[#5C6A57] hover:text-[#2A2626] flex items-center gap-1 transition">
                                    <span>🎯 Pasang Titik GPS Lokasi Rumah</span>
                                </button>
                            </div>
                            <input type="text" id="delivery_notes" name="delivery_notes" value="{{ old('delivery_notes') }}" oninput="syncCombinedAddress()"
                                   placeholder="Contoh: Seberang Masjid Al-Ikhlas, pagar warna hitam, atau titipkan satpam pos depan"
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-xs">
                            <div id="gpsStatusMessage" class="hidden text-[10px] text-[#5C6A57] font-medium pt-0.5"></div>
                        </div>

                        <!-- Hidden Coordinates & Routing Inputs -->
                        <input type="hidden" id="recipient_latitude" name="recipient_latitude" value="{{ old('recipient_latitude', '-7.2519') }}">
                        <input type="hidden" id="recipient_longitude" name="recipient_longitude" value="{{ old('recipient_longitude', '112.7320') }}">
                        <input type="hidden" id="distance_km" name="distance_km" value="{{ old('distance_km', '3.5') }}">

                        <!-- Alert Box: Wilayah di Luar Area Layanan Florist -->
                        <div id="unservicedAlertBox" class="hidden sm:col-span-2 p-4 bg-red-50 rounded-2xl border border-red-300 text-xs text-red-800 space-y-2 animate-in fade-in">
                            <div class="flex items-center gap-2 font-bold text-red-700">
                                <span class="text-lg">🚫</span>
                                <span id="unservicedTitle" class="text-sm">Pengiriman Belum Tersedia untuk Wilayah Ini</span>
                            </div>
                            <p id="unservicedDesc" class="text-xs text-red-700 leading-relaxed font-normal">
                                {{ $unservicedMessage }}
                            </p>
                            <div class="p-2.5 bg-white/80 rounded-xl border border-red-200 text-[11px] text-red-800 flex items-center justify-between">
                                <span>Kecamatan yang dipilih saat ini belum diaktifkan dalam kapasitas operasional toko.</span>
                                <button type="button" onclick="openRegionModal()" class="font-bold underline text-[#B85D66]">
                                    Ubah Wilayah
                                </button>
                            </div>
                        </div>

                        <!-- Interactive Map: Store to Recipient Routing Map -->
                        <div class="sm:col-span-2 space-y-2 pt-2">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <div class="flex items-center gap-2">
                                    <label class="block font-bold text-[#2A2626] text-xs">
                                        🗺️ Peta Titik Penerima & Rute Toko Surabaya Pusat
                                    </label>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#B85D66]/10 text-[#B85D66] font-semibold">
                                        Rute Fleksibel
                                    </span>
                                </div>
                                <span class="text-[11px] text-[#5C6A57] font-semibold" id="mapPinStatus">
                                    ✓ Geser Pin untuk Sesuaikan Titik Rumah
                                </span>
                            </div>

                            <!-- Map Canvas Container -->
                            <div class="relative rounded-2xl overflow-hidden border border-[#D8BABF] shadow-xs">
                                <div id="checkoutRouteMap" class="w-full h-64 sm:h-72 bg-[#FAF8F5] z-10"></div>
                                
                                <!-- Floating Controls on Map -->
                                <div class="absolute top-2 right-2 z-20 flex flex-col gap-1.5">
                                    <button type="button" onclick="detectGpsLocation()"
                                            class="px-2.5 py-1.5 bg-white/95 hover:bg-white text-[#2A2626] rounded-xl border border-[#D8BABF] shadow-sm text-[11px] font-semibold flex items-center gap-1 transition backdrop-blur-xs">
                                        <span>🎯 Titik GPS Rumah</span>
                                    </button>
                                    <button type="button" onclick="resetPinToSelectedDistrict()"
                                            class="px-2.5 py-1.5 bg-white/95 hover:bg-white text-[#2A2626] rounded-xl border border-[#D8BABF] shadow-sm text-[11px] font-semibold flex items-center gap-1 transition backdrop-blur-xs">
                                        <span>🔄 Pusatkan Kecamatan</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Real-time Route Calculation Info Box -->
                            <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] text-xs grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-center">
                                <div class="space-y-0.5">
                                    <span class="text-[10px] text-[#9B9494] uppercase font-bold block">Jarak Rute Perjalanan</span>
                                    <span class="text-sm font-bold text-[#2A2626]" id="routeDistanceText">~3.5 KM</span>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="text-[10px] text-[#9B9494] uppercase font-bold block">Estimasi Perjalanan Kurir</span>
                                    <span class="text-sm font-bold text-[#5C6A57]" id="routeDurationText">~15 - 25 Menit</span>
                                </div>
                                <div class="space-y-0.5 sm:text-right">
                                    <span class="text-[10px] text-[#9B9494] uppercase font-bold block">Titik Awal Pengiriman</span>
                                    <span class="text-xs font-semibold text-[#B85D66] block truncate">{{ $storeSettings['store_district'] ?? 'Surabaya Pusat' }}</span>
                                </div>
                            </div>
                            <span class="text-[10px] text-[#9B9494] block italic">
                                * Ongkos kirim dan estimasi waktu dihitung dari titik rute toko Desy Florist Surabaya Pusat. Anda dapat menggeser pin lokasi rumah untuk koordinat penyerahan bunga yang lebih presisi.
                            </span>
                        </div>

                        <!-- Live Combined Address Preview & Synchronized Form Input -->
                        <div class="sm:col-span-2 p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-[#2A2626] text-[11px] uppercase tracking-wider">Pratinjau Alamat Lengkap Kurir:</span>
                                <span class="text-[10px] text-[#5C6A57] font-semibold">✓ Otomatis Tersusun</span>
                            </div>
                            <p id="combinedAddressPreview" class="text-xs text-[#6B6565] leading-relaxed italic">
                                Belum ada alamat yang dipilih.
                            </p>
                            <!-- Hidden actual textarea submitted with form -->
                            <textarea id="delivery_address" name="delivery_address" class="hidden" required>{{ old('delivery_address') }}</textarea>
                        </div>
                    </div>
                </section>

                <!-- Section 2: Delivery Zone & Schedule -->
                <section class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-xs font-bold">2</span>
                            <div>
                                <h2 class="font-serif text-xl text-[#2A2626]">Area & Waktu Pengiriman</h2>
                                <p class="text-xs text-[#6B6565]">Area pengantaran dan slot waktu otomatis mengikuti wilayah pengiriman pilihan Anda</p>
                            </div>
                        </div>
                        <span id="zoneSyncBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#5C6A57]/10 text-[#5C6A57] text-[11px] font-semibold border border-[#5C6A57]/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#5C6A57] animate-pulse"></span>
                            <span>Sinkron Otomatis dengan Wilayah</span>
                        </span>
                    </div>

                    <!-- Highlighted Active Area Card (Follows Customer's Region) -->
                    <div id="activeZoneSummaryBox" class="p-4 rounded-xl border border-[#B85D66] bg-[#FAF8F5]/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white border border-[#EADFD4] flex items-center justify-center text-lg shrink-0 shadow-2xs">
                                🚚
                            </div>
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-[#B85D66] text-white">Area Pengiriman Terhubung</span>
                                    <span id="matchedAreaIndicator" class="text-[11px] text-[#5C6A57] font-semibold">✓ Otomatis Mengikuti Wilayah Anda</span>
                                </div>
                                <h4 id="activeZoneName" class="font-bold text-sm text-[#2A2626]">Menyesuaikan Area...</h4>
                                <p id="activeZoneDescription" class="text-[11px] text-[#6B6565]">
                                    Menyesuaikan dengan kecamatan dan kota yang Anda pilih di atas.
                                </p>
                            </div>
                        </div>
                        <div class="sm:text-right shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-[#EADFD4]">
                            <span class="text-[10px] text-[#9B9494] block uppercase font-medium">Tarif Ongkos Kirim</span>
                            <span id="activeZoneFee" class="text-base font-bold text-[#B85D66]">Rp 0</span>
                        </div>
                    </div>

                    <!-- Zone Options Grid (Interactive / Filtered) -->
                    <div class="space-y-2 pt-1">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-medium text-[#2A2626]">Pilihan Zona Pengiriman *</label>
                            <button type="button" onclick="toggleAllZones()" id="toggleAllZonesBtn" class="text-[11px] text-[#B85D66] font-semibold hover:underline">
                                Lihat Semua Zona Lainnya
                            </button>
                        </div>

                        <div id="deliveryZonesGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($deliveryZones as $zone)
                                <label id="zoneCard-{{ $zone->id }}"
                                       data-zone-id="{{ $zone->id }}"
                                       data-zone-name="{{ $zone->name }}"
                                       data-areas="{{ strtolower(implode(',', $zone->areas)) }}"
                                       data-fee="{{ $zone->delivery_fee }}"
                                       data-slots="{{ json_encode($zone->available_slots) }}"
                                       class="zone-card p-3.5 rounded-xl border border-[#EADFD4] has-checked:border-[#B85D66] has-checked:bg-[#F8E8EA]/40 flex items-start gap-3 cursor-pointer transition relative">
                                    <input type="radio" name="delivery_zone_id" value="{{ $zone->id }}" data-fee="{{ $zone->delivery_fee }}"
                                           {{ $loop->first ? 'checked' : '' }} onchange="onManualZoneChange({{ $zone->id }})" class="mt-0.5 text-[#B85D66] focus:ring-[#B85D66]">
                                    <div class="text-xs flex-1">
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="font-bold text-[#2A2626] block">{{ $zone->name }}</span>
                                            <span id="zoneMatchBadge-{{ $zone->id }}" class="hidden text-[10px] font-bold px-1.5 py-0.5 rounded bg-[#5C6A57]/15 text-[#5C6A57]">
                                                Sesuai Wilayah
                                            </span>
                                        </div>
                                        <span class="text-[#B85D66] font-semibold block mt-0.5">
                                            Ongkir: Rp {{ number_format($zone->delivery_fee, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[10px] text-[#9B9494] block mt-0.5">
                                            Cakupan: {{ implode(', ', array_slice($zone->areas, 0, 4)) }}...
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Date & Dynamic Delivery Slots -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-1">
                        <div>
                            <label class="block font-medium text-[#2A2626] mb-1">Tanggal Antar *</label>
                            <input type="date" name="delivery_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-white text-xs">
                            <span class="text-[10px] text-[#9B9494] block mt-1">Pengiriman bisa hari ini (Same-day) atau tanggal yang Anda tentukan</span>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-medium text-[#2A2626]">Pilihan Slot Jam Pengantaran *</label>
                                <span class="text-[10px] text-[#5C6A57] font-semibold" id="slotSyncIndicator">✓ Mengikuti Area Terpilih</span>
                            </div>
                            <select id="delivery_slot" name="delivery_slot" required class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-white text-xs">
                                <option value="09:00 - 12:00 (Pagi)">Pagi (09:00 - 12:00)</option>
                                <option value="12:00 - 15:00 (Siang)">Siang (12:00 - 15:00)</option>
                                <option value="15:00 - 18:00 (Sore)">Sore (15:00 - 18:00)</option>
                                <option value="18:00 - 21:00 (Malam)">Malam (18:00 - 21:00)</option>
                            </select>
                            <span id="slotHelperText" class="text-[10px] text-[#6B6565] block mt-1">
                                Slot jam pengiriman yang tersedia untuk wilayah Anda.
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Section 3: Personalized Greeting Card -->
                <section class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-xs font-bold">3</span>
                        <div>
                            <h2 class="font-serif text-xl text-[#2A2626]">Pesan Kartu Ucapan (Gratis)</h2>
                            <p class="text-xs text-[#6B6565]">Kartu ucapan eksklusif Desy Florist dengan cetak motif bunga</p>
                        </div>
                    </div>

                    <div class="text-xs space-y-2 pt-2">
                        <textarea name="greeting_message" rows="3"
                                  placeholder="Contoh: Selamat ulang tahun yang ke-25, sayang! Semoga selalu bahagia dan bersinar. Dari: Adrian"
                                  class="w-full p-3 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-xs">{{ old('greeting_message') }}</textarea>
                    </div>
                </section>

                <!-- Section 4: Payment Method -->
                <section class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-xs font-bold">4</span>
                        <div>
                            <h2 class="font-serif text-xl text-[#2A2626]">Metode Pembayaran</h2>
                            <p class="text-xs text-[#6B6565]">Pilih saluran pembayaran yang paling nyaman untuk Anda</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs">
                        <label class="p-3.5 rounded-xl border border-[#EADFD4] has-checked:border-[#B85D66] has-checked:bg-[#F8E8EA]/40 flex items-start gap-3 cursor-pointer transition">
                            <input type="radio" name="payment_method" value="bca" checked class="mt-0.5 text-[#B85D66] focus:ring-[#B85D66]">
                            <div>
                                <span class="font-bold text-[#2A2626] block">Transfer Bank BCA</span>
                                <span class="text-[11px] text-[#6B6565] block">No. Rek: 883-059-1234 (a.n Desy Florist)</span>
                            </div>
                        </label>

                        <label class="p-3.5 rounded-xl border border-[#EADFD4] has-checked:border-[#B85D66] has-checked:bg-[#F8E8EA]/40 flex items-start gap-3 cursor-pointer transition">
                            <input type="radio" name="payment_method" value="mandiri" class="mt-0.5 text-[#B85D66] focus:ring-[#B85D66]">
                            <div>
                                <span class="font-bold text-[#2A2626] block">Transfer Bank Mandiri</span>
                                <span class="text-[11px] text-[#6B6565] block">No. Rek: 137-00-1234567-8</span>
                            </div>
                        </label>

                        <label class="p-3.5 rounded-xl border border-[#EADFD4] has-checked:border-[#B85D66] has-checked:bg-[#F8E8EA]/40 flex items-start gap-3 cursor-pointer transition">
                            <input type="radio" name="payment_method" value="qris" class="mt-0.5 text-[#B85D66] focus:ring-[#B85D66]">
                            <div>
                                <span class="font-bold text-[#2A2626] block">QRIS / E-Wallet Instant</span>
                                <span class="text-[11px] text-[#6B6565] block">GoPay, OVO, ShopeePay, DANA</span>
                            </div>
                        </label>

                        <label class="p-3.5 rounded-xl border border-[#EADFD4] has-checked:border-[#B85D66] has-checked:bg-[#F8E8EA]/40 flex items-start gap-3 cursor-pointer transition">
                            <input type="radio" name="payment_method" value="cod" class="mt-0.5 text-[#B85D66] focus:ring-[#B85D66]">
                            <div>
                                <span class="font-bold text-[#2A2626] block">Bayar Saat Diterima (COD)</span>
                                <span class="text-[11px] text-[#6B6565] block">Bayar tunai ke kurir toko saat bunga tiba</span>
                            </div>
                        </label>
                    </div>
                </section>

            </div>

            <!-- Right: Sticky Order Summary (4 cols) -->
            <div class="lg:col-span-4 sticky top-28 space-y-4">
                <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-sm space-y-4">
                    <h3 class="font-serif text-xl text-[#2A2626] pb-3 border-b border-[#EADFD4]">
                        Ringkasan Belanja
                    </h3>

                    <!-- Order items list preview -->
                    <div class="space-y-3 max-h-56 overflow-y-auto pr-1">
                        @foreach($cart->items as $it)
                            <div class="flex items-center gap-3 text-xs">
                                <img src="{{ $it->product->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=200&q=80' }}"
                                     alt="{{ $it->product->name }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-[#2A2626] truncate">{{ $it->product->name }}</h4>
                                    <span class="text-[10px] text-[#9B9494]">{{ $it->quantity }}x Rp {{ number_format($it->unit_price, 0, ',', '.') }}</span>
                                </div>
                                <span class="font-medium text-[#2A2626] text-xs">
                                    Rp {{ number_format($it->unit_price * $it->quantity, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 border-t border-[#F3ECE4] space-y-2 text-xs text-[#6B6565]">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-[#2A2626]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($discountAmount > 0)
                            <div class="flex justify-between text-[#5C6A57]">
                                <span>Kupon ({{ $coupon->code }})</span>
                                <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        @php
                            $initialZoneFee = $deliveryZones->first()?->delivery_fee ?? 0;
                            $isInitialFreeShipping = ($freeShippingMin > 0 && $subtotal >= $freeShippingMin);
                            $initialDeliveryFee = $isInitialFreeShipping ? 0 : $initialZoneFee;
                            $initialGrandTotal = max(0, ($subtotal - $discountAmount) + $initialDeliveryFee);
                        @endphp

                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span class="font-semibold text-[#2A2626]" id="shippingFeeLabel">
                                @if($isInitialFreeShipping)
                                    <span class="text-[#5C6A57] font-bold">GRATIS (Promo)</span>
                                @else
                                    Rp {{ number_format($initialDeliveryFee, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#EADFD4] flex items-baseline justify-between">
                        <span class="text-xs uppercase tracking-wider font-semibold text-[#2A2626]">Total Akhir:</span>
                        <span class="font-serif text-2xl font-bold text-[#B85D66]" id="grandTotalLabel">
                            Rp {{ number_format($initialGrandTotal, 0, ',', '.') }}
                        </span>
                    </div>

                    <button type="submit" id="submitOrderBtn"
                            class="w-full py-4 px-4 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm text-center">
                        Konfirmasi & Buat Pesanan
                    </button>

                    <p class="text-[10px] text-center text-[#9B9494] leading-relaxed">
                        Dengan menekan tombol di atas, Anda menyetujui jadwal dan ketentuan pengiriman Desy Florist.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- ========================================== -->
<!-- SHOPEE-STYLE HIERARCHICAL REGION PICKER MODAL -->
<!-- ========================================== -->
<div id="regionModal" class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-black/60 backdrop-blur-xs transition duration-300">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl border border-[#EADFD4] shadow-2xl overflow-hidden flex flex-col max-h-[85vh] sm:max-h-[80vh] animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="p-4 sm:p-5 border-b border-[#EADFD4] flex items-center justify-between bg-[#FAF8F5]">
            <div class="flex items-center gap-2.5">
                <span class="text-xl">📍</span>
                <div>
                    <h3 class="font-serif text-base sm:text-lg text-[#2A2626] font-medium leading-tight">Pilih Wilayah Pengiriman</h3>
                    <p class="text-[11px] text-[#6B6565]">Pilih Provinsi, Kota, Kecamatan & Kode Pos</p>
                </div>
            </div>
            <button type="button" onclick="closeRegionModal()" class="w-8 h-8 rounded-full bg-white border border-[#EADFD4] text-[#6B6565] hover:text-[#B85D66] hover:bg-[#F8E8EA] flex items-center justify-center text-sm font-bold transition">
                ✕
            </button>
        </div>

        <!-- Quick Search Bar -->
        <div class="p-3 bg-white border-b border-[#F3ECE4]">
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-xs text-[#9B9494]">🔍</span>
                <input type="text" id="regionSearchInput" oninput="onRegionSearch(this.value)"
                       placeholder="Cari cepat nama kecamatan / kota (contoh: Bubutan, Menteng, Serpong)..."
                       class="w-full pl-8 pr-3 py-2 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] text-xs bg-[#FAF8F5]/50">
            </div>
            <!-- Quick Search Results Box -->
            <div id="searchResultsBox" class="hidden mt-2 max-h-48 overflow-y-auto rounded-xl border border-[#D8BABF] bg-white divide-y divide-[#F3ECE4] text-xs shadow-md">
            </div>
        </div>

        <!-- Level Step Tabs (Shopee Style) -->
        <div class="flex border-b border-[#EADFD4] bg-[#FAF8F5]/40 text-xs overflow-x-auto scrollbar-none divide-x divide-[#EADFD4]">
            <button type="button" id="tabProvince" onclick="switchModalStep('province')"
                    class="flex-1 py-2.5 px-3 text-center font-bold text-[#B85D66] border-b-2 border-[#B85D66] whitespace-nowrap transition">
                1. Provinsi
            </button>
            <button type="button" id="tabCity" onclick="switchModalStep('city')"
                    class="flex-1 py-2.5 px-3 text-center text-[#6B6565] whitespace-nowrap hover:text-[#2A2626] transition">
                2. Kota/Kab
            </button>
            <button type="button" id="tabDistrict" onclick="switchModalStep('district')"
                    class="flex-1 py-2.5 px-3 text-center text-[#6B6565] whitespace-nowrap hover:text-[#2A2626] transition">
                3. Kecamatan
            </button>
            <button type="button" id="tabSubDistrict" onclick="switchModalStep('subdistrict')"
                    class="flex-1 py-2.5 px-3 text-center text-[#6B6565] whitespace-nowrap hover:text-[#2A2626] transition">
                4. Kelurahan
            </button>
        </div>

        <!-- Breadcrumb Tracker -->
        <div id="modalBreadcrumb" class="px-4 py-2 bg-[#FAF8F5] text-[11px] text-[#6B6565] border-b border-[#F3ECE4] flex items-center gap-1.5 flex-wrap">
            <span class="text-[#9B9494]">Pilihan:</span>
            <span id="crumbProvince" class="font-semibold text-[#2A2626]">-</span>
        </div>

        <!-- Scrollable Options List -->
        <div id="modalItemsList" class="flex-1 overflow-y-auto divide-y divide-[#F3ECE4] p-1 text-xs">
            <!-- Dynamically populated via JS -->
        </div>

        <!-- Modal Footer -->
        <div class="p-3 bg-[#FAF8F5] border-t border-[#EADFD4] flex items-center justify-between text-xs">
            <span class="text-[11px] text-[#6B6565]">Klik wilayah untuk melangkah ke tahap berikutnya</span>
            <button type="button" onclick="closeRegionModal()" class="px-4 py-1.5 bg-white border border-[#D8BABF] rounded-lg text-[#2A2626] hover:bg-[#F8E8EA] text-xs font-semibold">
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- Leaflet JS & Indonesian Regions Dataset -->
<!-- Leaflet JS & Indonesian Regions Dataset -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{ asset('js/indonesia-regions.js') }}?v={{ time() }}"></script>

<script>
    const subtotal = {{ $subtotal ?? 0 }};
    const discount = {{ $discountAmount ?? 0 }};
    const freeShippingMin = {{ $freeShippingMin ?? 0 }};

    // Store & Routing Configuration
    const STORE_LAT = parseFloat("{{ $storeSettings['store_latitude'] ?? '-7.2654' }}");
    const STORE_LNG = parseFloat("{{ $storeSettings['store_longitude'] ?? '112.7483' }}");
    const STORE_NAME = "{{ $storeSettings['store_name'] ?? 'Desy Florist' }}";
    const STORE_DISTRICT = "{{ $storeSettings['store_district'] ?? 'Surabaya Pusat' }}";
    const ENABLED_DISTRICTS = @json($enabledDistricts ?? []);
    const MAX_SERVICE_DISTANCE = parseFloat("{{ $maxDistanceKm ?? 25.0 }}") || 25.0;
    const UNSERVICED_MESSAGE = "{{ addslashes($unservicedMessage ?? 'Di luar jangkauan') }}";

    // Global reference to full Indonesian regions & delivery zones
    const INDONESIA_REGIONS = window.INDONESIA_REGIONS || [];
    const DELIVERY_ZONES = @json($deliveryZones ?? []);

    // Current selection state
    let state = {
        province: document.getElementById('selected_province')?.value || 'Jawa Timur',
        city: document.getElementById('selected_city')?.value || 'Kota Surabaya',
        district: document.getElementById('selected_district')?.value || 'Bubutan',
        subdistrict: document.getElementById('selected_subdistrict')?.value || 'Bubutan',
        postalCode: document.getElementById('selected_postal_code')?.value || '60174',
        currentStep: 'province'
    };

    // Modal Control
    function openRegionModal() {
        const modal = document.getElementById('regionModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            switchModalStep(state.currentStep || 'province');
        }
    }

    function closeRegionModal() {
        const modal = document.getElementById('regionModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
            const searchInput = document.getElementById('regionSearchInput');
            if (searchInput) searchInput.value = '';
            const searchResultsBox = document.getElementById('searchResultsBox');
            if (searchResultsBox) searchResultsBox.classList.add('hidden');
        }
    }

    function switchModalStep(step) {
        state.currentStep = step;
        const tabs = ['province', 'city', 'district', 'subdistrict'];
        tabs.forEach(t => {
            const btn = document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1));
            if (btn) {
                if (t === step) {
                    btn.className = 'flex-1 py-2.5 px-3 text-center font-bold text-[#B85D66] border-b-2 border-[#B85D66] bg-white whitespace-nowrap transition';
                } else {
                    btn.className = 'flex-1 py-2.5 px-3 text-center text-[#6B6565] whitespace-nowrap hover:text-[#2A2626] transition';
                }
            }
        });

        updateBreadcrumb();
        renderModalList(step);
    }

    function updateBreadcrumb() {
        const parts = [];
        if (state.province) parts.push(state.province);
        if (state.city) parts.push(state.city);
        if (state.district) parts.push('Kec. ' + state.district);
        if (state.subdistrict) parts.push('Kel. ' + state.subdistrict + (state.postalCode ? ' (' + state.postalCode + ')' : ''));
        const crumbEl = document.getElementById('crumbProvince');
        if (crumbEl) {
            crumbEl.textContent = parts.length ? parts.join(' › ') : 'Pilih Provinsi';
        }
    }

    function renderModalList(step) {
        const listEl = document.getElementById('modalItemsList');
        if (!listEl) return;
        listEl.innerHTML = '';

        if (!INDONESIA_REGIONS || INDONESIA_REGIONS.length === 0) {
            listEl.innerHTML = '<div class="p-4 text-center text-xs text-[#9B9494]">Memuat data wilayah...</div>';
            return;
        }

        if (step === 'province') {
            INDONESIA_REGIONS.forEach(p => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `w-full p-3.5 text-left flex items-center justify-between hover:bg-[#F8E8EA]/40 transition ${state.province === p.province ? 'bg-[#FAF8F5] font-bold text-[#B85D66]' : 'text-[#2A2626]'}`;
                btn.innerHTML = `<span>${p.province}</span><span class="text-[#9B9494] text-xs">›</span>`;
                btn.onclick = () => selectProvince(p.province);
                listEl.appendChild(btn);
            });
        } else if (step === 'city') {
            const curProv = INDONESIA_REGIONS.find(p => p.province === state.province) || INDONESIA_REGIONS[0];
            (curProv.cities || []).forEach(c => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `w-full p-3.5 text-left flex items-center justify-between hover:bg-[#F8E8EA]/40 transition ${state.city === c.name ? 'bg-[#FAF8F5] font-bold text-[#B85D66]' : 'text-[#2A2626]'}`;
                btn.innerHTML = `<span>${c.name}</span><span class="text-[#9B9494] text-xs">›</span>`;
                btn.onclick = () => selectCity(c.name);
                listEl.appendChild(btn);
            });
        } else if (step === 'district') {
            const curProv = INDONESIA_REGIONS.find(p => p.province === state.province) || INDONESIA_REGIONS[0];
            const curCity = (curProv.cities || []).find(c => c.name === state.city) || (curProv.cities ? curProv.cities[0] : null);
            (curCity?.districts || []).forEach(d => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `w-full p-3.5 text-left flex items-center justify-between hover:bg-[#F8E8EA]/40 transition ${state.district === d.name ? 'bg-[#FAF8F5] font-bold text-[#B85D66]' : 'text-[#2A2626]'}`;
                btn.innerHTML = `
                    <div>
                        <span class="font-medium">Kec. ${d.name}</span>
                        <span class="text-[10px] text-[#9B9494] ml-2">Kode Pos: ${d.postalCode || '-'}</span>
                    </div>
                    <span class="text-[#9B9494] text-xs">›</span>
                `;
                btn.onclick = () => selectDistrict(d.name, d.postalCode);
                listEl.appendChild(btn);
            });
        } else if (step === 'subdistrict') {
            const curProv = INDONESIA_REGIONS.find(p => p.province === state.province) || INDONESIA_REGIONS[0];
            const curCity = (curProv.cities || []).find(c => c.name === state.city) || (curProv.cities ? curProv.cities[0] : null);
            const curDist = (curCity?.districts || []).find(d => d.name === state.district) || (curCity?.districts ? curCity.districts[0] : null);
            const subs = curDist?.subDistricts || [state.district];

            subs.forEach(s => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `w-full p-3.5 text-left flex items-center justify-between hover:bg-[#F8E8EA]/40 transition ${state.subdistrict === s ? 'bg-[#FAF8F5] font-bold text-[#B85D66]' : 'text-[#2A2626]'}`;
                btn.innerHTML = `
                    <div>
                        <span class="font-medium">Kel. ${s}</span>
                        <span class="text-[10px] text-[#9B9494] ml-2">${curDist?.postalCode || ''}</span>
                    </div>
                    <span class="text-xs text-[#B85D66] font-bold">Pilih ✓</span>
                `;
                btn.onclick = () => selectSubDistrict(s, curDist?.postalCode || '');
                listEl.appendChild(btn);
            });

            // Manual input fallback
            const customDiv = document.createElement('div');
            customDiv.className = 'p-4 bg-[#FAF8F5] border-t border-[#EADFD4] mt-2 space-y-2';
            customDiv.innerHTML = `
                <span class="text-[11px] font-semibold text-[#2A2626] block">Kelurahan Anda tidak ada di daftar?</span>
                <div class="flex gap-2">
                    <input type="text" id="customSubDistrictInput" placeholder="Ketik nama kelurahan/desa..." class="flex-1 p-2 rounded-lg border border-[#D8BABF] text-xs bg-white focus:outline-none">
                    <button type="button" onclick="submitCustomSubDistrict('${curDist?.postalCode || ''}')" class="px-3 py-2 bg-[#B85D66] text-white rounded-lg text-xs font-semibold hover:bg-[#8F3B44]">Simpan</button>
                </div>
            `;
            listEl.appendChild(customDiv);
        }
    }

    function submitCustomSubDistrict(defaultPostal) {
        const inp = document.getElementById('customSubDistrictInput');
        const val = inp ? inp.value.trim() : '';
        if (!val) {
            alert('Silakan ketik nama kelurahan atau desa Anda.');
            return;
        }
        selectSubDistrict(val, defaultPostal);
    }

    function selectProvince(prov) {
        state.province = prov;
        const pObj = INDONESIA_REGIONS.find(p => p.province === prov);
        if (pObj && pObj.cities && pObj.cities.length > 0) {
            state.city = pObj.cities[0].name;
            const cObj = pObj.cities[0];
            if (cObj.districts && cObj.districts.length > 0) {
                state.district = cObj.districts[0].name;
                state.subdistrict = (cObj.districts[0].subDistricts && cObj.districts[0].subDistricts[0]) ? cObj.districts[0].subDistricts[0] : cObj.districts[0].name;
                state.postalCode = cObj.districts[0].postalCode || '';
            }
        }
        switchModalStep('city');
    }

    function selectCity(city) {
        state.city = city;
        const pObj = INDONESIA_REGIONS.find(p => p.province === state.province);
        const cObj = pObj ? pObj.cities.find(c => c.name === city) : null;
        if (cObj && cObj.districts && cObj.districts.length > 0) {
            state.district = cObj.districts[0].name;
            state.subdistrict = (cObj.districts[0].subDistricts && cObj.districts[0].subDistricts[0]) ? cObj.districts[0].subDistricts[0] : cObj.districts[0].name;
            state.postalCode = cObj.districts[0].postalCode || '';
        }
        switchModalStep('district');
    }

    function selectDistrict(dist, postal) {
        state.district = dist;
        state.postalCode = postal || '';
        const pObj = INDONESIA_REGIONS.find(p => p.province === state.province);
        const cObj = pObj ? pObj.cities.find(c => c.name === state.city) : null;
        const dObj = cObj ? cObj.districts.find(d => d.name === dist) : null;
        state.subdistrict = (dObj && dObj.subDistricts && dObj.subDistricts[0]) ? dObj.subDistricts[0] : dist;
        switchModalStep('subdistrict');
    }

    function selectSubDistrict(sub, postal) {
        state.subdistrict = sub;
        if (postal) state.postalCode = postal;
        applySelectedRegion();
        closeRegionModal();
    }

    // Live Instant Search across all regions
    function onRegionSearch(query) {
        const box = document.getElementById('searchResultsBox');
        if (!box) return;
        if (!query || query.trim().length < 2) {
            box.classList.add('hidden');
            box.innerHTML = '';
            return;
        }

        const q = query.toLowerCase().trim();
        const matches = [];

        INDONESIA_REGIONS.forEach(p => {
            (p.cities || []).forEach(c => {
                (c.districts || []).forEach(d => {
                    const matchDist = d.name.toLowerCase().includes(q);
                    const matchCity = c.name.toLowerCase().includes(q);
                    const matchProv = p.province.toLowerCase().includes(q);
                    const matchPostal = d.postalCode && d.postalCode.includes(q);
                    const matchSub = d.subDistricts && d.subDistricts.some(s => s.toLowerCase().includes(q));

                    if (matchDist || matchCity || matchProv || matchPostal || matchSub) {
                        const matchedSubName = (matchSub && d.subDistricts.find(s => s.toLowerCase().includes(q))) || (d.subDistricts ? d.subDistricts[0] : d.name);
                        matches.push({
                            province: p.province,
                            city: c.name,
                            district: d.name,
                            subdistrict: matchedSubName,
                            postalCode: d.postalCode || ''
                        });
                    }
                });
            });
        });

        if (matches.length === 0) {
            box.innerHTML = '<div class="p-3 text-[11px] text-[#9B9494] text-center">Tidak ditemukan wilayah yang sesuai. Coba ketik nama kota atau kecamatan.</div>';
            box.classList.remove('hidden');
            return;
        }

        box.innerHTML = '';
        matches.slice(0, 15).forEach(m => {
            const item = document.createElement('div');
            item.className = 'p-2.5 hover:bg-[#F8E8EA]/40 cursor-pointer flex items-center justify-between text-xs transition border-b border-[#F3ECE4] last:border-0';
            item.innerHTML = `
                <div>
                    <span class="font-bold text-[#2A2626] block">Kec. ${m.district}, ${m.city}</span>
                    <span class="text-[10px] text-[#6B6565]">${m.province} • Kel. ${m.subdistrict} • Kode Pos: ${m.postalCode}</span>
                </div>
                <span class="text-xs text-[#B85D66] font-bold">Pilih ›</span>
            `;
            item.onclick = () => {
                state.province = m.province;
                state.city = m.city;
                state.district = m.district;
                state.subdistrict = m.subdistrict;
                state.postalCode = m.postalCode;
                applySelectedRegion();
                closeRegionModal();
            };
            box.appendChild(item);
        });

        box.classList.remove('hidden');
    }

    // Apply & Synchronize Selected Region
    function applySelectedRegion() {
        // Update hidden inputs
        if (document.getElementById('selected_province')) document.getElementById('selected_province').value = state.province;
        if (document.getElementById('selected_city')) document.getElementById('selected_city').value = state.city;
        if (document.getElementById('selected_district')) document.getElementById('selected_district').value = state.district;
        if (document.getElementById('selected_subdistrict')) document.getElementById('selected_subdistrict').value = state.subdistrict;
        if (document.getElementById('selected_postal_code')) document.getElementById('selected_postal_code').value = state.postalCode;

        // Update trigger display text
        const displayLabel = `${state.province}, ${state.city}, Kec. ${state.district}, Kel. ${state.subdistrict}` + (state.postalCode ? ` (${state.postalCode})` : '');
        const regionTextEl = document.getElementById('selectedRegionText');
        if (regionTextEl) {
            regionTextEl.innerHTML = `<span class="font-bold text-[#2A2626]">${displayLabel}</span>`;
        }
        const regionActionText = document.getElementById('regionActionText');
        if (regionActionText) {
            regionActionText.textContent = 'Ubah';
        }

        // Sync inline selects & combined full address
        syncInlineSelects();
        syncCombinedAddress();

        // Update routing map destination to selected district & city
        syncMapToDistrict(state.district, state.city, state.province);

        // Auto select best Delivery Zone in Section 2
        autoSelectDeliveryZone(state.district, state.city);
    }

    function syncInlineSelects() {
        // Optional placeholder for compatibility with existing inline UI components
    }

    function syncCombinedAddress() {
        const street = document.getElementById('street_address')?.value?.trim() || '';
        const previewEl = document.getElementById('combinedAddressPreview');
        const hiddenAddressEl = document.getElementById('delivery_address');

        const regionPart = `${state.subdistrict ? 'Kel. ' + state.subdistrict + ', ' : ''}Kec. ${state.district || ''}, ${state.city || ''}, ${state.province || ''} ${state.postalCode || ''}`.trim();
        const fullAddress = street ? `${street}, ${regionPart}` : regionPart;

        if (previewEl) {
            previewEl.textContent = fullAddress || 'Belum ada alamat yang dipilih.';
        }
        if (hiddenAddressEl) {
            hiddenAddressEl.value = fullAddress;
        }
    }

    // Extensive Coordinates Database covering Districts, Regencies/Cities, and Provinces
    const KNOWN_REGION_COORDS = {
        // --- 31 Surabaya Districts ---
        'Tegalsari': [-7.2721, 112.7380],
        'Genteng': [-7.2604, 112.7483],
        'Bubutan': [-7.2519, 112.7320],
        'Simokerto': [-7.2405, 112.7538],
        'Gubeng': [-7.2764, 112.7548],
        'Gunung Anyar': [-7.3361, 112.7936],
        'Sukolilo': [-7.2882, 112.7871],
        'Tambaksari': [-7.2529, 112.7681],
        'Mulyorejo': [-7.2683, 112.7936],
        'Rungkut': [-7.3218, 112.7758],
        'Tenggilis Mejoyo': [-7.3197, 112.7525],
        'Benowo': [-7.2343, 112.6373],
        'Pakal': [-7.2335, 112.6087],
        'Asemrowo': [-7.2465, 112.7042],
        'Sukomanunggal': [-7.2736, 112.7051],
        'Tandes': [-7.2612, 112.6738],
        'Sambikerep': [-7.2785, 112.6517],
        'Lakarsantri': [-7.3134, 112.6508],
        'Bulak': [-7.2376, 112.7951],
        'Kenjeran': [-7.2330, 112.7750],
        'Semampir': [-7.2215, 112.7523],
        'Pabean Cantian': [-7.2323, 112.7371],
        'Krembangan': [-7.2378, 112.7242],
        'Wonokromo': [-7.2974, 112.7351],
        'Wonocolo': [-7.3184, 112.7352],
        'Wiyung': [-7.3061, 112.6936],
        'Karang Pilang': [-7.3375, 112.7032],
        'Jambangan': [-7.3204, 112.7161],
        'Gayungan': [-7.3308, 112.7275],
        'Dukuh Pakis': [-7.2891, 112.7054],
        'Sawahan': [-7.2763, 112.7214],

        // --- Sidoarjo Districts ---
        'Waru': [-7.3541, 112.7483],
        'Sedati': [-7.3789, 112.7738],
        'Gedangan': [-7.3821, 112.7289],
        'Taman': [-7.3512, 112.6934],
        'Sukodono': [-7.4042, 112.6845],
        'Sidoarjo Kota': [-7.4478, 112.7183],
        'Candi': [-7.4812, 112.7167],
        'Buduran': [-7.4245, 112.7321],
        'Krian': [-7.4112, 112.5845],
        'Porong': [-7.5456, 112.6989],
        'Tulangan': [-7.4789, 112.6512],
        'Wonoayu': [-7.4432, 112.6212],
        'Prambon': [-7.4912, 112.5734],
        'Krembung': [-7.5234, 112.6212],
        'Jabon': [-7.5612, 112.7534],
        'Tarik': [-7.4321, 112.5123],
        'Balongbendo': [-7.4012, 112.5234],

        // --- Gresik Districts ---
        'Menganti': [-7.2734, 112.5834],
        'Driyorejo': [-7.3356, 112.6123],
        'Kebomas': [-7.1723, 112.6234],
        'Gresik Kota': [-7.1589, 112.6545],
        'Manyar': [-7.1234, 112.6012],
        'Cerme': [-7.2189, 112.5534],
        'Benjeng': [-7.2345, 112.4823],
        'Duduksampeyan': [-7.1678, 112.5123],
        'Kedamean': [-7.3123, 112.5345],
        'Bungah': [-7.0456, 112.5678],
        'Sidayu': [-6.9912, 112.5612],
        'Ujungpangkah': [-6.9123, 112.5456],
        'Panceng': [-6.9123, 112.4612],
        'Dukun': [-7.0123, 112.4823],
        'Wringinanom': [-7.3712, 112.5234],

        // --- Regencies & Cities in East Java ---
        'Kota Surabaya': [-7.2575, 112.7521],
        'Kab. Sidoarjo': [-7.4478, 112.7183],
        'Kab. Gresik': [-7.1589, 112.6545],
        'Kota Mojokerto': [-7.4726, 112.4381],
        'Kab. Mojokerto': [-7.5512, 112.4923],
        'Kota Pasuruan': [-7.6453, 112.9075],
        'Kab. Pasuruan': [-7.7123, 112.8234],
        'Kota Malang': [-7.9666, 112.6326],
        'Kab. Malang': [-8.1723, 112.6212],
        'Kota Batu': [-7.8712, 112.5273],
        'Kab. Jombang': [-7.5461, 112.2331],
        'Kab. Lamongan': [-7.1197, 112.4132],
        'Kab. Bangkalan': [-7.0455, 112.7386],
        'Kab. Sampang': [-7.1872, 113.2394],
        'Kab. Pamekasan': [-7.1611, 113.4739],
        'Kab. Sumenep': [-7.0167, 113.8667],
        'Kota Kediri': [-7.8480, 112.0178],
        'Kab. Kediri': [-7.7812, 112.1234],
        'Kota Blitar': [-8.0983, 112.1681],
        'Kab. Blitar': [-8.1345, 112.2212],
        'Kota Probolinggo': [-7.7569, 113.2115],
        'Kab. Probolinggo': [-7.8512, 113.3123],
        'Kota Madiun': [-7.6298, 111.5239],
        'Kab. Madiun': [-7.5512, 111.6234],
        'Kab. Banyuwangi': [-8.2192, 114.3691],
        'Kab. Jember': [-8.1724, 113.7007],
        'Kab. Tuban': [-6.8972, 112.0649],
        'Kab. Bojonegoro': [-7.1502, 111.8817],
        'Kab. Ngawi': [-7.4039, 111.4455],
        'Kab. Magetan': [-7.6528, 111.3283],
        'Kab. Ponorogo': [-7.8686, 111.4621],
        'Kab. Pacitan': [-8.2064, 111.0921],
        'Kab. Trenggalek': [-8.0506, 111.7083],
        'Kab. Tulungagung': [-8.0653, 111.9015],
        'Kab. Nganjuk': [-7.6047, 111.9044],
        'Kab. Lumajang': [-8.1331, 113.2246],
        'Kab. Bondowoso': [-7.9135, 113.8214],
        'Kab. Situbondo': [-7.7061, 114.0049],

        // --- Major Indonesian Cities ---
        'Kota Jakarta Pusat': [-6.1805, 106.8284],
        'Kota Jakarta Selatan': [-6.2615, 106.8106],
        'Kota Jakarta Barat': [-6.1683, 106.7589],
        'Kota Jakarta Timur': [-6.2250, 106.9004],
        'Kota Jakarta Utara': [-6.1384, 106.8642],
        'Kota Bandung': [-6.9175, 107.6191],
        'Kota Semarang': [-6.9667, 110.4167],
        'Kota Yogyakarta': [-7.7956, 110.3695],
        'Kota Surakarta': [-7.5755, 110.8243],
        'Kota Denpasar': [-8.6705, 115.2126],

        // --- Provinces ---
        'Jawa Timur': [-7.5361, 112.2384],
        'DKI Jakarta': [-6.2088, 106.8456],
        'Jawa Barat': [-6.9175, 107.6191],
        'Jawa Tengah': [-7.1510, 110.1403],
        'DI Yogyakarta': [-7.7956, 110.3695],
        'Banten': [-6.4058, 106.0640],
        'Bali': [-8.3405, 115.0920]
    };

    function resolveRegionCoordinates(district, city, province) {
        const clean = str => (str || '').replace(/^(Kec\.|Kel\.|Kab\.|Kota)\s+/i, '').trim();
        const dClean = clean(district);
        const cClean = clean(city);
        const pClean = clean(province);

        // 1. Direct District match
        if (district && KNOWN_REGION_COORDS[district]) return KNOWN_REGION_COORDS[district];
        if (dClean && KNOWN_REGION_COORDS[dClean]) return KNOWN_REGION_COORDS[dClean];

        // 2. City match
        if (city && KNOWN_REGION_COORDS[city]) return KNOWN_REGION_COORDS[city];
        if (cClean && KNOWN_REGION_COORDS[cClean]) return KNOWN_REGION_COORDS[cClean];
        if (cClean && KNOWN_REGION_COORDS['Kota ' + cClean]) return KNOWN_REGION_COORDS['Kota ' + cClean];
        if (cClean && KNOWN_REGION_COORDS['Kab. ' + cClean]) return KNOWN_REGION_COORDS['Kab. ' + cClean];

        // 3. Province match
        if (province && KNOWN_REGION_COORDS[province]) return KNOWN_REGION_COORDS[province];
        if (pClean && KNOWN_REGION_COORDS[pClean]) return KNOWN_REGION_COORDS[pClean];

        // 4. Default to Bubutan / Surabaya instead of store point so it never collapses to 0 KM
        return [-7.2519, 112.7320];
    }

    function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth radius in KM
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // Leaflet Routing & Map
    let routeMap = null;
    let storeMarker = null;
    let recipientMarker = null;
    let routePolyline = null;
    let geocodeAbortController = null;

    function initMap() {
        const mapContainer = document.getElementById('checkoutRouteMap');
        if (!mapContainer || typeof L === 'undefined') return;

        let initLat = parseFloat(document.getElementById('recipient_latitude')?.value);
        let initLng = parseFloat(document.getElementById('recipient_longitude')?.value);

        if (isNaN(initLat) || isNaN(initLng)) {
            const defCoords = resolveRegionCoordinates(state.district, state.city, state.province);
            initLat = defCoords[0];
            initLng = defCoords[1];
        }

        try {
            const mapRenderer = L.svg({ padding: 1.5 });
            routeMap = L.map('checkoutRouteMap', { zoomControl: true, renderer: mapRenderer }).setView([initLat, initLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(routeMap);

            // Store Marker
            const storeIcon = L.divIcon({
                className: 'custom-map-pin',
                html: '<div class="w-8 h-8 rounded-full bg-[#B85D66] text-white flex items-center justify-center font-bold text-xs shadow-lg border-2 border-white">🌸</div>',
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });
            storeMarker = L.marker([STORE_LAT, STORE_LNG], { icon: storeIcon }).addTo(routeMap);
            storeMarker.bindPopup(`<b>${STORE_NAME}</b><br>Titik Awal Pengiriman (${STORE_DISTRICT})`);

            // Recipient Marker (Draggable)
            const recipientIcon = L.divIcon({
                className: 'custom-recipient-pin',
                html: '<div class="w-8 h-8 rounded-full bg-[#2A2626] text-white flex items-center justify-center font-bold text-xs shadow-lg border-2 border-white">📍</div>',
                iconSize: [32, 32],
                iconAnchor: [16, 30]
            });
            recipientMarker = L.marker([initLat, initLng], { icon: recipientIcon, draggable: true }).addTo(routeMap);
            recipientMarker.bindPopup('<b>Lokasi Pengantaran Anda</b><br>Geser pin untuk penyesuaian titik').openPopup();

            // Drag event
            recipientMarker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                setRecipientMarkerPosition(pos.lat, pos.lng, true);
            });

            // Double click to relocate pin if user prefers clicking over dragging
            routeMap.on('dblclick', function(e) {
                setRecipientMarkerPosition(e.latlng.lat, e.latlng.lng, true);
            });

            updateMapRoutePolyline();

            setTimeout(() => {
                if (routeMap) {
                    routeMap.invalidateSize();
                    updateMapRoutePolyline();
                }
            }, 200);

            setTimeout(() => {
                if (routeMap) {
                    routeMap.invalidateSize();
                }
            }, 600);
        } catch (err) {
            console.error('Error initializing map:', err);
        }
    }

    function setRecipientMarkerPosition(lat, lng, isUserAction = false) {
        if (!recipientMarker || !routeMap) return;

        const fixedLat = parseFloat(lat).toFixed(7);
        const fixedLng = parseFloat(lng).toFixed(7);

        recipientMarker.setLatLng([fixedLat, fixedLng]);

        const latInput = document.getElementById('recipient_latitude');
        const lngInput = document.getElementById('recipient_longitude');
        if (latInput) latInput.value = fixedLat;
        if (lngInput) lngInput.value = fixedLng;

        const pinStatus = document.getElementById('mapPinStatus');
        if (pinStatus) {
            pinStatus.textContent = isUserAction ? '✓ Titik Lokasi Disesuaikan' : '✓ Geser Pin untuk Sesuaikan Titik Rumah';
        }

        const locText = state.district ? `Kec. ${state.district}, ${state.city || ''}` : (state.city || 'Lokasi Penerima');
        recipientMarker.bindPopup(`<b>Lokasi Penerima</b><br>${locText}<br><span style="font-size:10px; color:#666;">Geser pin atau klik peta untuk sesuaikan titik</span>`);

        updateMapRoutePolyline(!isUserAction);
        autoSelectDeliveryZone(state.district, state.city);
    }

    let routeFetchAbortController = null;
    let lastFetchedRouteTarget = null;
    let routeFetchTimeout = null;

    function updateMapRoutePolyline(shouldFitBounds = false) {
        if (!routeMap || !recipientMarker || !storeMarker) return;
        const rPos = recipientMarker.getLatLng();
        const sPos = storeMarker.getLatLng();

        // 1. Initialize polyline layer ONCE if not existing yet
        if (!routePolyline) {
            routePolyline = L.polyline([sPos, rPos], {
                color: '#B85D66',
                weight: 5,
                opacity: 0.85,
                lineCap: 'round',
                lineJoin: 'round',
                noClip: true,
                smoothFactor: 1.0
            }).addTo(routeMap);
        }

        // Only adjust map bounds when explicitly requested (e.g. initial load / region change), NOT on user drag/pan
        if (shouldFitBounds) {
            const straightDist = calculateHaversineDistance(sPos.lat, sPos.lng, rPos.lat, rPos.lng);
            if (straightDist < 0.1) {
                routeMap.setView([rPos.lat, rPos.lng], 15);
            } else {
                const group = new L.featureGroup([storeMarker, recipientMarker]);
                routeMap.fitBounds(group.getBounds().pad(0.2), { maxZoom: 16 });
            }
        }

        // Check if recipient position actually changed enough to warrant new OSRM network request
        if (lastFetchedRouteTarget) {
            const distChanged = Math.hypot(lastFetchedRouteTarget.lat - rPos.lat, lastFetchedRouteTarget.lng - rPos.lng);
            if (distChanged < 0.00005) {
                return; // Position virtually unchanged, keep existing polyline intact!
            }
        }

        // 2. Debounce OSRM Fetch to prevent spamming while panning/dragging
        if (routeFetchTimeout) clearTimeout(routeFetchTimeout);

        routeFetchTimeout = setTimeout(() => {
            if (routeFetchAbortController) {
                routeFetchAbortController.abort();
            }
            routeFetchAbortController = new AbortController();

            const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${sPos.lng},${sPos.lat};${rPos.lng},${rPos.lat}?overview=full&geometries=geojson`;

            fetch(osrmUrl, { signal: routeFetchAbortController.signal })
                .then(res => res.json())
                .then(data => {
                    if (data && data.routes && data.routes.length > 0) {
                        const coords = data.routes[0].geometry.coordinates;
                        const roadPoints = coords.map(c => [c[1], c[0]]);
                        if (routePolyline && routeMap) {
                            routePolyline.setLatLngs(roadPoints);
                            routePolyline.setStyle({ color: '#B85D66', weight: 5, dashArray: null, opacity: 0.9 });
                        }
                        lastFetchedRouteTarget = { lat: rPos.lat, lng: rPos.lng };

                        // Update real road distance
                        const roadDistanceKm = (data.routes[0].distance / 1000).toFixed(1);
                        const distDisplay = document.getElementById('distanceDisplay');
                        if (distDisplay) {
                            distDisplay.textContent = `± ${roadDistanceKm} KM (Rute Jalan Utama)`;
                        }
                    }
                })
                .catch(err => {
                    // Do NOT reset or delete polyline on abort or error to prevent flickering!
                });
        }, 200);
    }

    function syncMapToDistrict(districtName, cityName, provinceName) {
        if (!routeMap || !recipientMarker) return;

        const dName = districtName || state.district;
        const cName = cityName || state.city;
        const pName = provinceName || state.province;

        // 1. Immediate resolution from extensive region coordinate database
        const coords = resolveRegionCoordinates(dName, cName, pName);
        setRecipientMarkerPosition(coords[0], coords[1], false);

        // 2. Asynchronous live geocode via OpenStreetMap for high accuracy
        triggerLiveGeocode(dName, cName, pName);
    }

    function triggerLiveGeocode(district, city, province) {
        if (!district && !city) return;

        if (geocodeAbortController) {
            geocodeAbortController.abort();
        }
        geocodeAbortController = new AbortController();

        const queryParts = [];
        if (state.subdistrict) queryParts.push(state.subdistrict);
        if (district) queryParts.push(district);
        if (city) queryParts.push(city);
        if (province) queryParts.push(province);
        queryParts.push('Indonesia');

        const query = queryParts.join(', ');

        const pinStatus = document.getElementById('mapPinStatus');
        if (pinStatus) pinStatus.textContent = '⏳ Menyinkronkan lokasi peta...';

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`, {
            signal: geocodeAbortController.signal,
            headers: { 'Accept-Language': 'id' }
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.length > 0) {
                const geoLat = parseFloat(data[0].lat);
                const geoLng = parseFloat(data[0].lon);
                setRecipientMarkerPosition(geoLat, geoLng, false);
            }
            if (pinStatus) pinStatus.textContent = '✓ Geser Pin untuk Sesuaikan Titik Rumah';
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                if (pinStatus) pinStatus.textContent = '✓ Geser Pin untuk Sesuaikan Titik Rumah';
            }
        });
    }

    function detectGpsLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                setRecipientMarkerPosition(pos.coords.latitude, pos.coords.longitude, true);
            }, function() {
                alert('Tidak dapat mendeteksi lokasi GPS Anda. Pastikan izin lokasi aktif.');
            });
        } else {
            alert('Browser Anda tidak mendukung deteksi lokasi.');
        }
    }

    function resetPinToSelectedDistrict() {
        syncMapToDistrict(state.district, state.city, state.province);
    }

    // Auto-match delivery zone & time slots based on chosen district / coordinates
    function autoSelectDeliveryZone(districtName, cityName) {
        if (!DELIVERY_ZONES || DELIVERY_ZONES.length === 0) return;

        const dName = (districtName || '').trim();
        const cName = (cityName || '').toLowerCase().trim();

        const isSurabaya = cName.includes('surabaya');
        const isDistrictEnabled = !isSurabaya || (ENABLED_DISTRICTS && ENABLED_DISTRICTS.length === 0) || ENABLED_DISTRICTS.some(d => d.toLowerCase() === dName.toLowerCase());

        const alertBox = document.getElementById('unservicedAlertBox');
        const submitBtn = document.getElementById('submitOrderBtn');
        const feeSummaryEl = document.getElementById('activeZoneFee');
        const zoneNameEl = document.getElementById('activeZoneName');
        const zoneDescEl = document.getElementById('activeZoneDescription');

        const curLat = parseFloat(document.getElementById('recipient_latitude')?.value) || -7.2519;
        const curLng = parseFloat(document.getElementById('recipient_longitude')?.value) || 112.7320;

        const straightDist = calculateHaversineDistance(STORE_LAT, STORE_LNG, curLat, curLng);
        const roadDistanceKm = parseFloat((straightDist * 1.32).toFixed(1));
        const estDurationMinutes = Math.max(15, Math.round(roadDistanceKm * 2.6 + 8));

        if (document.getElementById('distance_km')) document.getElementById('distance_km').value = roadDistanceKm;
        const distEl = document.getElementById('routeDistanceText');
        const durEl = document.getElementById('routeDurationText');
        if (distEl) distEl.textContent = `~${roadDistanceKm} KM`;
        if (durEl) durEl.textContent = `~${estDurationMinutes - 5} - ${estDurationMinutes + 10} Menit`;

        if (!isDistrictEnabled || roadDistanceKm > MAX_SERVICE_DISTANCE) {
            if (alertBox) {
                alertBox.classList.remove('hidden');
                const titleEl = document.getElementById('unservicedTitle');
                if (titleEl) {
                    titleEl.textContent = !isDistrictEnabled 
                        ? `Kecamatan ${dName} Belum Masuk Area Operasional` 
                        : `Jarak Pengiriman (${roadDistanceKm} KM) di Luar Jangkauan Toko`;
                }
                const descEl = document.getElementById('unservicedDesc');
                if (descEl) {
                    descEl.textContent = !isDistrictEnabled
                        ? `Mohon maaf, operasional pengiriman Desy Florist saat ini belum mencakup Kecamatan ${dName}. Admin dapat mengaktifkan wilayah ini melalui dashboard.`
                        : `${UNSERVICED_MESSAGE} (Radius maksimal layanan saat ini adalah ${MAX_SERVICE_DISTANCE} KM dari toko di ${STORE_DISTRICT}).`;
                }
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-[#B85D66]', 'hover:bg-[#8F3B44]');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                submitBtn.textContent = 'Alamat di Luar Area Layanan Florist';
            }

            if (zoneNameEl) zoneNameEl.textContent = 'Di Luar Area Jangkauan Layanan';
            if (zoneDescEl) zoneDescEl.textContent = 'Alamat belum dapat dilayani untuk pengiriman langsung toko saat ini.';
            if (feeSummaryEl) feeSummaryEl.textContent = 'Tidak Tersedia';

            const shippingLabel = document.getElementById('shippingFeeLabel');
            if (shippingLabel) shippingLabel.innerHTML = '<span class="text-red-600 font-bold">Tidak Tersedia</span>';
            return;
        }

        if (alertBox) alertBox.classList.add('hidden');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-[#B85D66]', 'hover:bg-[#8F3B44]');
            submitBtn.textContent = 'Konfirmasi & Buat Pesanan';
        }

        let matchedZone = DELIVERY_ZONES.find(z => {
            const minKm = parseFloat(z.min_distance_km) || 0;
            const maxKm = parseFloat(z.max_distance_km) || 50;
            return roadDistanceKm >= minKm && roadDistanceKm <= maxKm;
        });

        if (!matchedZone) {
            matchedZone = DELIVERY_ZONES.find(z => {
                if (!z.areas || !Array.isArray(z.areas)) return false;
                return z.areas.some(a => a.toLowerCase().includes(dName.toLowerCase()));
            }) || DELIVERY_ZONES[0];
        }

        if (matchedZone) {
            applyZoneSelection(matchedZone, dName, cityName, roadDistanceKm, estDurationMinutes);
        }
    }

    function applyZoneSelection(zone, districtName, cityName, distanceKm, durationMinutes) {
        if (!zone) return;

        const targetRadio = document.querySelector(`input[name="delivery_zone_id"][value="${zone.id}"]`);
        if (targetRadio) {
            targetRadio.checked = true;
        }

        const nameEl = document.getElementById('activeZoneName');
        const descEl = document.getElementById('activeZoneDescription');
        const feeEl = document.getElementById('activeZoneFee');
        const indEl = document.getElementById('matchedAreaIndicator');

        if (nameEl) nameEl.textContent = zone.name;
        if (descEl) {
            const locText = (districtName && cityName) ? `Kec. ${districtName}, ${cityName}` : (cityName || 'Wilayah Pengiriman Anda');
            const distInfo = distanceKm ? ` (Jarak: ${distanceKm} KM • Estimasi: ~${durationMinutes} menit)` : '';
            descEl.textContent = `Area pengantaran dan kurir aktif untuk ${locText}${distInfo}. Dihitung dari toko di ${STORE_DISTRICT}.`;
        }
        if (feeEl) {
            feeEl.textContent = 'Rp ' + Number(zone.delivery_fee).toLocaleString('id-ID');
        }
        if (indEl) {
            indEl.textContent = `✓ Terhubung Otomatis (${zone.name.split(':')[0]})`;
        }

        updateZoneCardsVisibility(zone, cityName);
        updateDeliverySlots(zone, districtName, cityName);
        updateDeliveryFee();
    }

    function updateZoneCardsVisibility(selectedZone, cityName) {
        const cards = document.querySelectorAll('.zone-card');
        cards.forEach(card => {
            const isMatch = card.dataset.zoneId == selectedZone.id;
            const badge = card.querySelector(`[id^="zoneMatchBadge-"]`);
            if (badge) {
                if (isMatch) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        });
    }

    function updateDeliverySlots(zone, districtName, cityName) {
        const slotSelect = document.getElementById('delivery_slot');
        if (!slotSelect) return;

        let rawSlots = zone?.available_slots;
        let slots = [];

        if (typeof rawSlots === 'string') {
            try {
                slots = JSON.parse(rawSlots);
            } catch (e) {
                slots = [];
            }
        } else if (Array.isArray(rawSlots)) {
            slots = rawSlots;
        }

        if (!slots || slots.length === 0) {
            slots = [
                '09:00 - 12:00 (Pagi)',
                '12:00 - 15:00 (Siang)',
                '15:00 - 18:00 (Sore)',
                '18:00 - 21:00 (Malam)'
            ];
        }

        let currentVal = slotSelect.value;
        let optionsHtml = '';
        slots.forEach(slot => {
            const isSelected = (currentVal && currentVal === slot) ? 'selected' : '';
            optionsHtml += `<option value="${slot}" ${isSelected}>${slot}</option>`;
        });

        slotSelect.innerHTML = optionsHtml;
    }

    function updateDeliveryFee() {
        const checkedRadio = document.querySelector('input[name="delivery_zone_id"]:checked');
        const activeZoneFeeEl = document.getElementById('activeZoneFee');
        const shippingFeeLabel = document.getElementById('shippingFeeLabel');
        const grandTotalLabel = document.getElementById('grandTotalLabel');

        let deliveryFee = 0;
        if (checkedRadio) {
            deliveryFee = parseFloat(checkedRadio.dataset.fee) || 0;
        } else if (activeZoneFeeEl) {
            const feeText = activeZoneFeeEl.textContent.replace(/[^\d]/g, '');
            deliveryFee = parseInt(feeText) || 0;
        }

        // Check for Free Shipping
        const isFreeShipping = (freeShippingMin > 0 && subtotal >= freeShippingMin);
        const finalDeliveryFee = isFreeShipping ? 0 : deliveryFee;

        if (shippingFeeLabel) {
            if (isFreeShipping) {
                shippingFeeLabel.innerHTML = '<span class="text-[#5C6A57] font-bold">GRATIS (Promo)</span>';
            } else {
                shippingFeeLabel.textContent = 'Rp ' + finalDeliveryFee.toLocaleString('id-ID');
            }
        }

        if (grandTotalLabel) {
            const total = Math.max(0, (subtotal - discount) + finalDeliveryFee);
            grandTotalLabel.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
    }

    function onManualZoneChange(zoneId) {
        const zone = DELIVERY_ZONES.find(z => z.id == zoneId);
        if (zone) {
            applyZoneSelection(zone, state.district, state.city, document.getElementById('distance_km')?.value, 20);
        }
    }

    function toggleAllZones() {
        const grid = document.getElementById('deliveryZonesGrid');
        if (grid) {
            grid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Initialize everything on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
        syncCombinedAddress();
        applySelectedRegion();

        const streetInput = document.getElementById('street_address');
        if (streetInput) {
            streetInput.addEventListener('input', syncCombinedAddress);
        }
    });
</script>

@endsection
