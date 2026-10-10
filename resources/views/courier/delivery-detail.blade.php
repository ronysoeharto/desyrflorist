@extends('layouts.courier')

@section('title', 'Detail Pengantaran — ' . $delivery->order->order_number)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <nav class="text-xs text-[#9B9494] mb-1">
                <a href="{{ route('courier.deliveries') }}" class="hover:underline">← Kembali ke Daftar</a>
            </nav>
            <h1 class="font-serif text-2xl text-[#2A2626]">Pesanan: {{ $delivery->order->order_number }}</h1>
            @if($delivery->order->tracking_number)
                <div class="mt-1">
                    <span class="text-xs font-mono font-bold text-[#5C6A57] bg-[#EEF2EB] px-2.5 py-0.5 rounded-lg border border-[#7B8B75]/30 inline-block">
                        📦 No. Resi: {{ $delivery->order->tracking_number }}
                    </span>
                </div>
            @endif
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $delivery->status === 'delivered' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
            {{ $delivery->status_label }}
        </span>
    </div>

    <!-- Recipient & Destination Details -->
    <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
        <h2 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px]">Tujuan & Kontak Penerima</h2>
        
        <div class="space-y-2">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] text-[#9B9494] block">Nama Penerima:</span>
                    <strong class="text-sm text-[#2A2626]">{{ $delivery->order->recipient_name }}</strong>
                </div>
                <a href="tel:{{ $delivery->order->recipient_phone }}"
                   class="px-3 py-1.5 bg-[#EEF2EB] text-[#5C6A57] font-bold rounded-lg hover:bg-[#5C6A57] hover:text-white transition">
                    📞 Hubungi {{ $delivery->order->recipient_phone }}
                </a>
            </div>

            <div class="pt-2 border-t border-[#F3ECE4]">
                <span class="text-[10px] text-[#9B9494] block">Alamat Pengiriman:</span>
                <p class="text-[#2A2626] leading-relaxed mt-0.5">{{ $delivery->order->delivery_address }}</p>
                <div class="pt-2">
                    <a href="{{ $delivery->order->google_maps_url }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#FAF8F5] border border-[#D8BABF] rounded-lg text-[#2A2626] hover:border-[#5C6A57] transition font-semibold">
                        <span>🗺️ Buka Rute di Google Maps</span>
                    </a>
                </div>
            </div>

            @if($delivery->order->delivery_notes)
                <div class="pt-2 border-t border-[#F3ECE4]">
                    <span class="text-[10px] text-[#9B9494] block">Catatan Titik Antar:</span>
                    <p class="text-[#6B6565] italic">{{ $delivery->order->delivery_notes }}</p>
                </div>
            @endif

            <div class="pt-2 border-t border-[#F3ECE4] flex justify-between text-[11px] text-[#6B6565]">
                <span>Jadwal: {{ $delivery->order->delivery_date->format('d M Y') }}</span>
                <span>Slot Jam: <strong>{{ $delivery->order->delivery_slot }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Items Preview -->
    <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-3 text-xs">
        <h2 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px]">Bunga yang Dibawa</h2>
        <div class="divide-y divide-[#F3ECE4]">
            @foreach($delivery->order->items as $it)
                <div class="py-2.5 flex items-center justify-between">
                    <span>{{ $it->quantity }}x <strong>{{ $it->name }}</strong></span>
                    <span class="text-[#9B9494]">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Status Progression Controls -->
    @if($delivery->status !== 'delivered')
        <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-5 text-xs">
            <div class="flex items-center justify-between pb-3 border-b border-[#F3ECE4]">
                <div>
                    <h2 class="font-serif text-lg text-[#2A2626]">Tahapan Pengantaran Bunga</h2>
                    <p class="text-[11px] text-[#6B6565]">Ikuti alur kerja berurutan: Ambil bunga di toko terlebih dahulu, baru kemudian mulai pengantaran.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $delivery->status === 'on_delivery' ? 'bg-blue-100 text-blue-700' : ($delivery->status === 'picked_up' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-800') }}">
                    {{ $delivery->status_label }}
                </span>
            </div>

            <!-- Visual 3-Step Wizard Indicator -->
            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                <!-- Step 1 -->
                <div class="p-2.5 rounded-xl border {{ in_array($delivery->status, ['picked_up', 'on_delivery']) ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-amber-50 border-amber-300 text-amber-900 font-bold ring-2 ring-amber-400' }}">
                    <div class="text-sm mb-0.5">{{ in_array($delivery->status, ['picked_up', 'on_delivery']) ? '✓' : '🌸' }}</div>
                    <span class="block text-[11px] font-bold">1. Ambil di Toko</span>
                    <span class="text-[9px] text-[#6B6565]">{{ in_array($delivery->status, ['picked_up', 'on_delivery']) ? 'Sudah Diambil' : 'Pencet Ini Dulu' }}</span>
                </div>

                <!-- Step 2 -->
                <div class="p-2.5 rounded-xl border {{ $delivery->status === 'on_delivery' ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : ($delivery->status === 'picked_up' ? 'bg-blue-50 border-blue-300 text-blue-900 font-bold ring-2 ring-blue-400' : 'bg-gray-50 border-gray-200 text-gray-400') }}">
                    <div class="text-sm mb-0.5">{{ $delivery->status === 'on_delivery' ? '✓' : ($delivery->status === 'picked_up' ? '🛵' : '🔒') }}</div>
                    <span class="block text-[11px] font-bold">2. Mulai Antar</span>
                    <span class="text-[9px] text-[#6B6565]">{{ $delivery->status === 'on_delivery' ? 'Sedang Jalan' : ($delivery->status === 'picked_up' ? 'Siap Berangkat' : 'Terkunci') }}</span>
                </div>

                <!-- Step 3 -->
                <div class="p-2.5 rounded-xl border {{ $delivery->status === 'on_delivery' ? 'bg-purple-50 border-purple-300 text-purple-900 font-bold ring-2 ring-purple-400' : 'bg-gray-50 border-gray-200 text-gray-400') }}">
                    <div class="text-sm mb-0.5">{{ $delivery->status === 'on_delivery' ? '📷' : '🔒' }}</div>
                    <span class="block text-[11px] font-bold">3. Foto Bukti</span>
                    <span class="text-[9px] text-[#6B6565]">{{ $delivery->status === 'on_delivery' ? 'Saat Sampai' : 'Terkunci' }}</span>
                </div>
            </div>

            <!-- Step 1 Form: Hanya Muncul Ketika Belum Diambil di Toko -->
            @if($delivery->status === 'assigned')
                <div class="p-4 rounded-xl bg-amber-50/80 border border-amber-200 space-y-3">
                    <div class="flex items-start gap-2.5">
                        <span class="text-xl">🌸</span>
                        <div class="space-y-1">
                            <strong class="text-xs text-amber-900 block">Langkah 1: Wajib Ambil Bunga di Toko Terlebih Dahulu</strong>
                            <p class="text-[11px] text-amber-800 leading-relaxed">
                                Silakan ambil rangkaian bunga fisik di Studio Desy Florist. Periksa rangkaian bunga dan kartu ucapan. Setelah bunga fisik berada di tangan Anda, tekan tombol konfirmasi di bawah ini.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('courier.deliveries.status', $delivery->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda sudah berada di studio dan mengambil rangkaian bunga fisik ini?');">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="picked_up">
                        <button type="submit" class="w-full py-3.5 px-4 bg-[#C5A059] hover:bg-[#A37E36] text-white rounded-xl font-bold uppercase tracking-wider text-xs transition shadow-sm flex items-center justify-center gap-2">
                            <span>🌸 Saya Sudah Ambil Bunga di Toko</span>
                        </button>
                    </form>
                </div>

                <div class="p-3 rounded-xl bg-gray-50 border border-dashed border-gray-300 text-center text-[11px] text-gray-400">
                    🔒 Tombol <strong>"Mulai Antar ke Penerima"</strong> akan otomatis muncul setelah Anda menekan tombol ambil bunga di atas.
                </div>

            <!-- Step 2 Form: Hanya Muncul Setelah Bunga Diambil di Toko -->
            @elseif($delivery->status === 'picked_up')
                <div class="p-4 rounded-xl bg-blue-50/80 border border-blue-200 space-y-3">
                    <div class="flex items-start gap-2.5">
                        <span class="text-xl">🛵</span>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <strong class="text-xs text-blue-900 block">Langkah 2: Mulai Pengantaran Menuju Alamat Penerima</strong>
                                <span class="text-[10px] text-emerald-700 bg-emerald-100 font-bold px-2 py-0.5 rounded-full">Bunga Sudah Diambil ✓</span>
                            </div>
                            <p class="text-[11px] text-blue-800 leading-relaxed">
                                Bunga sudah bersama Anda. Silakan berangkat menuju penerima (<strong>{{ $delivery->order->recipient_name }}</strong>). Tekan tombol di bawah saat mulai perjalanan agar GPS pelacakan live langsung tersinkronkan ke pembeli.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('courier.deliveries.status', $delivery->id) }}" method="POST"
                          onsubmit="return confirm('Mulai perjalanan pengantaran menuju lokasi penerima? Status akan berubah menjadi Dalam Pengiriman.');">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="on_delivery">
                        <button type="submit" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold uppercase tracking-wider text-xs transition shadow-sm flex items-center justify-center gap-2">
                            <span>🛵 Mulai Antar Bunga ke Penerima Sekarang →</span>
                        </button>
                    </form>
                </div>

            <!-- Step 3: Sedang Dalam Pengantaran -->
            @elseif($delivery->status === 'on_delivery')
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">🛵</span>
                        <div>
                            <span class="font-bold text-xs block">Sedang Dalam Pengantaran ke Penerima</span>
                            <span class="text-[11px] text-emerald-700">Tujuan: {{ $delivery->order->recipient_name }} ({{ $delivery->order->delivery_district ?? 'Surabaya' }})</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-200 text-emerald-800 animate-pulse">
                        Live Tracking Aktif
                    </span>
                </div>
            @endif

            <!-- Live GPS Tracking Section -->
            @if(in_array($delivery->status, ['picked_up', 'on_delivery']))
                <div class="pt-4 border-t border-[#F3ECE4] space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-[#2A2626] text-xs flex items-center gap-1.5">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Pelacakan GPS Real-Time (Sinkron dengan Pembeli)</span>
                        </h3>
                        <span id="gpsStatusBadge" class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                            GPS Aktif
                        </span>
                    </div>

                    <p class="text-[11px] text-[#6B6565]">
                        Lokasi perangkat Anda dipancarkan ke halaman lacak pembeli secara real-time saat Anda memindahkan rangkaian bunga ini.
                    </p>

                    <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-[11px]">
                            <span class="text-[#9B9494] block text-[10px]">Koordinat Terakhir:</span>
                            <span id="currentCoordsDisplay" class="font-mono font-bold text-[#2A2626]">
                                {{ number_format($delivery->current_latitude ?? -7.257472, 6) }}, {{ number_format($delivery->current_longitude ?? 112.752088, 6) }}
                            </span>
                            <span id="lastUpdatedDisplay" class="text-[10px] text-[#6B6565] block">
                                Disinkronkan: {{ $delivery->last_location_updated_at ? $delivery->last_location_updated_at->format('H:i:s') : 'Baru saja' }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="button" onclick="sendCurrentLocation()"
                                    style="background-color: #5C6A57 !important; color: #ffffff !important;"
                                    class="flex-1 sm:flex-none py-2 px-3 rounded-lg text-xs font-semibold transition flex items-center justify-center gap-1 shadow-sm">
                                <span>📍 Update GPS Sekarang</span>
                            </button>
                            <button type="button" onclick="toggleSimulatedDrive()" id="btnSimulateDrive"
                                    style="background-color: #C5A059 !important; color: #ffffff !important;"
                                    class="flex-1 sm:flex-none py-2 px-3 rounded-lg text-xs font-semibold transition flex items-center justify-center gap-1 shadow-sm">
                                <span>🛵 Simulasi Jalan</span>
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    const deliveryLocationUrl = "{{ route('courier.deliveries.location', $delivery->id) }}";
                    const csrfToken = "{{ csrf_token() }}";
                    const storeLat = {{ (float) \App\Models\StoreSetting::get('store_latitude', '-7.257472') }};
                    const storeLng = {{ (float) \App\Models\StoreSetting::get('store_longitude', '112.752088') }};
                    const destLat = {{ (float) ($delivery->order->recipient_latitude ?? -7.257472) }};
                    const destLng = {{ (float) ($delivery->order->recipient_longitude ?? 112.752088) }};

                    let currLat = {{ (float) ($delivery->current_latitude ?? -7.257472) }};
                    let currLng = {{ (float) ($delivery->current_longitude ?? 112.752088) }};
                    let isSimulating = false;
                    let simulationInterval = null;

                    function updateLocationToServer(lat, lng) {
                        fetch(deliveryLocationUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ latitude: lat, longitude: lng })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                document.getElementById('currentCoordsDisplay').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                                document.getElementById('lastUpdatedDisplay').textContent = `Disinkronkan: ${data.last_updated}`;
                            }
                        })
                        .catch(err => console.error("Location update failed:", err));
                    }

                    function sendCurrentLocation() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                pos => {
                                    currLat = pos.coords.latitude;
                                    currLng = pos.coords.longitude;
                                    updateLocationToServer(currLat, currLng);
                                },
                                err => {
                                    alert("Gagal mengambil GPS perangkat. Menggunakan lokasi default.");
                                    updateLocationToServer(currLat, currLng);
                                },
                                { enableHighAccuracy: true }
                            );
                        } else {
                            updateLocationToServer(currLat, currLng);
                        }
                    }

                    // Auto-watch GPS position every 8 seconds if available
                    if (navigator.geolocation) {
                        navigator.geolocation.watchPosition(
                            pos => {
                                if (!isSimulating) {
                                    currLat = pos.coords.latitude;
                                    currLng = pos.coords.longitude;
                                    updateLocationToServer(currLat, currLng);
                                }
                            },
                            err => console.log("GPS watch notice:", err.message),
                            { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 }
                        );
                    }

                    function toggleSimulatedDrive() {
                        const btn = document.getElementById('btnSimulateDrive');
                        if (isSimulating) {
                            clearInterval(simulationInterval);
                            isSimulating = false;
                            btn.innerHTML = '<span>🛵 Simulasi Jalan</span>';
                            btn.setAttribute('style', 'background-color: #C5A059 !important; color: #ffffff !important;');
                        } else {
                            isSimulating = true;
                            btn.innerHTML = '<span>⏸ Stop Simulasi</span>';
                            btn.setAttribute('style', 'background-color: #8F3B44 !important; color: #ffffff !important;');

                            let step = 0;
                            const totalSteps = 20;

                            simulationInterval = setInterval(() => {
                                step++;
                                const progress = step / totalSteps;
                                const simLat = storeLat + (destLat - storeLat) * progress;
                                const simLng = storeLng + (destLng - storeLng) * progress;

                                currLat = simLat;
                                currLng = simLng;
                                updateLocationToServer(simLat, simLng);

                                if (step >= totalSteps) {
                                    clearInterval(simulationInterval);
                                    isSimulating = false;
                                    btn.innerHTML = '<span>🛵 Simulasi Jalan</span>';
                                    btn.setAttribute('style', 'background-color: #C5A059 !important; color: #ffffff !important;');
                                }
                            }, 3000);
                        }
                    }
                </script>
            @endif

            <!-- Upload Delivery Proof (HTML5 In-Page Live Camera Workflow) -->
            @if($delivery->status === 'on_delivery')
                <div class="pt-4 border-t border-[#F3ECE4] space-y-4">
                    <h3 class="font-bold text-[#2A2626] text-xs uppercase tracking-wider flex items-center gap-2">
                        <span>📷 Tahap Akhir: Ambil Foto Bukti Penerimaan (Kamera Langsung)</span>
                    </h3>
                <p class="text-[11px] text-[#6B6565] leading-relaxed">
                    <strong>Langkah 1:</strong> Tekan tombol <strong class="text-[#5C6A57]">"📷 Buka Kamera Langsung"</strong> di bawah untuk membidik foto serah terima secara real-time.<br>
                    <strong>Langkah 2:</strong> Setelah foto berhasil diambil, tekan tombol <strong class="text-[#5C6A57]">"✓ Selesaikan Pengantaran Bunga"</strong>.
                </p>

                <form id="proofForm" action="{{ route('courier.deliveries.proof', $delivery->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="handleProofSubmit(event)">
                    @csrf
                    <input type="hidden" name="type" value="photo">

                    <div class="p-4 bg-[#FAF8F5] rounded-2xl border border-[#D8BABF] space-y-3">
                        <label class="block text-[11px] font-bold text-[#2A2626]">
                            1. Bidik Foto Bukti Penerimaan dari Kamera:
                        </label>

                        <!-- Camera Action Controls -->
                        <div id="cameraControls" class="space-y-3 text-center">
                            <!-- Start Live Camera Button -->
                            <button type="button" id="startCameraBtn" onclick="openLiveCamera()"
                                    class="w-full py-3.5 px-4 bg-[#5C6A57] hover:bg-[#4A5745] text-white rounded-xl text-xs font-bold uppercase tracking-wider transition flex items-center justify-center gap-2 shadow-sm">
                                <span>📷 Buka Kamera Langsung</span>
                            </button>

                            <!-- Live Video View Box (Hidden until camera opened) -->
                            <div id="videoWrapper" class="hidden relative rounded-xl overflow-hidden border border-[#D8BABF] bg-black max-w-sm mx-auto shadow-md">
                                <video id="webcamVideo" autoplay playsinline muted class="w-full h-64 object-cover"></video>
                                <div class="absolute bottom-3 inset-x-0 flex items-center justify-center gap-3 px-3">
                                    <button type="button" onclick="captureWebcamPhoto()"
                                            class="py-2.5 px-5 bg-white text-[#2A2626] font-bold rounded-full text-xs shadow-lg flex items-center gap-2 hover:bg-[#FAF8F5] transition">
                                        <span class="w-3 h-3 rounded-full bg-red-600 animate-ping"></span>
                                        <span>📸 Ambil Foto Sekarang</span>
                                    </button>
                                    <button type="button" onclick="closeLiveCamera()"
                                            class="py-2.5 px-4 bg-gray-900/80 text-white rounded-full text-xs font-semibold backdrop-blur-xs">
                                        Batal
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden Canvas & Real File Input -->
                            <canvas id="photoCanvas" class="hidden"></canvas>
                            <input type="file" id="proofImageInput" name="proof_image" accept="image/*" required class="hidden" onchange="handleProofImagePreview(this)">
                        </div>

                        <!-- Live Image Preview Box -->
                        <div id="proofPreviewBox" class="hidden mt-3 p-3.5 bg-white rounded-xl border border-[#EADFD4] text-center space-y-2">
                            <span class="text-[10px] text-[#9B9494] font-semibold uppercase tracking-wider block">Foto Bukti Terpilih (Kamera):</span>
                            <img id="proofPreviewImg" src="" alt="Pratinjau Bukti" class="max-h-56 rounded-lg mx-auto object-contain border border-[#F3ECE4] shadow-xs">
                            <div class="flex items-center justify-center gap-3 pt-1">
                                <span class="text-[11px] text-emerald-700 font-bold block">
                                    ✓ Foto Berhasil Ditangkap!
                                </span>
                                <button type="button" onclick="openLiveCamera()" class="text-[11px] text-[#B85D66] font-bold underline hover:text-[#8F3B44]">
                                    📷 Foto Ulang
                                </button>
                            </div>
                        </div>

                        <!-- Fallback Option if Camera Browser Permission Blocked -->
                        <details class="pt-2 text-[10px] text-[#9B9494]">
                            <summary class="cursor-pointer hover:underline">Opsi Cadangan (Jika Kamera WebRTC Terblokir)</summary>
                            <div class="pt-2">
                                <input type="file" accept="image/*" capture="environment" onchange="handleFallbackFileInput(this)" class="w-full text-xs text-[#6B6565]">
                            </div>
                        </details>
                    </div>

                    <button type="submit" id="btnSubmitProof" disabled
                            style="background-color: #9B9494 !important; color: #ffffff !important;"
                            class="w-full py-3.5 px-4 rounded-xl font-bold uppercase tracking-wider transition opacity-60 cursor-not-allowed flex items-center justify-center gap-2 shadow-sm">
                        <span>🔒 Ambil Foto Bukti Kamera Dulu</span>
                    </button>
                </form>
            </div>

            <script>
                let mediaStream = null;

                async function openLiveCamera() {
                    const startBtn = document.getElementById('startCameraBtn');
                    const videoWrapper = document.getElementById('videoWrapper');
                    const video = document.getElementById('webcamVideo');

                    try {
                        const constraints = {
                            video: {
                                facingMode: { ideal: 'environment' },
                                width: { ideal: 1280 },
                                height: { ideal: 720 }
                            },
                            audio: false
                        };

                        mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
                        video.srcObject = mediaStream;
                        await video.play();

                        startBtn.classList.add('hidden');
                        videoWrapper.classList.remove('hidden');
                    } catch (err) {
                        alert("Gagal membuka kamera langsung. Pastikan Anda telah memberikan izin kamera pada browser HP Anda.");
                    }
                }

                function captureWebcamPhoto() {
                    const video = document.getElementById('webcamVideo');
                    const canvas = document.getElementById('photoCanvas');
                    const previewImg = document.getElementById('proofPreviewImg');
                    const previewBox = document.getElementById('proofPreviewBox');
                    const fileInput = document.getElementById('proofImageInput');
                    const submitBtn = document.getElementById('btnSubmitProof');

                    if (!video.videoWidth) return;

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
                    previewImg.src = dataUrl;
                    previewBox.classList.remove('hidden');

                    canvas.toBlob(blob => {
                        const file = new File([blob], `proof_${Date.now()}.jpg`, { type: 'image/jpeg' });
                        const container = new DataTransfer();
                        container.items.add(file);
                        fileInput.files = container.files;

                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                        submitBtn.setAttribute('style', 'background-color: #5C6A57 !important; color: #ffffff !important;');
                        submitBtn.innerHTML = '<span>✓ Selesaikan Pengantaran Bunga</span>';
                    }, 'image/jpeg', 0.85);

                    closeLiveCamera();
                }

                function closeLiveCamera() {
                    const videoWrapper = document.getElementById('videoWrapper');
                    const startBtn = document.getElementById('startCameraBtn');
                    const video = document.getElementById('webcamVideo');

                    if (mediaStream) {
                        mediaStream.getTracks().forEach(track => track.stop());
                        mediaStream = null;
                    }
                    video.srcObject = null;
                    videoWrapper.classList.add('hidden');
                    startBtn.classList.remove('hidden');
                }

                function handleFallbackFileInput(fallbackInput) {
                    const fileInput = document.getElementById('proofImageInput');
                    if (fallbackInput.files && fallbackInput.files[0]) {
                        fileInput.files = fallbackInput.files;
                        handleProofImagePreview(fileInput);
                    }
                }

                function handleProofImagePreview(input) {
                    const previewBox = document.getElementById('proofPreviewBox');
                    const previewImg = document.getElementById('proofPreviewImg');
                    const submitBtn = document.getElementById('btnSubmitProof');

                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            previewBox.classList.remove('hidden');

                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                            submitBtn.setAttribute('style', 'background-color: #5C6A57 !important; color: #ffffff !important;');
                            submitBtn.innerHTML = '<span>✓ Selesaikan Pengantaran Bunga</span>';
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function handleProofSubmit(e) {
                    const submitBtn = document.getElementById('btnSubmitProof');
                    if (submitBtn.disabled) {
                        e.preventDefault();
                        return false;
                    }
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span>⏳ Mengunggah Foto Bukti...</span>';
                    submitBtn.setAttribute('style', 'background-color: #5C6A57 !important; color: #ffffff !important; opacity: 0.85;');
                }
            </script>
        </div>
        @endif
    </div>
    @else
        <!-- Delivery Proof Display -->
        <div class="bg-white p-6 rounded-2xl border border-[#7B8B75] shadow-xs space-y-3 text-xs">
            <div class="flex items-center gap-2 text-[#5C6A57] font-bold">
                <span>✓</span> Pengantaran Selesai pada {{ $delivery->delivered_at?->format('d M Y, H:i') }} WIB
            </div>

            @if($delivery->proofs->isNotEmpty())
                <div class="space-y-2 pt-2">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-[#2A2626] block">Bukti Serah Terima:</span>
                        <span class="text-[10px] text-[#5C6A57] font-semibold">🔍 Klik foto untuk memperbesar</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($delivery->proofs as $prf)
                            @php $imgUrl = asset('storage/' . $prf->file_path); @endphp
                            <a href="{{ $imgUrl }}" target="_blank" onclick="openImageModal(event, '{{ $imgUrl }}')"
                               class="group relative block aspect-square rounded-xl overflow-hidden border border-[#7B8B75]/40 hover:border-[#5C6A57] transition shadow-2xs cursor-pointer">
                                <img src="{{ $imgUrl }}" alt="Bukti Pengantaran" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-[10px] font-semibold gap-1 p-2 text-center">
                                    <span>🔍 Buka Foto</span> ↗
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
