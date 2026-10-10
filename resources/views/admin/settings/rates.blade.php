@extends('layouts.admin')

@section('title', 'Tarif Ongkir — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Tarif Ongkos Kirim</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">
                Kelola nominal biaya pengiriman, biaya jarak tambahan, dan ambang batas program gratis ongkir toko.
            </p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    @include('admin.settings._tabs')

    <form action="{{ route('admin.settings.rates.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Tarif per Zona (7 cols) -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6 text-xs">
                <div class="border-b border-[#EADFD4] pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="font-serif text-xl text-[#2A2626]">Tarif Dasar Berdasarkan Zona</h2>
                        <span class="text-[11px] text-[#6B6565]">Biaya dasar pengantaran bunga untuk masing-masing zona</span>
                    </div>
                    <span class="px-2.5 py-1 bg-[#EEF2EB] text-[#5C6A57] font-bold rounded-lg text-[10px]">
                        Surabaya Area
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($zones as $zone)
                        <div class="p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="space-y-0.5">
                                <strong class="text-sm text-[#2A2626] block">{{ $zone->name }}</strong>
                                <span class="text-[11px] text-[#6B6565] block">
                                    Jarak: {{ $zone->min_distance_km }} - {{ $zone->max_distance_km }} KM dari toko Surabaya Pusat
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-[#6B6565]">Rp</span>
                                <input type="number" name="zone_fees[{{ $zone->id }}]" value="{{ (int)$zone->delivery_fee }}" required
                                       class="w-32 p-2 rounded-xl border border-[#D8BABF] bg-white text-sm font-bold text-[#B85D66] text-right">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-[#EADFD4] flex items-center justify-between">
                    <span class="text-[#6B6565] text-[11px]">Tarif akan langsung aktif di halaman checkout pelanggan.</span>
                    <button type="submit" class="py-2.5 px-6 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-bold uppercase tracking-wider transition text-xs shadow-xs">
                        Simpan Tarif Ongkir
                    </button>
                </div>
            </div>

            <!-- Kebijakan Gratis Ongkir & Extra KM (5 cols) -->
            <div class="lg:col-span-5 space-y-6 text-xs">
                
                <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
                    <h3 class="font-serif text-lg text-[#2A2626]">Program Gratis Ongkir</h3>
                    <p class="text-[11px] text-[#6B6565]">
                        Jika total belanja memenuhi syarat minimal, ongkir otomatis menjadi Rp 0 (Free Shipping).
                    </p>

                    <div>
                        <label class="block font-semibold text-[#2A2626] mb-1">Minimal Belanja Gratis Ongkir (Rp) *</label>
                        <input type="number" name="free_shipping_min" value="{{ (int)$freeShippingMin }}" required
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] font-bold text-sm text-[#5C6A57]">
                        <span class="text-[10px] text-[#9B9494] block mt-1">
                            Set nilai 0 jika ingin menonaktifkan program gratis ongkir.
                        </span>
                    </div>

                    <div class="pt-3 border-t border-[#EADFD4]">
                        <label class="block font-semibold text-[#2A2626] mb-1">Biaya Tambahan Per KM (Jarak Ekstra) (Rp) *</label>
                        <input type="number" name="extra_fee_per_km" value="{{ (int)$extraFeePerKm }}" required
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] font-bold text-sm text-[#2A2626]">
                        <span class="text-[10px] text-[#9B9494] block mt-1">
                            Digunakan apabila ada pesanan khusus yang melebihi batas zona standar.
                        </span>
                    </div>
                </div>

                <div class="p-4 bg-[#FAF8F5] rounded-2xl border border-[#EADFD4] space-y-2">
                    <h4 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px]">💡 Kebijakan Transparansi Ongkir</h4>
                    <p class="text-[11px] text-[#6B6565] leading-relaxed">
                        Desy Florist tidak menggunakan jarak garis lurus tanpa kalkulasi rute yang jelas. Jika koordinat Google Maps belum dimasukkan pengguna, tarif zona berdasarkan pilihan kecamatan Surabaya Pusat/Wilayah akan digunakan sebagai pedoman tarif pasti.
                    </p>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection
