@extends('layouts.app')

@section('title', 'Pengaturan Profil — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <a href="{{ route('account.dashboard') }}" class="hover:text-[#B85D66]">Akun</a>
            <span>/</span>
            <span class="text-[#2A2626]">Profil</span>
        </nav>
        <h1 class="font-serif text-3xl text-[#2A2626] font-normal">Pengaturan Profil</h1>
    </div>
</div>

<x-account-nav />

<div class="max-w-2xl mx-auto px-4 sm:px-6 py-4 sm:py-8">
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6">
        
        <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium text-[#2A2626] mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-sm">
            </div>

            <div>
                <label class="block font-medium text-[#2A2626] mb-1">Alamat Email (Tidak dapat diubah)</label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full p-2.5 rounded-xl border border-[#EADFD4] bg-gray-50 text-gray-500 text-sm">
            </div>

            <div>
                <label class="block font-medium text-[#2A2626] mb-1">Nomor WhatsApp / HP</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-sm">
            </div>

            <div class="pt-4 border-t border-[#F3ECE4] space-y-4">
                <h3 class="font-serif text-base text-[#2A2626]">Ubah Kata Sandi (Opsional)</h3>
                
                <div>
                    <label class="block font-medium text-[#2A2626] mb-1">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-sm">
                </div>

                <div>
                    <label class="block font-medium text-[#2A2626] mb-1">Kata Sandi Baru</label>
                    <input type="password" name="password"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-sm">
                </div>

                <div>
                    <label class="block font-medium text-[#2A2626] mb-1">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation"
                           class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-sm">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="py-3 px-6 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
