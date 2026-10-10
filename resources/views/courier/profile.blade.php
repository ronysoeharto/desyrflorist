@extends('layouts.courier')

@section('title', 'Profil Kendaraan Kurir — Desy Florist')

@section('content')
<div class="space-y-6">
    <h1 class="font-serif text-2xl text-[#2A2626]">Profil Mitra Kurir</h1>

    <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
        <div class="flex items-center gap-4 pb-4 border-b border-[#F3ECE4]">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border border-[#D8BABF]">
            <div>
                <h2 class="font-bold text-sm text-[#2A2626]">{{ $user->name }}</h2>
                <span class="text-[#6B6565] block">{{ $user->email }}</span>
                <span class="text-[#5C6A57] font-semibold block mt-0.5">{{ $user->phone }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <span class="text-[10px] text-[#9B9494] block">Nomor KTP / Identitas:</span>
                <span class="font-semibold text-[#2A2626]">{{ $profile->identity_number ?? '-' }}</span>
            </div>

            <div>
                <span class="text-[10px] text-[#9B9494] block">Tipe Kendaraan:</span>
                <span class="font-semibold text-[#2A2626] capitalize">{{ $profile->vehicle_type ?? '-' }}</span>
            </div>

            <div>
                <span class="text-[10px] text-[#9B9494] block">Merk & Model:</span>
                <span class="font-semibold text-[#2A2626]">{{ $profile->vehicle_number ?? '-' }}</span>
            </div>

            <div>
                <span class="text-[10px] text-[#9B9494] block">Nomor Plat Polisi:</span>
                <span class="font-mono font-bold text-[#2A2626]">{{ $profile->plate_number ?? '-' }}</span>
            </div>

            <div class="sm:col-span-2">
                <span class="text-[10px] text-[#9B9494] block">Alamat Domisili:</span>
                <span class="text-[#6B6565]">{{ $profile->address ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
