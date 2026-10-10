@extends('layouts.admin')

@section('title', 'Jam Operasional — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Jam Operasional & Same-Day Cutoff</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">
                Atur hari buka studio florist, batas waktu pemesanan kirim di hari yang sama, dan lead time persiapan bunga.
            </p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    @include('admin.settings._tabs')

    <form action="{{ route('admin.settings.hours.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-8 bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6 text-xs">
                <div class="border-b border-[#EADFD4] pb-3">
                    <h2 class="font-serif text-xl text-[#2A2626]">Jadwal Kerja Studio Florist</h2>
                    <span class="text-[11px] text-[#6B6565]">Mengatur batasan waktu saat checkout pesanan pelanggan</span>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Hari Kerja Operasional *</label>
                            <input type="text" name="operating_days" value="{{ old('operating_days', $settings['operating_days'] ?? 'Senin - Minggu') }}" required
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                            <span class="text-[10px] text-[#9B9494]">Contoh: Senin - Minggu atau Setiap Hari</span>
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Jam Buka Studio Bunga *</label>
                            <input type="text" name="operating_hours" value="{{ old('operating_hours', $settings['operating_hours'] ?? '08:00 - 20:00 WIB') }}" required
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                            <span class="text-[10px] text-[#9B9494]">Contoh: 08:00 - 20:00 WIB</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Batas Waktu Order Same-Day (Cutoff) *</label>
                            <input type="text" name="same_day_cutoff" value="{{ old('same_day_cutoff', $settings['same_day_cutoff'] ?? '16:00') }}" required
                                   placeholder="16:00"
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] font-bold text-[#B85D66]">
                            <span class="text-[10px] text-[#9B9494]">Pesanan setelah jam ini akan dialihkan ke slot besok.</span>
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Estimasi Minimal Persiapan Rangkaian (Jam)</label>
                            <input type="number" step="0.5" name="preparation_lead_time_hours" value="{{ old('preparation_lead_time_hours', $settings['preparation_lead_time_hours'] ?? '2') }}"
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                            <span class="text-[10px] text-[#9B9494]">Lead time florist menyiapkan bunga sebelum diserahkan ke kurir.</span>
                        </div>
                    </div>

                    <div class="p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] flex items-center justify-between">
                        <div>
                            <strong class="text-[#2A2626] block">Status Penerimaan Pesanan Baru</strong>
                            <span class="text-[11px] text-[#6B6565]">Jika tidak aktif, pelanggan akan diberi notifikasi bahwa studio sedang rehat/libur.</span>
                        </div>
                        <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-xs text-[#5C6A57]">
                            <input type="checkbox" name="store_is_open" value="1" {{ ($settings['store_is_open'] ?? '1') == '1' ? 'checked' : '' }}
                                   class="rounded text-[#5C6A57] focus:ring-[#5C6A57] w-4 h-4">
                            <span>Toko Buka Menerima Pesanan</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EADFD4] flex items-center justify-end">
                    <button type="submit" class="py-2.5 px-6 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-bold uppercase tracking-wider transition text-xs shadow-xs">
                        Simpan Jam Operasional
                    </button>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-4 text-xs">
                <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-3">
                    <h3 class="font-serif text-base text-[#2A2626]">Ketentuan Same Day Delivery</h3>
                    <p class="text-[#6B6565] leading-relaxed text-[11px]">
                        Rangkaian bunga segar memerlukan sentuhan artistik florist berpengalaman. Batas pemesanan Same Day di jam <strong>{{ $settings['same_day_cutoff'] ?? '16:00' }} WIB</strong> menjamin bunga dapat dirangkai dan dikirim dengan kualitas prima sebelum malam.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
