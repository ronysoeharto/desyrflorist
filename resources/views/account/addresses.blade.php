@extends('layouts.app')

@section('title', 'Buku Alamat — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <a href="{{ route('account.dashboard') }}" class="hover:text-[#B85D66]">Akun</a>
            <span>/</span>
            <span class="text-[#2A2626]">Buku Alamat</span>
        </nav>
        <h1 class="font-serif text-3xl text-[#2A2626] font-normal">Buku Alamat Pengiriman</h1>
    </div>
</div>

<x-account-nav />

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 sm:py-8 space-y-8">
    
    <!-- Addresses List -->
    <div class="space-y-4">
        <h2 class="font-serif text-xl text-[#2A2626]">Alamat Tersimpan</h2>

        @if($addresses->isEmpty())
            <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] text-center text-xs text-[#6B6565]">
                Belum ada alamat tersimpan. Tambahkan alamat baru di formulir bawah ini untuk mempermudah checkout.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($addresses as $addr)
                    <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-2 relative text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-[#2A2626]">{{ $addr->label }}</span>
                            @if($addr->is_default)
                                <span class="text-[10px] bg-[#EEF2EB] text-[#5C6A57] font-bold px-2 py-0.5 rounded-full">Utama</span>
                            @endif
                        </div>
                        <p class="font-medium text-[#2A2626]">{{ $addr->recipient_name }} ({{ $addr->phone }})</p>
                        <p class="text-[#6B6565] leading-relaxed">{{ $addr->address }}, {{ $addr->district }}, {{ $addr->city }} {{ $addr->postal_code }}</p>

                        <div class="pt-2 flex items-center justify-end">
                            <form action="{{ route('account.addresses.delete', $addr->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus alamat ini?')" class="text-xs text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Add New Address Form -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4">
        <h3 class="font-serif text-xl text-[#2A2626]">Tambah Alamat Baru</h3>

        <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-medium mb-1">Label Alamat (Contoh: Rumah / Kantor)</label>
                    <input type="text" name="label" required placeholder="Rumah / Kantor / Apartemen" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>
                <div>
                    <label class="block font-medium mb-1">Nama Penerima</label>
                    <input type="text" name="recipient_name" required placeholder="Nama Lengkap" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>
                <div>
                    <label class="block font-medium mb-1">Nomor WhatsApp / HP</label>
                    <input type="text" name="phone" required placeholder="08123456789" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>
            </div>

            <div>
                <label class="block font-medium mb-1">Alamat Lengkap</label>
                <textarea name="address" rows="2" required placeholder="Nama jalan, nomor rumah/gedung, RT/RW" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-medium mb-1">Kota</label>
                    <input type="text" name="city" required placeholder="Jakarta Selatan" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>
                <div>
                    <label class="block font-medium mb-1">Kecamatan</label>
                    <input type="text" name="district" required placeholder="Kebayoran Baru" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>
                <div>
                    <label class="block font-medium mb-1">Kode Pos (Opsional)</label>
                    <input type="text" name="postal_code" placeholder="12190" class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66]">
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_default" name="is_default" value="1" class="rounded text-[#B85D66] focus:ring-[#B85D66]">
                <label for="is_default" class="ml-2">Jadikan sebagai alamat pengiriman utama</label>
            </div>

            <button type="submit" class="py-3 px-6 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold uppercase tracking-wider text-xs transition shadow-sm">
                Simpan Alamat
            </button>
        </form>
    </div>

</div>
@endsection
