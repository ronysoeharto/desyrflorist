@extends('layouts.admin')

@section('title', 'Zona Pengiriman & Wilayah Surabaya — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Sistem Zona & Wilayah Pengiriman</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">
                Konfigurasi rentang jarak km, tarif per zona dari toko Surabaya Pusat, serta aktivasi per kecamatan di Kota Surabaya.
            </p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    @include('admin.settings._tabs')

    <form action="{{ route('admin.settings.zones.update') }}" method="POST" id="zonesForm">
        @csrf

        <div class="space-y-8">
            
            <!-- SECTION 1: 3 ZONA PENGIRIMAN FLEKSIBEL -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[#EADFD4] pb-4">
                    <div>
                        <h2 class="font-serif text-xl text-[#2A2626]">Rentang Jarak & Tarif 3 Zona Fleksibel</h2>
                        <p class="text-xs text-[#6B6565]">
                            Dihitung otomatis dari koordinat toko Desy Florist di Surabaya Pusat menuju koordinat pemesan.
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-[#FAF8F5] border border-[#EADFD4] text-xs font-semibold text-[#2A2626]">
                        Titik Awal: Surabaya Pusat
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach($zones as $index => $zone)
                        <div class="p-5 rounded-2xl border border-[#EADFD4] bg-[#FAF8F5]/50 hover:border-[#B85D66] transition space-y-4">
                            <input type="hidden" name="zones[{{ $index }}][id]" value="{{ $zone->id }}">

                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-md bg-[#B85D66] text-white text-[10px] font-bold uppercase tracking-wider">
                                    Zona {{ $index + 1 }}
                                </span>
                                <label class="inline-flex items-center gap-1.5 text-xs cursor-pointer">
                                    <input type="checkbox" name="zones[{{ $index }}][status]" value="1" {{ $zone->status ? 'checked' : '' }}
                                           class="rounded text-[#B85D66] focus:ring-[#B85D66]">
                                    <span class="font-semibold text-[#2A2626]">Aktif</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-[#2A2626] mb-1">Nama Label Zona *</label>
                                <input type="text" name="zones[{{ $index }}][name]" value="{{ $zone->name }}" required
                                       class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white text-xs font-semibold">
                            </div>

                            <!-- Rentang Jarak KM -->
                            <div class="grid grid-cols-2 gap-2 bg-white p-3 rounded-xl border border-[#EADFD4]">
                                <div>
                                    <label class="block text-[10px] font-bold text-[#6B6565] uppercase">Min Jarak (KM)</label>
                                    <input type="number" step="0.1" name="zones[{{ $index }}][min_distance_km]" value="{{ $zone->min_distance_km }}" required
                                           class="w-full p-1.5 rounded-lg border border-[#D8BABF] text-xs font-bold text-[#2A2626]">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-[#6B6565] uppercase">Max Jarak (KM)</label>
                                    <input type="number" step="0.1" name="zones[{{ $index }}][max_distance_km]" value="{{ $zone->max_distance_km }}" required
                                           class="w-full p-1.5 rounded-lg border border-[#D8BABF] text-xs font-bold text-[#2A2626]">
                                </div>
                            </div>

                            <!-- Tarif & Min Order -->
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#2A2626] mb-1">Tarif Ongkir (Rp) *</label>
                                    <input type="number" name="zones[{{ $index }}][delivery_fee]" value="{{ (int)$zone->delivery_fee }}" required
                                           class="w-full p-2 rounded-xl border border-[#D8BABF] bg-white text-xs font-bold text-[#B85D66]">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#2A2626] mb-1">Min. Belanja (Rp)</label>
                                    <input type="number" name="zones[{{ $index }}][min_order]" value="{{ (int)$zone->min_order }}" required
                                           class="w-full p-2 rounded-xl border border-[#D8BABF] bg-white text-xs">
                                </div>
                            </div>

                            <div class="text-[10px] text-[#9B9494]">
                                📍 Cakupan Referensi: {{ implode(', ', array_slice($zone->areas ?? [], 0, 4)) }}...
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Di Luar Area Layanan Config -->
                <div class="p-4 bg-[#F8E8EA]/40 rounded-xl border border-[#B85D66]/30 space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <strong class="text-[#8F3B44] font-semibold flex items-center gap-1.5">
                            <span>🚫</span> Ketentuan di Luar Area Layanan Florist
                        </strong>
                        <div class="flex items-center gap-2">
                            <span class="text-[#6B6565]">Batas Radius Maksimal:</span>
                            <input type="number" step="1" name="max_delivery_distance_km" value="{{ $maxDistanceKm }}" required
                                   class="w-20 p-1.5 rounded-lg border border-[#D8BABF] bg-white font-bold text-center">
                            <span class="font-bold">KM</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-medium text-[#2A2626] mb-1">Pesan Penolakan / Notifikasi untuk Pelanggan di Luar Layanan:</label>
                        <input type="text" name="unserviced_message" value="{{ $unservicedMessage }}" required
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] bg-white text-xs text-[#2A2626]">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: AKTIVASI KECAMATAN SURABAYA (OPERASIONAL KEMAMPUAN TOKO) -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#EADFD4] pb-4">
                    <div>
                        <h2 class="font-serif text-xl text-[#2A2626]">Manajemen Wilayah Kecamatan Surabaya</h2>
                        <p class="text-xs text-[#6B6565] mt-0.5">
                            Admin dapat mengaktifkan atau menonaktifkan setiap kecamatan berdasarkan kapasitas operasional harian toko.
                        </p>
                    </div>

                    <!-- Quick Bulk Buttons -->
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="selectCenterOnly()"
                                class="px-3 py-1.5 bg-[#FAF8F5] border border-[#EADFD4] hover:border-[#B85D66] rounded-xl text-xs font-semibold text-[#2A2626] transition">
                            Hanya Surabaya Pusat
                        </button>
                        <button type="button" onclick="selectAllDistricts(true)"
                                class="px-3 py-1.5 bg-[#EEF2EB] border border-[#7B8B75]/30 text-[#5C6A57] rounded-xl text-xs font-bold hover:bg-[#5C6A57] hover:text-white transition">
                            Aktifkan Semua
                        </button>
                        <button type="button" onclick="selectAllDistricts(false)"
                                class="px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 rounded-xl text-xs font-semibold hover:bg-red-100 transition">
                            Matikan Semua
                        </button>
                    </div>
                </div>

                <!-- 31 Kecamatan Grid by Region -->
                <div class="space-y-6">
                    @foreach($districtsByRegion as $regionName => $districts)
                        <div class="p-4 bg-[#FAF8F5]/80 rounded-2xl border border-[#EADFD4] space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="font-serif text-sm font-bold text-[#2A2626] flex items-center gap-2">
                                    <span>📍</span>
                                    <span>{{ $regionName }}</span>
                                    <span class="text-[10px] font-sans font-normal text-[#6B6565]">({{ count($districts) }} Kecamatan)</span>
                                </h3>
                                <button type="button" onclick="toggleRegionDistricts('{{ Str::slug($regionName) }}')"
                                        class="text-[11px] text-[#B85D66] font-semibold hover:underline">
                                    Toggle Wilayah Ini
                                </button>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                                @foreach($districts as $district)
                                    @php $isActive = in_array($district, $enabledDistricts); @endphp
                                    <label class="p-2.5 rounded-xl border transition flex items-center gap-2.5 cursor-pointer text-xs
                                                 {{ $isActive ? 'bg-white border-[#B85D66] shadow-2xs font-semibold text-[#2A2626]' : 'bg-gray-50 border-gray-200 text-[#9B9494]' }}
                                                 district-card-{{ Str::slug($regionName) }}">
                                        <input type="checkbox" name="enabled_districts[]" value="{{ $district }}"
                                               {{ $isActive ? 'checked' : '' }}
                                               class="district-checkbox rounded text-[#B85D66] focus:ring-[#B85D66]"
                                               onchange="onDistrictToggle(this)">
                                        <span>{{ $district }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-[#EADFD4] flex items-center justify-between">
                    <span class="text-xs text-[#6B6565]">
                        * Kecamatan yang dinonaktifkan tidak akan dapat dipilih atau akan ditolak pada proses checkout.
                    </span>
                    <button type="submit" class="py-3 px-8 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-bold uppercase tracking-wider transition shadow-sm text-xs">
                        Simpan Konfigurasi Zona & Wilayah
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>

<script>
    function onDistrictToggle(checkbox) {
        const label = checkbox.closest('label');
        if (checkbox.checked) {
            label.classList.remove('bg-gray-50', 'border-gray-200', 'text-[#9B9494]');
            label.classList.add('bg-white', 'border-[#B85D66]', 'shadow-2xs', 'font-semibold', 'text-[#2A2626]');
        } else {
            label.classList.remove('bg-white', 'border-[#B85D66]', 'shadow-2xs', 'font-semibold', 'text-[#2A2626]');
            label.classList.add('bg-gray-50', 'border-gray-200', 'text-[#9B9494]');
        }
    }

    function selectAllDistricts(status) {
        document.querySelectorAll('.district-checkbox').forEach(cb => {
            cb.checked = status;
            onDistrictToggle(cb);
        });
    }

    function selectCenterOnly() {
        const centerDistricts = ['Tegalsari', 'Genteng', 'Bubutan', 'Simokerto', 'Gubeng', 'Wonokromo', 'Sawahan'];
        document.querySelectorAll('.district-checkbox').forEach(cb => {
            cb.checked = centerDistricts.includes(cb.value);
            onDistrictToggle(cb);
        });
    }

    function toggleRegionDistricts(regionSlug) {
        const checkboxes = document.querySelectorAll(`.district-card-${regionSlug} .district-checkbox`);
        if (checkboxes.length === 0) return;
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => {
            cb.checked = !allChecked;
            onDistrictToggle(cb);
        });
    }
</script>
@endsection
