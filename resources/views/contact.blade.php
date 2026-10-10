@extends('layouts.app')

@section('title', 'Kontak Studio — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-12 border-b border-[#EADFD4]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-3">
        <span class="text-xs uppercase tracking-widest text-[#B85D66] font-semibold">Hubungi Kami</span>
        <h1 class="font-serif text-4xl sm:text-5xl text-[#2A2626] font-normal">Kontak Desy Florist</h1>
        <p class="text-xs sm:text-sm text-[#6B6565] font-light max-w-lg mx-auto leading-relaxed">
            Punya pertanyaan mengenai pesanan khusus, dekorasi acara, atau konsultasi buket? Tim kami siap melayani Anda dengan hangat.
        </p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
        
        <!-- Left: Studio Details -->
        <div class="bg-white p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6">
            <h3 class="font-serif text-2xl text-[#2A2626]">Studio Kami</h3>
            
            <div class="space-y-5 text-xs text-[#6B6565]">
                <div class="flex items-start gap-3">
                    <span class="text-base text-[#B85D66]">📍</span>
                    <div class="flex-1">
                        <strong class="text-[#2A2626] block">Alamat Florist Studio:</strong>
                        <a href="{{ $storeMapsUrl ?? 'https://www.google.com/maps?q=-7.2654,112.7483' }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="group block mt-1 hover:text-[#B85D66] transition cursor-pointer"
                           title="Klik untuk membuka rute dan lokasi di Google Maps">
                            <span class="leading-relaxed underline decoration-dotted underline-offset-4 group-hover:decoration-solid group-hover:text-[#B85D66] block">
                                {{ $storeSettings['store_address'] ?? 'Jl. Kayoon No. 42 (Sentra Bunga Surabaya Pusat), Kec. Genteng, Surabaya, Jawa Timur 60271' }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#B85D66] mt-1.5 group-hover:underline">
                                <span>Buka di Google Maps</span> ↗
                            </span>
                        </a>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="text-base text-[#B85D66]">🕒</span>
                    <div>
                        <strong class="text-[#2A2626] block">Jam Operasional:</strong>
                        <p class="mt-0.5">{{ $storeSettings['store_open_hours'] ?? (!empty($storeSettings['operating_hours']) ? 'Buka ' . ($storeSettings['operating_days'] ?? 'Setiap Hari') . ': ' . $storeSettings['operating_hours'] : 'Senin - Minggu: 08:00 - 20:00 WIB') }}</p>
                        <span class="text-[10px] text-[#5C6A57] font-semibold block mt-0.5">Batas order same-day: Pukul {{ $storeSettings['same_day_cutoff'] ?? '16:00' }} WIB</span>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="text-base text-[#B85D66]">💬</span>
                    <div>
                        <strong class="text-[#2A2626] block">WhatsApp Fast Response:</strong>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $storeSettings['store_whatsapp'] ?? '6281234567890') }}" target="_blank" rel="noopener noreferrer"
                           class="text-[#5C6A57] font-bold hover:underline block mt-0.5">
                            {{ $storeSettings['store_whatsapp'] ?? '0812-3456-7890' }}
                        </a>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="text-base text-[#B85D66]">✉️</span>
                    <div>
                        <strong class="text-[#2A2626] block">Email Studio:</strong>
                        <a href="mailto:{{ $storeSettings['store_email'] ?? 'halo@desyflorist.com' }}" class="text-[#6B6565] hover:underline block mt-0.5">
                            {{ $storeSettings['store_email'] ?? 'halo@desyflorist.com' }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-[#EADFD4]">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $storeSettings['store_whatsapp'] ?? '6281234567890') }}?text=Halo%20{{ urlencode($storeSettings['store_name'] ?? 'Desy Florist') }},%20saya%20ingin%20tanya%20tentang%20pesanan%20bunga" target="_blank" rel="noopener noreferrer"
                   class="w-full py-3 px-4 rounded-xl bg-[#5C6A57] hover:bg-[#434F3F] text-white text-xs font-semibold tracking-wider uppercase flex items-center justify-center gap-2 transition shadow-xs">
                    <span>Chat Langsung via WhatsApp</span>
                </a>
            </div>
        </div>

        <!-- Right: Lokasi Peta Studio -->
        <div class="bg-white p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-serif text-2xl text-[#2A2626]">Peta Lokasi Studio</h3>
                    <p class="text-xs text-[#6B6565] mt-0.5">Kunjungi workshop atau pickup langsung di lokasi kami.</p>
                </div>
                <span class="w-8 h-8 rounded-full bg-[#B85D66]/10 text-[#B85D66] flex items-center justify-center font-bold text-base">📍</span>
            </div>

            <div id="contactStoreMap" class="w-full h-72 rounded-xl border border-[#D8BABF] overflow-hidden z-10 bg-[#FAF8F5]"></div>

            <div class="pt-2 flex items-center justify-between text-xs">
                <span class="text-xs text-[#6B6565] truncate max-w-[220px]">
                    {{ $storeSettings['store_district'] ?? 'Surabaya Pusat' }}, {{ $storeSettings['store_city'] ?? 'Surabaya' }}
                </span>
                <a href="{{ $storeMapsUrl ?? 'https://www.google.com/maps?q=-7.2654,112.7483' }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-bold transition shadow-xs">
                    <span>Buka di Google Maps</span> ↗
                </a>
            </div>
        </div>

    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const storeLat = parseFloat("{{ $storeSettings['store_latitude'] ?? '-7.2679543' }}") || -7.2654;
        const storeLng = parseFloat("{{ $storeSettings['store_longitude'] ?? '112.7490163' }}") || 112.7483;
        const storeName = @json($storeSettings['store_name'] ?? 'Desy Florist');
        const storeAddress = @json($storeSettings['store_address'] ?? 'Surabaya Pusat, Jawa Timur');
        const mapsUrl = @json($storeMapsUrl ?? 'https://www.google.com/maps?q=-7.2654,112.7483');

        const mapContainer = document.getElementById('contactStoreMap');
        if (!mapContainer) return;

        const map = L.map('contactStoreMap', { scrollWheelZoom: false }).setView([storeLat, storeLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const storeIcon = L.divIcon({
            html: '<div style="background-color:#B85D66; color:white; width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; border:2.5px solid white; box-shadow:0 3px 8px rgba(0,0,0,0.35);">🌸</div>',
            className: '',
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -34]
        });

        const marker = L.marker([storeLat, storeLng], { icon: storeIcon }).addTo(map);
        marker.bindPopup(`<div style="font-family:sans-serif; font-size:11px; line-height:1.4; min-width:180px;">
            <b style="color:#B85D66; font-size:13px; display:block; margin-bottom:2px;">${storeName}</b>
            <span style="color:#555;">${storeAddress}</span>
            <div style="margin-top:6px; padding-top:4px; border-top:1px solid #eee;">
                <a href="${mapsUrl}" target="_blank" rel="noopener noreferrer" style="color:#B85D66; font-weight:bold; text-decoration:underline;">Buka di Google Maps ↗</a>
            </div>
        </div>`).openPopup();
    });
</script>
@endpush
@endsection
