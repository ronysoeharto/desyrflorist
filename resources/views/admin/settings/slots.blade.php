@extends('layouts.admin')

@section('title', 'Jadwal dan Slot Pengiriman — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Jadwal & Slot Pengiriman Bunga</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">
                Konfigurasi pilihan rentang jam pengantaran kurir dan kapasitas kuota pesanan tiap slot waktu.
            </p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    @include('admin.settings._tabs')

    <form action="{{ route('admin.settings.slots.update') }}" method="POST" id="slotsForm">
        @csrf

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6 text-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[#EADFD4] pb-4">
                <div>
                    <h2 class="font-serif text-xl text-[#2A2626]">Daftar Slot Jam Pengiriman Aktif</h2>
                    <span class="text-[11px] text-[#6B6565]">Pilihan waktu yang akan muncul pada formulir pemesanan pelanggan</span>
                </div>
                <button type="button" onclick="addNewSlotRow()"
                        class="px-3.5 py-2 rounded-xl bg-[#FAF8F5] border border-[#B85D66] text-[#B85D66] hover:bg-[#B85D66] hover:text-white transition font-semibold flex items-center gap-1.5 self-start sm:self-auto">
                    <span>+ Tambah Slot Waktu Baru</span>
                </button>
            </div>

            <div class="space-y-3" id="slotsContainer">
                @foreach($slots as $index => $slot)
                    <div class="slot-row p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                            <div>
                                <label class="block text-[10px] font-bold text-[#6B6565] uppercase mb-1">Nama Slot *</label>
                                <input type="text" name="slots[{{ $index }}][name]" value="{{ $slot['name'] }}" required
                                       class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white font-semibold text-xs">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-[#6B6565] uppercase mb-1">Rentang Jam Pengantaran *</label>
                                <input type="text" name="slots[{{ $index }}][time]" value="{{ $slot['time'] }}" required
                                       placeholder="Contoh: 08:00 - 11:00"
                                       class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white text-xs">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-[#6B6565] uppercase mb-1">Kuota Pesanan Maksimal *</label>
                                <input type="number" name="slots[{{ $index }}][quota]" value="{{ $slot['quota'] ?? 15 }}" required min="1"
                                       class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white font-bold text-xs text-[#B85D66]">
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-3 pt-2 md:pt-0 border-t md:border-t-0 border-[#EADFD4]">
                            <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-[#2A2626]">
                                <input type="checkbox" name="slots[{{ $index }}][is_active]" value="1" {{ ($slot['is_active'] ?? true) ? 'checked' : '' }}
                                       class="rounded text-[#B85D66] focus:ring-[#B85D66]">
                                <span>Aktifkan</span>
                            </label>

                            <button type="button" onclick="this.closest('.slot-row').remove()"
                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus slot ini">
                                🗑️
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-[#EADFD4] flex items-center justify-between">
                <span class="text-[11px] text-[#6B6565]">* Slot waktu di atas otomatis disinkronkan dengan halaman checkout.</span>
                <button type="submit" class="py-2.5 px-6 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-bold uppercase tracking-wider transition text-xs shadow-xs">
                    Simpan Perubahan Slot Pengiriman
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function addNewSlotRow() {
        const container = document.getElementById('slotsContainer');
        const index = container.querySelectorAll('.slot-row').length;

        const newRow = document.createElement('div');
        newRow.className = 'slot-row p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] flex flex-col md:flex-row md:items-center justify-between gap-4';
        newRow.innerHTML = `
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                <div>
                    <label class="block text-[10px] font-bold text-[#6B6565] uppercase mb-1">Nama Slot *</label>
                    <input type="text" name="slots[${index}][name]" value="Slot Tambahan" required
                           class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white font-semibold text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-[#6B6565] uppercase mb-1">Rentang Jam Pengantaran *</label>
                    <input type="text" name="slots[${index}][time]" value="19:00 - 22:00" required
                           class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-[#6B6565] uppercase mb-1">Kuota Pesanan Maksimal *</label>
                    <input type="number" name="slots[${index}][quota]" value="10" required min="1"
                           class="w-full p-2 rounded-lg border border-[#D8BABF] bg-white font-bold text-xs text-[#B85D66]">
                </div>
            </div>
            <div class="flex items-center justify-between md:justify-end gap-3 pt-2 md:pt-0 border-t md:border-t-0 border-[#EADFD4]">
                <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-[#2A2626]">
                    <input type="checkbox" name="slots[${index}][is_active]" value="1" checked
                           class="rounded text-[#B85D66] focus:ring-[#B85D66]">
                    <span>Aktifkan</span>
                </label>
                <button type="button" onclick="this.closest('.slot-row').remove()"
                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition">
                    🗑️
                </button>
            </div>
        `;
        container.appendChild(newRow);
    }
</script>
@endsection
