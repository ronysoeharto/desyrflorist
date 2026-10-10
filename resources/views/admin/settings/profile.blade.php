@extends('layouts.admin')

@section('title', 'Profil dan Lokasi Toko — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Profil & Lokasi Toko</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">
                Titik awal pengiriman resmi toko Desy Florist di Surabaya Pusat untuk dasar kalkulasi rute dan ongkos kirim.
            </p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    @include('admin.settings._tabs')

    <form action="{{ route('admin.settings.profile.update') }}" method="POST" id="storeProfileForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Identitas & Alamat Toko (7 cols) -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6 text-xs">
                
                <div class="flex items-center justify-between border-b border-[#EADFD4] pb-3">
                    <div>
                        <h2 class="font-serif text-xl text-[#2A2626]">Identitas Resmi Studio Florist</h2>
                        <span class="text-[11px] text-[#6B6565]">Konfigurasi lokasi dan operasional utama toko</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-[#5C6A57]/10 text-[#5C6A57] text-[10px] font-bold">
                        Surabaya Pusat
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block font-semibold text-[#2A2626] mb-1">Nama Toko *</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? 'Desy Florist') }}" required
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm font-medium">
                    </div>

                    <div>
                        <label class="block font-semibold text-[#2A2626] mb-1">Tagline Bisnis</label>
                        <input type="text" name="store_tagline" value="{{ old('store_tagline', $settings['store_tagline'] ?? 'Artisan Floral Studio & Same Day Delivery Surabaya') }}"
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>

                    <!-- Wilayah Utama Konfirmasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4]">
                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Kota Utama Toko</label>
                            <input type="text" name="store_city" value="{{ old('store_city', $settings['store_city'] ?? 'Surabaya') }}" required readonly
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white font-semibold text-[#2A2626]">
                            <span class="text-[10px] text-[#9B9494]">Kota operasional utama Desy Florist</span>
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Wilayah / Sentra Utama Toko</label>
                            <input type="text" name="store_district" value="{{ old('store_district', $settings['store_district'] ?? 'Surabaya Pusat') }}" required
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white font-semibold text-[#2A2626]">
                            <span class="text-[10px] text-[#9B9494]">Titik tolak pengiriman utama pesanan</span>
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Provinsi</label>
                            <input type="text" name="store_province" value="{{ old('store_province', $settings['store_province'] ?? 'Jawa Timur') }}" required readonly
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white text-[#2A2626]">
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Zona Waktu Operasional</label>
                            <input type="text" name="store_timezone" value="{{ old('store_timezone', $settings['store_timezone'] ?? 'Asia/Jakarta') }}" required readonly
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white text-[#2A2626]">
                            <span class="text-[10px] text-[#9B9494]">Waktu Indonesia Barat (WIB)</span>
                        </div>
                    </div>

                    <!-- Alamat Lengkap Fisik Toko -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="block font-semibold text-[#2A2626]">Alamat Lengkap Toko Fisik (Dikonfirmasi Pemilik) *</label>
                            <span class="text-[10px] text-[#B85D66] font-semibold">Wajib Alamat Akurat</span>
                        </div>
                        <textarea name="store_address" id="store_address" rows="3" required
                                  placeholder="Masukkan alamat ruko / gedung / nomor jalan toko yang sebenarnya di Surabaya Pusat..."
                                  class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] leading-relaxed">{{ old('store_address', $settings['store_address'] ?? 'Jl. Kayoon No. 42 (Sentra Bunga Surabaya Pusat), Kec. Genteng, Surabaya, Jawa Timur 60271') }}</textarea>
                        <p class="text-[11px] text-[#6B6565]">
                            Alamat ini akan ditampilkan pada faktur nota pesanan, kartu nama, informasi penjemputan mitra kurir, serta titik awal rute pengantaran.
                        </p>
                    </div>

                    <!-- Kontak & Media Sosial Admin -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-[#EADFD4]">
                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">WhatsApp CS & Pesanan *</label>
                            <input type="text" name="store_whatsapp" value="{{ old('store_whatsapp', $settings['store_whatsapp'] ?? '6281234567890') }}" required
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                            <span class="text-[10px] text-[#9B9494]">Format angka internasional (628...)</span>
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Email Resmi Florist *</label>
                            <input type="email" name="store_email" value="{{ old('store_email', $settings['store_email'] ?? 'halo@desyflorist.com') }}" required
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Akun Instagram Toko</label>
                            <input type="text" name="store_instagram" value="{{ old('store_instagram', $settings['store_instagram'] ?? 'desyflorist.official') }}" placeholder="desyflorist.official"
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                            <span class="text-[10px] text-[#9B9494]">Username IG (dengan / tanpa @)</span>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-[#2A2626] mb-1">Google Maps API Key (Opsional)</label>
                        <input type="text" name="google_maps_api_key" value="{{ old('google_maps_api_key', $settings['google_maps_api_key'] ?? '') }}"
                               placeholder="AIzaSy..."
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] font-mono text-xs">
                        <span class="text-[10px] text-[#9B9494] block mt-0.5">
                            Jika diisi, sistem dapat mengaktifkan Google Maps Routing / Distance Matrix API. Jika kosong, sistem otomatis menggunakan peta Leaflet interaktif & perhitungan zona admin tanpa kendala biaya.
                        </span>
                    </div>

                </div>

                <div class="pt-4 border-t border-[#EADFD4] flex items-center justify-between">
                    <span class="text-[11px] text-[#6B6565]">Perubahan disimpan ke database toko.</span>
                    <button type="submit" class="py-3 px-6 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-bold uppercase tracking-wider transition shadow-sm">
                        Simpan Profil & Titik Toko
                    </button>
                </div>

            </div>

            <!-- Right: Peta Interaktif & Koordinat Titik Awal Toko (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-serif text-lg text-[#2A2626]">Titik Koordinat Toko (Peta)</h3>
                            <p class="text-[11px] text-[#6B6565]">Klik atau geser pin pada peta untuk menentukan koordinat presisi toko.</p>
                        </div>
                        <span class="w-8 h-8 rounded-full bg-[#B85D66]/10 text-[#B85D66] flex items-center justify-center font-bold">
                            📍
                        </span>
                    </div>

                    <!-- Input Koordinat Lat/Lng -->
                    <div class="grid grid-cols-2 gap-3 bg-[#FAF8F5] p-3 rounded-xl border border-[#EADFD4]">
                        <div>
                            <label class="block text-[10px] font-bold text-[#6B6565] uppercase">Latitude *</label>
                            <input type="text" id="store_latitude" name="store_latitude" step="any"
                                   value="{{ old('store_latitude', $settings['store_latitude'] ?? '-7.2654000') }}" required
                                   onchange="updateMarkerFromInputs()"
                                   class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white font-mono text-xs font-semibold text-[#2A2626]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[#6B6565] uppercase">Longitude *</label>
                            <input type="text" id="store_longitude" name="store_longitude" step="any"
                                   value="{{ old('store_longitude', $settings['store_longitude'] ?? '112.7483000') }}" required
                                   onchange="updateMarkerFromInputs()"
                                   class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white font-mono text-xs font-semibold text-[#2A2626]">
                        </div>
                    </div>

                    <!-- Quick GPS Helper Buttons -->
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="getCurrentDeviceLocation()"
                                class="flex-1 py-2 px-3 rounded-xl bg-[#EEF2EB] text-[#5C6A57] hover:bg-[#5C6A57] hover:text-white transition font-semibold text-center border border-[#7B8B75]/30">
                            🎯 Ambil GPS Lokasi Saya
                        </button>
                        <button type="button" onclick="resetToSurabayaCenter()"
                                class="py-2 px-3 rounded-xl bg-[#FAF8F5] text-[#6B6565] hover:text-[#2A2626] border border-[#EADFD4] transition font-semibold">
                            Reset Surabaya Pusat
                        </button>
                    </div>

                    <!-- Search Location Bar -->
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-[#6B6565] uppercase">Cari Alamat / Tempat di Peta</label>
                        <div class="flex gap-2">
                            <input type="text" id="mapSearchInput" placeholder="Ketik nama jalan / ruko / gedung..."
                                   onkeydown="if(event.key==='Enter'){event.preventDefault();searchLocationOnMap();}"
                                   class="flex-1 p-2 rounded-xl border border-[#D8BABF] text-xs">
                            <button type="button" onclick="searchLocationOnMap()"
                                    class="px-3 py-2 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-bold text-xs transition">
                                🔍 Cari
                            </button>
                        </div>
                    </div>

                    <!-- Interactive Map Container -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-[11px] text-[#6B6565]">
                            <span>Peta Interaktif Penanda Toko:</span>
                            <span id="mapStatusText" class="text-[#5C6A57] font-semibold">✓ Siap Digeser</span>
                        </div>
                        <div id="storeLocationMap" class="w-full h-80 rounded-xl border border-[#D8BABF] z-10 shadow-inner bg-[#EFEBE4]"></div>
                        <div class="flex items-center justify-between text-[10px] text-[#9B9494]">
                            <span>* Geser penanda merah atau klik pada peta.</span>
                            <button type="button" onclick="reverseGeocodeFromMarker()"
                                    class="text-[#B85D66] font-semibold hover:underline">
                                📍 Terapkan Alamat dari Pin Peta
                            </button>
                        </div>
                    </div>

                    <div class="p-3.5 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-1">
                        <strong class="text-[#2A2626] block">Titik Awal Pengiriman Aktif:</strong>
                        <p class="text-[11px] text-[#6B6565]" id="activeCoordinateText">
                            Lat: {{ $settings['store_latitude'] ?? '-7.2654000' }} | Lng: {{ $settings['store_longitude'] ?? '112.7483000' }}
                        </p>
                        <a id="previewGmapsLink" href="https://www.google.com/maps?q={{ $settings['store_latitude'] ?? '-7.2654000' }},{{ $settings['store_longitude'] ?? '112.7483000' }}" target="_blank"
                           class="text-[11px] text-[#B85D66] font-bold hover:underline inline-flex items-center gap-1 mt-1">
                            <span>Buka di Google Maps Eksternal ↗</span>
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </form>
</div>

<!-- Leaflet CSS & JS for Interactive Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    let map;
    let marker;

    const latInput = document.getElementById('store_latitude');
    const lngInput = document.getElementById('store_longitude');
    const coordText = document.getElementById('activeCoordinateText');
    const gmapsLink = document.getElementById('previewGmapsLink');

    function initMap() {
        const initialLat = parseFloat(latInput.value) || -7.2654;
        const initialLng = parseFloat(lngInput.value) || 112.7483;

        map = L.map('storeLocationMap').setView([initialLat, initialLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Custom Store Marker Icon
        const storeIcon = L.divIcon({
            html: '<div style="background-color:#B85D66; color:white; width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:18px; border:3px solid white; box-shadow:0 3px 6px rgba(0,0,0,0.3);">🌸</div>',
            className: '',
            iconSize: [36, 36],
            iconAnchor: [18, 36],
            popupAnchor: [0, -36]
        });

        marker = L.marker([initialLat, initialLng], {
            draggable: true,
            icon: storeIcon
        }).addTo(map);

        updateMarkerPopup(initialLat, initialLng);

        // Drag event
        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            setCoordinates(position.lat, position.lng);
        });

        // Click on map event
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            setCoordinates(e.latlng.lat, e.latlng.lng);
        });
    }

    function updateMarkerPopup(lat, lng) {
        if (!marker) return;
        const storeName = document.querySelector('input[name="store_name"]')?.value || 'Desy Florist';
        marker.bindPopup(`<div style="font-size:12px; line-height:1.4; min-width:180px;">
            <b style="color:#B85D66; font-size:13px; display:block;">${storeName}</b>
            <span style="color:#555;">Titik Titik Koordinat Toko:</span><br>
            <span style="font-family:monospace; font-size:11px; color:#2A2626;">${lat}, ${lng}</span><br>
            <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" rel="noopener noreferrer" style="color:#B85D66; font-weight:bold; display:inline-block; margin-top:4px;">Buka di Google Maps ↗</a>
        </div>`).openPopup();
    }

    function setCoordinates(lat, lng) {
        lat = parseFloat(lat).toFixed(7);
        lng = parseFloat(lng).toFixed(7);

        latInput.value = lat;
        lngInput.value = lng;

        coordText.textContent = `Lat: ${lat} | Lng: ${lng}`;
        gmapsLink.href = `https://www.google.com/maps?q=${lat},${lng}`;

        updateMarkerPopup(lat, lng);
    }

    function updateMarkerFromInputs() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);

        if (!isNaN(lat) && !isNaN(lng) && map && marker) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
            setCoordinates(lat, lng);
        }
    }

    async function searchLocationOnMap() {
        const queryInput = document.getElementById('mapSearchInput');
        const query = queryInput ? queryInput.value.trim() : '';
        if (!query) {
            alert('Silakan masukkan nama jalan, gedung, atau daerah yang ingin dicari.');
            return;
        }

        const status = document.getElementById('mapStatusText');
        status.textContent = '⏳ Mencari lokasi...';

        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`, {
                headers: { 'Accept-Language': 'id' }
            });
            const data = await res.json();
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                map.setView([lat, lon], 16);
                marker.setLatLng([lat, lon]);
                setCoordinates(lat, lon);
                status.textContent = '✓ Lokasi ditemukan!';
            } else {
                status.textContent = '⚠️ Tidak ditemukan';
                alert('Lokasi tidak ditemukan. Coba gunakan nama jalan atau tempat yang lebih spesifik.');
            }
        } catch (e) {
            status.textContent = '⚠️ Gagal mencari';
            alert('Gagal menghubungi layanan pencarian peta.');
        }
    }

    async function reverseGeocodeFromMarker() {
        const lat = latInput.value;
        const lng = lngInput.value;
        const status = document.getElementById('mapStatusText');
        status.textContent = '⏳ Mengambil alamat dari pin...';

        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
                headers: { 'Accept-Language': 'id' }
            });
            const data = await res.json();
            if (data && data.display_name) {
                const addrInput = document.getElementById('store_address');
                if (addrInput) {
                    addrInput.value = data.display_name;
                    status.textContent = '✓ Alamat berhasil diterapkan!';
                }
            } else {
                status.textContent = '⚠️ Alamat tidak ditemukan';
            }
        } catch (e) {
            status.textContent = '⚠️ Gagal mengambil alamat';
        }
    }

    function resetToSurabayaCenter() {
        const defaultLat = -7.2654;
        const defaultLng = 112.7483;
        if (map && marker) {
            marker.setLatLng([defaultLat, defaultLng]);
            map.setView([defaultLat, defaultLng], 14);
            setCoordinates(defaultLat, defaultLng);
        }
    }

    function getCurrentDeviceLocation() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung Geolocation.');
            return;
        }

        const status = document.getElementById('mapStatusText');
        status.textContent = '⏳ Mengambil sinyal GPS...';

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                if (map && marker) {
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng], 16);
                    setCoordinates(lat, lng);
                    status.textContent = '✓ Lokasi GPS Diterapkan!';
                }
            },
            function (error) {
                status.textContent = '⚠️ Gagal mengambil GPS';
                alert('Tidak dapat mendeteksi lokasi GPS Anda: ' + error.message);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection
