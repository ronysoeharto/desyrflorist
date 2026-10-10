@extends('layouts.admin')

@section('title', 'Rincian Kurir — ' . $courier->user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <nav class="text-xs text-[#9B9494] mb-1">
            <a href="{{ route('admin.couriers.index') }}" class="hover:underline">← Kembali ke Daftar Kurir</a>
        </nav>
        <h1 class="font-serif text-3xl text-[#2A2626]">Rincian Mitra: {{ $courier->user->name }}</h1>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-6 text-xs">
        <div class="flex items-center justify-between pb-4 border-b border-[#F3ECE4]">
            <div class="flex items-center gap-4">
                <img src="{{ $courier->user->avatar_url }}" alt="{{ $courier->user->name }}" class="w-16 h-16 rounded-full object-cover border border-[#D8BABF]">
                <div>
                    <h2 class="font-bold text-base text-[#2A2626]">{{ $courier->user->name }}</h2>
                    <span class="text-[#6B6565] block">{{ $courier->user->email }} • {{ $courier->user->phone }}</span>
                    <span class="text-[10px] text-[#9B9494] block mt-0.5">Terdaftar sejak {{ $courier->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                    {{ $courier->status === 'approved' ? 'bg-green-100 text-green-700' : ($courier->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    Status: {{ $courier->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <span class="text-[10px] text-[#9B9494] block">Nomor KTP:</span>
                <span class="font-semibold text-sm text-[#2A2626] font-mono">{{ $courier->identity_number }}</span>
            </div>

            <div>
                <span class="text-[10px] text-[#9B9494] block">Tipe Kendaraan:</span>
                <span class="font-semibold text-sm text-[#2A2626] capitalize">{{ $courier->vehicle_type }}</span>
            </div>

            <div>
                <span class="text-[10px] text-[#9B9494] block">Merk / Seri Kendaraan:</span>
                <span class="font-semibold text-sm text-[#2A2626]">{{ $courier->vehicle_number }}</span>
            </div>

            <div>
                <span class="text-[10px] text-[#9B9494] block">Nomor Plat Polisi:</span>
                <span class="font-mono font-bold text-sm text-[#2A2626]">{{ $courier->plate_number }}</span>
            </div>

            <div class="sm:col-span-2">
                <span class="text-[10px] text-[#9B9494] block">Alamat Domisili:</span>
                <span class="text-[#6B6565]">{{ $courier->address }}</span>
            </div>
        </div>

        <!-- Verification Form -->
        <div class="pt-6 border-t border-[#F3ECE4] space-y-4">
            <h3 class="font-bold text-[#2A2626] uppercase tracking-wider text-[11px]">Ubah Status Verifikasi</h3>

            <form action="{{ route('admin.couriers.status', $courier->id) }}" method="POST" class="flex flex-wrap items-center gap-3">
                @csrf
                @method('PUT')

                <select name="status" class="p-2.5 rounded-xl border border-[#D8BABF] bg-white text-xs">
                    <option value="approved" {{ $courier->status === 'approved' ? 'selected' : '' }}>Setujui (Approved)</option>
                    <option value="pending" {{ $courier->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="rejected" {{ $courier->status === 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                    <option value="suspended" {{ $courier->status === 'suspended' ? 'selected' : '' }}>Tangguhkan (Suspended)</option>
                </select>

                <button type="submit" class="py-2.5 px-6 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-semibold transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
