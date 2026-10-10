@extends('layouts.app')

@section('title', 'Lacak Status Pengiriman Bunga — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <span class="text-[#2A2626]">Lacak Pengiriman</span>
        </nav>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Lacak Pesanan Bunga Anda</h1>
        <p class="text-xs sm:text-sm text-[#6B6565] mt-1 font-light">
            Pantau proses perangkaian dan pengiriman rangkaian bunga Anda secara real-time.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-8">
    
    <!-- Tracking Search Box -->
    <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs">
        <form method="GET" action="{{ route('checkout.tracking') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="order_number" value="{{ $searchNumber }}" required
                   placeholder="Masukkan nomor pesanan atau nomor resi (Contoh: DF-RESI-... atau DSY-...)"
                   class="flex-1 px-4 py-3 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] text-xs font-mono uppercase bg-[#FAF8F5]/40">
            <button type="submit"
                    class="py-3 px-6 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold uppercase tracking-wider transition shadow-sm">
                Lacak Sekarang
            </button>
        </form>
    </div>

    @if($searchNumber && !$order)
        <div class="bg-white p-8 rounded-2xl border border-[#EADFD4] text-center space-y-3">
            <span class="text-3xl block">🔍</span>
            <h3 class="font-serif text-lg text-[#2A2626]">Nomor Pesanan / Resi Tidak Ditemukan</h3>
            <p class="text-xs text-[#6B6565]">
                Kami tidak menemukan pesanan dengan nomor atau resi <strong>{{ $searchNumber }}</strong>. Pastikan nomor yang Anda masukkan sudah benar.
            </p>
        </div>
    @elseif($order)
        <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs p-6 sm:p-8 space-y-8">
            
            <!-- Header Status -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-[#EADFD4] gap-4">
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[10px] text-[#9B9494] uppercase tracking-wider">No. Pesanan:</span>
                        <span class="font-mono text-sm font-bold text-[#2A2626]">{{ $order->order_number }}</span>
                    </div>

                    @if($order->tracking_number)
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[10px] text-[#5C6A57] uppercase font-bold tracking-wider">No. Resi Resmi:</span>
                            <span class="font-mono text-base sm:text-lg font-bold text-[#111827] bg-[#EEF2EB] px-2.5 py-0.5 rounded-lg border border-[#7B8B75]/40 select-all">
                                {{ $order->tracking_number }}
                            </span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}'); alert('Nomor resi disalin: {{ $order->tracking_number }}');"
                                    class="text-[10px] px-2 py-0.5 rounded-md bg-white border border-[#D8BABF] text-[#2A2626] hover:bg-[#F8E8EA] transition font-medium">
                                📋 Salin
                            </button>
                        </div>
                    @endif

                    <span class="text-xs text-[#6B6565] block">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $order->status_badge }}">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <!-- Visual Progress Timeline -->
            @php
                $statusHierarchy = [
                    'pending' => 1,
                    'paid' => 2,
                    'confirmed' => 2,
                    'processing' => 3,
                    'ready' => 4,
                    'assigned' => 4,
                    'picked_up' => 5,
                    'on_delivery' => 5,
                    'delivered' => 6,
                    'completed' => 6,
                    'cancelled' => 0,
                ];
                $currentStep = $statusHierarchy[$order->status] ?? 1;
            @endphp

            @if($order->status === 'cancelled')
                <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 text-xs text-center font-medium">
                    ⚠️ Pesanan ini telah dibatalkan.
                </div>
            @else
                <div class="space-y-4">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#2A2626]">Status Perjalanan Bunga</h3>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-2 text-center text-xs">
                        
                        <div class="p-3 rounded-xl border {{ $currentStep >= 1 ? 'border-[#7B8B75] bg-[#EEF2EB] text-[#2A2626]' : 'border-[#EADFD4] text-[#9B9494]' }}">
                            <div class="text-base mb-1">📝</div>
                            <span class="font-bold block text-[11px]">Diterima</span>
                            <span class="text-[9px] text-[#6B6565]">Menunggu bayar</span>
                        </div>

                        <div class="p-3 rounded-xl border {{ $currentStep >= 2 ? 'border-[#7B8B75] bg-[#EEF2EB] text-[#2A2626]' : 'border-[#EADFD4] text-[#9B9494]' }}">
                            <div class="text-base mb-1">💳</div>
                            <span class="font-bold block text-[11px]">Dikonfirmasi</span>
                            <span class="text-[9px] text-[#6B6565]">Pembayaran pas</span>
                        </div>

                        <div class="p-3 rounded-xl border {{ $currentStep >= 3 ? 'border-[#7B8B75] bg-[#EEF2EB] text-[#2A2626]' : 'border-[#EADFD4] text-[#9B9494]' }}">
                            <div class="text-base mb-1">💐</div>
                            <span class="font-bold block text-[11px]">Dirangkai</span>
                            <span class="text-[9px] text-[#6B6565]">Oleh Florist</span>
                        </div>

                        <div class="p-3 rounded-xl border {{ $currentStep >= 4 ? 'border-[#7B8B75] bg-[#EEF2EB] text-[#2A2626]' : 'border-[#EADFD4] text-[#9B9494]' }}">
                            <div class="text-base mb-1">📦</div>
                            <span class="font-bold block text-[11px]">Siap Kirim</span>
                            <span class="text-[9px] text-[#6B6565]">Kurir ditugaskan</span>
                        </div>

                        <div class="p-3 rounded-xl border {{ $currentStep >= 5 ? 'border-[#7B8B75] bg-[#EEF2EB] text-[#2A2626]' : 'border-[#EADFD4] text-[#9B9494]' }}">
                            <div class="text-base mb-1">🛵</div>
                            <span class="font-bold block text-[11px]">Diantar</span>
                            <span class="text-[9px] text-[#6B6565]">Dalam perjalanan</span>
                        </div>

                        <div class="p-3 rounded-xl border {{ $currentStep >= 6 ? 'border-[#7B8B75] bg-[#EEF2EB] text-[#2A2626]' : 'border-[#EADFD4] text-[#9B9494]' }}">
                            <div class="text-base mb-1">🎉</div>
                            <span class="font-bold block text-[11px]">Terkirim</span>
                            <span class="text-[9px] text-[#6B6565]">Bunga diterima</span>
                        </div>

                    </div>
                </div>
            @endif

            <!-- Payment Proof Status & Upload Section -->
            @include('partials.payment-proof-box', ['order' => $order])

            <!-- Live Courier Map Section -->
            @php
                $storeLat = (float) ($storeSettings['store_latitude'] ?? -7.257472);
                $storeLng = (float) ($storeSettings['store_longitude'] ?? 112.752088);
                $recLat = (float) ($order->recipient_latitude ?? $storeLat);
                $recLng = (float) ($order->recipient_longitude ?? $storeLng);
                $curLat = (float) ($order->delivery?->current_latitude ?? $storeLat);
                $curLng = (float) ($order->delivery?->current_longitude ?? $storeLng);
                $isOnDelivery = in_array($order->status, ['on_delivery', 'picked_up', 'ready', 'assigned']);
            @endphp

            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

            <div class="pt-4 border-t border-[#EADFD4] space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="font-serif text-lg text-[#2A2626] flex items-center gap-2">
                            <span>🗺️ Peta Pelacakan Pengiriman Kurir Live</span>
                            @if($isOnDelivery)
                                <span class="flex h-2.5 w-2.5 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                            @endif
                        </h3>
                        <p class="text-xs text-[#6B6565]">
                            Pantau pergerakan posisi kurir khusus Desy Florist secara langsung dari toko menuju lokasi penerima.
                        </p>
                    </div>

                    <div id="liveSyncBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-medium {{ $isOnDelivery ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600' }}">
                        <span>📡 {{ $isOnDelivery ? 'Tersinkronisasi Real-Time (Auto Updates)' : 'Pengiriman Belum Dimulai' }}</span>
                    </div>
                </div>

                <!-- Interactive Map Container -->
                <div class="relative">
                    <div id="liveTrackingMap" class="w-full h-80 sm:h-96 rounded-2xl border border-[#EADFD4] shadow-inner bg-[#FAF8F5] z-0"></div>

                    <div class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-xs p-2.5 rounded-xl border border-[#EADFD4] shadow-md z-10 text-[10px] space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#B85D66] text-white text-[8px] flex items-center justify-center font-bold">🏠</span>
                            <span class="text-[#2A2626] font-semibold">Toko Desy Florist</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#C5A059] text-white text-[8px] flex items-center justify-center font-bold">🛵</span>
                            <span class="text-[#2A2626] font-semibold">Kurir (Lokasi Live)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#5C6A57] text-white text-[8px] flex items-center justify-center font-bold">📍</span>
                            <span class="text-[#2A2626] font-semibold">Lokasi Penerima</span>
                        </div>
                    </div>
                </div>

                <!-- Live Status Bar -->
                <div class="p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#B85D66] text-white flex items-center justify-center text-lg font-bold shadow-sm">
                            🛵
                        </div>
                        <div>
                            <span id="liveCourierName" class="font-bold text-[#2A2626] block">
                                {{ $order->delivery?->courier?->name ?? 'Kurir Desy Florist' }}
                            </span>
                            <span id="liveStatusText" class="text-[11px] text-[#6B6565]">
                                {{ $order->delivery ? $order->delivery->status_label : 'Menunggu kurir ditugaskan' }}
                            </span>
                        </div>
                    </div>

                    <div class="text-right text-[11px] space-y-0.5 w-full sm:w-auto">
                        <div class="text-[#9B9494]">
                            Terakhir Diperbarui: <strong id="liveLastTime" class="text-[#2A2626] font-mono">{{ $order->delivery?->last_location_updated_at ? $order->delivery->last_location_updated_at->format('H:i:s') : date('H:i:s') }} WIB</strong>
                        </div>
                        <div id="liveDistanceText" class="text-[#5C6A57] font-semibold">
                            Estimasi Jarak Pengiriman: {{ number_format($order->distance_km ?? 0, 1) }} KM
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const storeCoords = [{{ $storeLat }}, {{ $storeLng }}];
                    const recipientCoords = [{{ $recLat }}, {{ $recLng }}];
                    let courierCoords = [{{ $curLat }}, {{ $curLng }}];

                    // Initialize Leaflet Map
                    const mapRenderer = L.svg({ padding: 1.5 });
                    const map = L.map('liveTrackingMap', { zoomControl: true, renderer: mapRenderer }).setView(courierCoords, 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    // Custom HTML Icons
                    const storeIcon = L.divIcon({
                        className: 'custom-map-icon',
                        html: `<div style="background:#B85D66; color:white; width:34px; height:34px; rounded-radius:50%; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; border:2.5px solid white; box-shadow:0 4px 8px rgba(0,0,0,0.25);">🏠</div>`,
                        iconSize: [34, 34],
                        iconAnchor: [17, 17]
                    });

                    const courierIcon = L.divIcon({
                        className: 'custom-map-icon',
                        html: `<div style="background:#C5A059; color:white; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px; border:3px solid white; box-shadow:0 4px 12px rgba(197,160,89,0.5); animation: pulse 2s infinite;">🛵</div>`,
                        iconSize: [40, 40],
                        iconAnchor: [20, 20]
                    });

                    const recipientIcon = L.divIcon({
                        className: 'custom-map-icon',
                        html: `<div style="background:#5C6A57; color:white; width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; border:2.5px solid white; box-shadow:0 4px 8px rgba(0,0,0,0.25);">📍</div>`,
                        iconSize: [34, 34],
                        iconAnchor: [17, 17]
                    });

                    // Add Store, Recipient, and Courier Markers
                    const storeMarker = L.marker(storeCoords, { icon: storeIcon }).addTo(map).bindPopup('<b>Studio Desy Florist</b><br>Titik Keberangkatan Rangkaian Bunga');
                    const recipientMarker = L.marker(recipientCoords, { icon: recipientIcon }).addTo(map).bindPopup('<b>Tujuan Pengiriman</b><br>{{ e($order->recipient_name) }}');
                    const courierMarker = L.marker(courierCoords, { icon: courierIcon }).addTo(map).bindPopup('<b>Kurir Desy Florist</b><br>Lokasi Terkini');

                    // Route Polyline (Initial & Road Navigation)
                    let routePolyline = L.polyline([storeCoords, courierCoords, recipientCoords], {
                        color: '#B85D66',
                        weight: 5,
                        opacity: 0.85,
                        lineCap: 'round',
                        lineJoin: 'round',
                        noClip: true,
                        smoothFactor: 1.0
                    }).addTo(map);

                    // Fit map bounds to show all markers
                    const bounds = L.latLngBounds([storeCoords, recipientCoords, courierCoords]);
                    map.fitBounds(bounds, { padding: [40, 40], maxZoom: 15 });

                    // OSRM Real Road Route Fetcher
                    let osrmAbortController = null;
                    let lastFetchedCourierPos = null;

                    function fetchRoadRoute(cLat, cLng) {
                        // Avoid re-fetching if courier position hasn't changed significantly (less than ~5 meters)
                        if (lastFetchedCourierPos) {
                            const distDiff = Math.hypot(lastFetchedCourierPos.lat - cLat, lastFetchedCourierPos.lng - cLng);
                            if (distDiff < 0.00005) {
                                return; // Keep existing line completely intact!
                            }
                        }

                        if (osrmAbortController) osrmAbortController.abort();
                        osrmAbortController = new AbortController();

                        // OSRM Waypoints: Store -> Courier -> Recipient
                        const waypoints = `${storeCoords[1]},${storeCoords[0]};${cLng},${cLat};${recipientCoords[1]},${recipientCoords[0]}`;
                        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${waypoints}?overview=full&geometries=geojson`;

                        fetch(osrmUrl, { signal: osrmAbortController.signal })
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.routes && data.routes.length > 0) {
                                    const coords = data.routes[0].geometry.coordinates;
                                    const roadPoints = coords.map(c => [c[1], c[0]]);
                                    routePolyline.setLatLngs(roadPoints);
                                    routePolyline.setStyle({ color: '#B85D66', weight: 5, dashArray: null, opacity: 0.9 });
                                    lastFetchedCourierPos = { lat: cLat, lng: cLng };

                                    const distanceKm = (data.routes[0].distance / 1000).toFixed(1);
                                    const distEl = document.getElementById('liveDistanceText');
                                    if (distEl) {
                                        distEl.textContent = `Estimasi Jarak Rute Jalan: ${distanceKm} KM`;
                                    }
                                }
                            })
                            .catch(err => {
                                // Do NOT reset or clear polyline on error/abort to prevent line disappearing!
                            });
                    }

                    // Initial Road Route Fetch
                    fetchRoadRoute(courierCoords[0], courierCoords[1]);

                    // Auto Polling function to refresh location from backend JSON endpoint
                    const trackingApiUrl = "{{ route('checkout.tracking', $order->order_number) }}";
                    const isOrderActive = {{ $order->status !== 'completed' && $order->status !== 'cancelled' ? 'true' : 'false' }};

                    function pollCourierLocation() {
                        fetch(trackingApiUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.current_latitude && data.current_longitude) {
                                const newLat = parseFloat(data.current_latitude);
                                const newLng = parseFloat(data.current_longitude);

                                // Smoothly update courier marker position
                                courierMarker.setLatLng([newLat, newLng]);

                                // Fetch updated real road route following new courier coordinates
                                fetchRoadRoute(newLat, newLng);

                                // Update UI text
                                if (data.courier_name) {
                                    document.getElementById('liveCourierName').textContent = data.courier_name;
                                }
                                if (data.delivery_status_label) {
                                    document.getElementById('liveStatusText').textContent = data.delivery_status_label;
                                }
                                if (data.last_updated_time) {
                                    document.getElementById('liveLastTime').textContent = data.last_updated_time + ' WIB';
                                }
                            }
                        })
                        .catch(err => console.error("Tracking poll error:", err));
                    }

                    // Poll every 4 seconds if order is active
                    if (isOrderActive) {
                        setInterval(pollCourierLocation, 4000);
                    }
                });
            </script>

            <!-- Delivery & Courier Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-[#EADFD4] text-xs">
                <div class="space-y-2">
                    <h4 class="font-semibold text-[#2A2626] uppercase tracking-wider text-[11px]">Tujuan Pengiriman:</h4>
                    <p class="text-[#2A2626] font-medium">{{ $order->recipient_name }} ({{ $order->recipient_phone }})</p>
                    <p class="text-[#6B6565] leading-relaxed">{{ $order->delivery_address }}</p>
                    <div class="pt-1 text-[11px] text-[#9B9494]">
                        📅 Rencana Pengiriman: {{ $order->delivery_date->format('d M Y') }} • Slot {{ $order->delivery_slot }}
                    </div>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-[#2A2626] uppercase tracking-wider text-[11px]">Informasi Kurir:</h4>
                    @if($order->delivery && $order->delivery->courier)
                        <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#E5B2B6] text-white flex items-center justify-center font-bold">
                                🛵
                            </div>
                            <div>
                                <span class="font-bold text-[#2A2626] block">{{ $order->delivery->courier->name }}</span>
                                <span class="text-[11px] text-[#6B6565]">Kurir Khusus Bunga Desy Florist</span>
                            </div>
                        </div>
                    @else
                        <p class="text-[#9B9494] italic">Kurir akan segera ditugaskan setelah bunga selesai dirangkai.</p>
                    @endif
                </div>
            </div>

            <!-- Courier Delivery Proof Photos (If Delivered) -->
            @if($order->delivery && $order->delivery->proofs->isNotEmpty())
                <div class="pt-4 border-t border-[#EADFD4]">
                    <div class="bg-[#EEF2EB]/50 p-5 rounded-2xl border border-[#7B8B75]/30 space-y-3 text-xs">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                                <span>🌸</span> Bukti Serah Terima Penerimaan Bunga
                            </h4>
                            <span class="text-[10px] text-[#5C6A57] font-semibold">
                                Diterima {{ $order->delivery->delivered_at?->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                        <p class="text-xs text-[#6B6565]">Foto bukti penerimaan yang diunggah oleh kurir saat pengantaran (Klik foto untuk memperbesar):</p>
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
                </div>
            @endif

            <!-- Order Items -->
            <div class="pt-4 border-t border-[#EADFD4] space-y-3">
                <h4 class="font-semibold text-[#2A2626] uppercase tracking-wider text-[11px]">Rangkaian dalam Pesanan Ini:</h4>
                <div class="divide-y divide-[#F3ECE4]">
                    @foreach($order->items as $it)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <img src="{{ $it->product?->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=200&q=80' }}"
                                     alt="{{ $it->name }}" class="w-10 h-10 rounded-lg object-cover">
                                <div>
                                    <span class="font-semibold text-[#2A2626] block">{{ $it->name }}</span>
                                    <span class="text-[10px] text-[#9B9494]">{{ $it->quantity }}x Rp {{ number_format($it->unit_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <span class="font-semibold text-[#2A2626]">
                                Rp {{ number_format($it->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
