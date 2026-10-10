@extends('layouts.admin')

@section('title', 'Pengaturan Toko — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Pengaturan Studio Florist</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">Konfigurasi informasi studio, kontak WhatsApp, jam buka, dan kebijakan gratis ongkir.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Store Settings Form (7 cols) -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6 text-xs">
            <h2 class="font-serif text-xl text-[#2A2626]">Informasi Bisnis</h2>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Nama Toko / Studio</label>
                    <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'Desy Florist' }}" required
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Tagline</label>
                    <input type="text" name="store_tagline" value="{{ $settings['store_tagline'] ?? 'Artisan Floral Studio & Same Day Delivery' }}"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Alamat Studio Fisik</label>
                    <textarea name="store_address" rows="2" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">{{ $settings['store_address'] ?? 'Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan' }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Nomor WhatsApp Pesanan (Tanpa +)</label>
                        <input type="text" name="store_whatsapp" value="{{ $settings['store_whatsapp'] ?? '6281234567890' }}" required
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>

                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Email Support</label>
                        <input type="email" name="store_email" value="{{ $settings['store_email'] ?? 'hello@desyflorist.com' }}" required
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Jam Buka Studio</label>
                        <input type="text" name="store_open_hours" value="{{ $settings['store_open_hours'] ?? 'Senin - Minggu: 07.30 - 21.00 WIB' }}"
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>

                    <div>
                        <label class="block font-medium mb-1 text-[#2A2626]">Batas Order Same Day</label>
                        <input type="text" name="same_day_cutoff" value="{{ $settings['same_day_cutoff'] ?? '16:00' }}"
                               class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#2A2626]">Minimal Belanja Gratis Ongkir (Rp)</label>
                    <input type="number" name="free_shipping_min" value="{{ $settings['free_shipping_min'] ?? '0' }}"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>

                <div class="pt-4 border-t border-[#EADFD4]">
                    <button type="submit" class="py-3 px-6 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider transition shadow-sm">
                        Simpan Pengaturan Toko
                    </button>
                </div>
            </form>
        </div>

        <!-- Delivery Zones Overview (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                <h3 class="font-serif text-lg text-[#2A2626]">Zona Pengiriman Terdaftar</h3>
                <p class="text-[#6B6565]">Biaya pengiriman dihitung otomatis berdasarkan zona pemesan.</p>

                <div class="space-y-3">
                    @foreach($deliveryZones as $zone)
                        <div class="p-3.5 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-1">
                            <div class="flex items-center justify-between">
                                <strong class="text-[#2A2626]">{{ $zone->name }}</strong>
                                <span class="font-serif font-bold text-[#B85D66]">Rp {{ number_format($zone->delivery_fee, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[11px] text-[#6B6565]">Cakupan: {{ implode(', ', $zone->areas) }}</p>
                            <span class="text-[10px] text-[#9B9494] block">Min. Pesan: Rp {{ number_format($zone->min_order, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
