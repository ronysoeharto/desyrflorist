@extends('layouts.admin')

@section('title', 'Detail Pengiriman Bunga — ' . $delivery->order->order_number)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <nav class="text-xs text-[#9B9494] mb-1">
                <a href="{{ route('admin.deliveries.index') }}" class="hover:underline">← Kembali ke Manajemen Pengiriman</a>
            </nav>
            <h1 class="font-serif text-3xl text-[#2A2626]">Lacak Rute & Posisi Kurir</h1>
            <p class="text-xs text-[#6B6565]">Nomor Pesanan: <strong class="font-mono text-[#B85D66]">{{ $delivery->order->order_number }}</strong></p>
        </div>

        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase
            @if($delivery->status === 'delivered') bg-[#EEF2EB] text-[#5C6A57]
            @elseif($delivery->status === 'on_delivery') bg-purple-100 text-purple-700
            @elseif($delivery->status === 'picked_up') bg-blue-100 text-blue-700
            @else bg-[#FAF8F5] text-[#C5A059] border border-[#C5A059]/30 @endif">
            {{ $delivery->status_label }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Peta Rute Pengiriman (7 cols) -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-serif text-lg text-[#2A2626]">Peta Rute Toko ke Penerima</h2>
                    <span class="text-xs text-[#6B6565]">Visualisasi posisi toko Desy Florist, pergerakan kurir, dan tujuan</span>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#B85D66]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#B85D66]"></span> Toko
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-purple-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span> Kurir
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#5C6A57]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#5C6A57]"></span> Penerima
                    </span>
                </div>
            </div>

            <!-- Map Container -->
            <div id="adminDeliveryMap" class="w-full h-96 rounded-xl border border-[#D8BABF] shadow-inner bg-[#EFEBE4] z-10"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs pt-2 gap-2">
                <span class="text-[#6B6565]">
                    Pembaruan Lokasi Kurir Terakhir: 
                    <strong id="adminLastUpdatedText">{{ $delivery->last_location_updated_at ? $delivery->last_location_updated_at->format('H:i:s, d M Y') . ' (' . $delivery->last_location_updated_at->diffForHumans() . ')' : 'Belum Ada Pembaruan GPS' }}</strong>
                </span>
                <span id="adminDistanceText" class="text-[#5C6A57] font-semibold">
                    Estimasi Jarak Rute Jalan: {{ number_format($delivery->order->distance_km ?? 0, 1) }} KM
                </span>
            </div>

            <div class="flex justify-end pt-1">
                <a href="{{ $delivery->order->google_maps_url }}" target="_blank"
                   class="px-3 py-1.5 bg-[#FAF8F5] border border-[#D8BABF] rounded-lg text-[#2A2626] hover:border-[#B85D66] font-semibold transition inline-flex items-center gap-1.5">
                    <span>🗺️ Buka Rute di Google Maps</span>
                    <span>↗</span>
                </a>
            </div>

            <!-- Manual Coordinate Dispatcher (For Admin Simulation/Testing) -->
            <div class="pt-4 border-t border-[#EADFD4] text-xs">
                <details class="cursor-pointer">
                    <summary class="font-bold text-[#6B6565] hover:text-[#2A2626]">
                        🔧 Simulasi Perbarui Koordinat Kurir (Admin Test Tool)
                    </summary>
                    <form action="{{ route('admin.deliveries.location', $delivery->id) }}" method="POST" class="pt-3 space-y-3">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-[#6B6565] uppercase">Latitude Kurir</label>
                                <input type="text" name="latitude" value="{{ $delivery->current_latitude ?? -7.2654 }}" required
                                       class="w-full p-2 rounded-lg border border-[#D8BABF] font-mono text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-[#6B6565] uppercase">Longitude Kurir</label>
                                <input type="text" name="longitude" value="{{ $delivery->current_longitude ?? 112.7483 }}" required
                                       class="w-full p-2 rounded-lg border border-[#D8BABF] font-mono text-xs">
                            </div>
                        </div>
                        <button type="submit" class="py-2 px-4 bg-[#2A2626] text-white rounded-lg font-bold uppercase tracking-wider text-[11px]">
                            Update Koordinat Kurir Ini
                        </button>
                    </form>
                </details>
            </div>
        </div>

        <!-- Info Pesanan & Penerima (5 cols) -->
        <div class="lg:col-span-5 space-y-6 text-xs">
            
            <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                <h3 class="font-serif text-lg text-[#2A2626]">Informasi Tujuan & Kurir</h3>

                <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-1">
                    <span class="text-[10px] uppercase font-bold text-[#9B9494] block">Kurir yang Bertugas:</span>
                    @if($delivery->courier)
                        <strong class="text-sm text-[#2A2626] block">{{ $delivery->courier->name }}</strong>
                        <span class="text-[11px] text-[#6B6565] block">Nomor HP/WA: {{ $delivery->courier->phone ?? '-' }}</span>
                        <div class="pt-2">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $delivery->courier->phone ?? '') }}" target="_blank"
                               class="text-[#5C6A57] font-bold hover:underline inline-flex items-center gap-1">
                                <span>💬 Chat WhatsApp Kurir</span>
                            </a>
                        </div>
                    @else
                        <span class="text-[#9B9494] italic">Belum ada kurir yang ditugaskan.</span>
                    @endif
                </div>

                <div class="space-y-1 pt-1">
                    <span class="text-[10px] uppercase font-bold text-[#9B9494] block">Penerima Bunga:</span>
                    <strong class="text-sm text-[#2A2626] block">{{ $delivery->order->recipient_name }}</strong>
                    <span class="text-[#6B6565] block">Telepon: {{ $delivery->order->recipient_phone }}</span>
                    <p class="text-[#2A2626] leading-relaxed pt-1">{{ $delivery->order->delivery_address }}</p>
                    <div class="text-[11px] text-[#5C6A57] font-semibold pt-1">
                        Jarak dari Toko: {{ $delivery->order->distance_km ? $delivery->order->distance_km . ' KM' : 'Mengikuti Zona' }}
                    </div>
                </div>

                @if($delivery->order->delivery_notes)
                    <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4]">
                        <span class="text-[10px] uppercase font-bold text-[#9B9494] block">Catatan Patokan / Titik Antar:</span>
                        <p class="text-[#6B6565] italic mt-0.5">{{ $delivery->order->delivery_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Bukti Serah Terima Bunga -->
            <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-serif text-lg text-[#2A2626]">Bukti Serah Terima</h3>
                    @if($delivery->proofs && $delivery->proofs->isNotEmpty())
                        <span class="text-[10px] text-[#9B9494]">🔍 Klik foto untuk memperbesar</span>
                    @endif
                </div>
                @if($delivery->proofs && $delivery->proofs->isNotEmpty())
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($delivery->proofs as $prf)
                            @php $imgUrl = asset('storage/' . $prf->file_path); @endphp
                            <a href="{{ $imgUrl }}" target="_blank" onclick="openImageModal(event, '{{ $imgUrl }}')"
                               class="group relative block aspect-square rounded-xl overflow-hidden border border-[#EADFD4] hover:border-[#B85D66] transition shadow-2xs cursor-pointer">
                                <img src="{{ $imgUrl }}" alt="Bukti Serah Terima" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-[10px] font-semibold gap-1 p-2 text-center">
                                    <span>🔍 Lihat Resolusi Penuh</span> ↗
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <span class="text-[10px] text-[#5C6A57] font-semibold block pt-1">
                        ✓ Bunga telah diterima pada {{ $delivery->delivered_at?->format('d M Y, H:i') }} WIB
                    </span>
                @else
                    <p class="text-[#9B9494] italic text-[11px]">
                        Foto bukti penerimaan akan muncul di sini setelah kurir berhasil menyerahkan rangkaian bunga.
                    </p>
                @endif
            </div>

        </div>

    </div>
</div>

<!-- Leaflet Map Script -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const storeLat = parseFloat("{{ $storeSettings['store_latitude'] ?? '-7.2654' }}");
        const storeLng = parseFloat("{{ $storeSettings['store_longitude'] ?? '112.7483' }}");

        let courierLat = parseFloat("{{ $delivery->current_latitude ?? '' }}");
        let courierLng = parseFloat("{{ $delivery->current_longitude ?? '' }}");

        const destLat = parseFloat("{{ $delivery->order->recipient_latitude ?? '' }}");
        const destLng = parseFloat("{{ $delivery->order->recipient_longitude ?? '' }}");

        // Map Renderer with padding to prevent line clipping on pan/scroll/zoom
        const mapRenderer = L.svg({ padding: 1.5 });
        const map = L.map('adminDeliveryMap', { zoomControl: true, renderer: mapRenderer }).setView([storeLat, storeLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const markers = [];

        // 1. Store Marker
        const storeIcon = L.divIcon({
            className: 'custom-map-icon',
            html: '<div style="background-color:#B85D66; color:white; width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; border:2.5px solid white; box-shadow:0 3px 6px rgba(0,0,0,0.3);">🌸</div>',
            iconSize: [34, 34],
            iconAnchor: [17, 17]
        });
        const storeMarker = L.marker([storeLat, storeLng], { icon: storeIcon }).addTo(map);
        storeMarker.bindPopup("<b>Desy Florist (Studio)</b><br>Surabaya Pusat");
        markers.push(storeMarker);

        // 2. Destination Marker (if available)
        let destMarker = null;
        if (!isNaN(destLat) && !isNaN(destLng)) {
            const destIcon = L.divIcon({
                className: 'custom-map-icon',
                html: '<div style="background-color:#5C6A57; color:white; width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; border:2.5px solid white; box-shadow:0 3px 6px rgba(0,0,0,0.3);">📍</div>',
                iconSize: [34, 34],
                iconAnchor: [17, 17]
            });
            destMarker = L.marker([destLat, destLng], { icon: destIcon }).addTo(map);
            destMarker.bindPopup("<b>Tujuan: {{ addslashes($delivery->order->recipient_name) }}</b><br>{{ addslashes($delivery->order->delivery_district ?? '') }}");
            markers.push(destMarker);
        }

        // 3. Courier Realtime Marker (if courier reported GPS)
        let courierMarker = null;
        const courierIcon = L.divIcon({
            className: 'custom-map-icon',
            html: '<div style="background-color:#7C3AED; color:white; width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:18px; border:3px solid white; box-shadow:0 4px 10px rgba(124,58,237,0.4);">🛵</div>',
            iconSize: [38, 38],
            iconAnchor: [19, 19]
        });

        if (!isNaN(courierLat) && !isNaN(courierLng)) {
            courierMarker = L.marker([courierLat, courierLng], { icon: courierIcon }).addTo(map);
            courierMarker.bindPopup("<b>Posisi Kurir: {{ addslashes($delivery->courier->name ?? 'Kurir') }}</b><br>Status: {{ $delivery->status_label }}");
            markers.push(courierMarker);
        }

        // Fit Bounds initially
        if (markers.length > 1) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.15));
        }

        // 4. Persistent Polyline for OSRM Road Routing
        let routePolyline = L.polyline([], {
            color: '#B85D66',
            weight: 5,
            opacity: 0.85,
            lineCap: 'round',
            lineJoin: 'round',
            noClip: true,
            smoothFactor: 1.0
        }).addTo(map);

        let osrmAbortController = null;
        let lastFetchedCourierPos = null;

        function fetchRoadRoute(cLat, cLng) {
            const hasCourier = !isNaN(cLat) && !isNaN(cLng);
            const hasDest = !isNaN(destLat) && !isNaN(destLng);

            if (!hasDest) return;

            if (hasCourier && lastFetchedCourierPos) {
                const distDiff = Math.hypot(lastFetchedCourierPos.lat - cLat, lastFetchedCourierPos.lng - cLng);
                if (distDiff < 0.00005) {
                    return; // Position barely changed, keep current route intact
                }
            }

            if (osrmAbortController) osrmAbortController.abort();
            osrmAbortController = new AbortController();

            let waypoints = '';
            if (hasCourier) {
                waypoints = `${storeLng},${storeLat};${cLng},${cLat};${destLng},${destLat}`;
            } else {
                waypoints = `${storeLng},${storeLat};${destLng},${destLat}`;
            }

            const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${waypoints}?overview=full&geometries=geojson`;

            fetch(osrmUrl, { signal: osrmAbortController.signal })
                .then(res => res.json())
                .then(data => {
                    if (data && data.routes && data.routes.length > 0) {
                        const coords = data.routes[0].geometry.coordinates;
                        const roadPoints = coords.map(c => [c[1], c[0]]);
                        routePolyline.setLatLngs(roadPoints);
                        if (hasCourier) {
                            lastFetchedCourierPos = { lat: cLat, lng: cLng };
                        }

                        const distanceKm = (data.routes[0].distance / 1000).toFixed(1);
                        const distEl = document.getElementById('adminDistanceText');
                        if (distEl) {
                            distEl.textContent = `Estimasi Jarak Rute Jalan: ${distanceKm} KM`;
                        }
                    }
                })
                .catch(err => {
                    // Keep existing polyline visible if fetch fails or aborts
                });
        }

        // Fetch initial route
        fetchRoadRoute(courierLat, courierLng);

        // Auto Polling Every 5 Seconds for Live Sync in Admin
        const adminApiUrl = "{{ route('admin.deliveries.show', $delivery->id) }}";
        const isDeliveryActive = "{{ $delivery->status !== 'delivered' && $delivery->status !== 'cancelled' ? 'true' : 'false' }}" === "true";

        if (isDeliveryActive) {
            setInterval(function () {
                fetch(adminApiUrl, {
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

                        if (!isNaN(newLat) && !isNaN(newLng)) {
                            if (!courierMarker) {
                                courierMarker = L.marker([newLat, newLng], { icon: courierIcon }).addTo(map);
                                courierMarker.bindPopup(`<b>Posisi Kurir: ${data.courier_name}</b><br>Status: ${data.status_label}`);
                            } else {
                                courierMarker.setLatLng([newLat, newLng]);
                            }

                            fetchRoadRoute(newLat, newLng);

                            const lastUpdatedEl = document.getElementById('adminLastUpdatedText');
                            if (lastUpdatedEl && data.last_updated_time) {
                                lastUpdatedEl.textContent = `${data.last_updated_time} (${data.last_updated_human})`;
                            }
                        }
                    }
                })
                .catch(() => {});
            }, 5000);
        }
    });
</script>
@endsection
