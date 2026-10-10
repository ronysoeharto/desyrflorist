@extends('layouts.admin')

@section('title', 'Verifikasi Mitra Kurir — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Verifikasi & Mitra Kurir</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">Tinjau pendaftaran calon kurir dan kelola kurir aktif studio.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#FAF8F5] border border-[#EADFD4] text-[#C5A059]">
                {{ $pendingCount }} Menunggu Verifikasi
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#EEF2EB] text-[#5C6A57]">
                {{ $approvedCount }} Disetujui
            </span>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 text-xs font-semibold">
        <a href="{{ route('admin.couriers.index') }}"
           class="px-4 py-2 rounded-xl transition {{ empty($status) ? 'bg-[#B85D66] text-white' : 'bg-white text-[#6B6565] border border-[#EADFD4]' }}">
            Semua ({{ $couriers->total() }})
        </a>
        <a href="{{ route('admin.couriers.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-xl transition {{ $status === 'pending' ? 'bg-[#B85D66] text-white' : 'bg-white text-[#6B6565] border border-[#EADFD4]' }}">
            Perlu Verifikasi ({{ $pendingCount }})
        </a>
        <a href="{{ route('admin.couriers.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-xl transition {{ $status === 'approved' ? 'bg-[#B85D66] text-white' : 'bg-white text-[#6B6565] border border-[#EADFD4]' }}">
            Aktif Disetujui ({{ $approvedCount }})
        </a>
    </div>

    <!-- Couriers Table -->
    <div class="bg-white rounded-2xl border border-[#EADFD4] shadow-xs overflow-hidden">
        @if($couriers->isEmpty())
            <div class="p-8 text-center text-xs text-[#6B6565]">
                Tidak ada data mitra kurir pada filter ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF8F5] text-[#9B9494] uppercase tracking-wider text-[10px] border-b border-[#EADFD4]">
                        <tr>
                            <th class="p-4">Foto & Nama Kurir</th>
                            <th class="p-4">Kontak & NIK</th>
                            <th class="p-4">Kendaraan</th>
                            <th class="p-4">Nomor Plat</th>
                            <th class="p-4">Status Akun</th>
                            <th class="p-4 text-right">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3ECE4]">
                        @foreach($couriers as $prof)
                            <tr class="hover:bg-[#FAF8F5]/50 transition">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $prof->user?->avatar_url }}" alt="{{ $prof->user?->name }}"
                                             class="w-10 h-10 rounded-full object-cover border border-[#D8BABF]">
                                        <div>
                                            <a href="{{ route('admin.couriers.show', $prof->id) }}" class="font-bold text-[#2A2626] hover:text-[#B85D66]">
                                                {{ $prof->user?->name }}
                                            </a>
                                            <span class="text-[10px] text-[#9B9494] block">{{ $prof->user?->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="font-semibold text-[#2A2626] block">{{ $prof->user?->phone }}</span>
                                    <span class="text-[10px] text-[#9B9494] font-mono">NIK: {{ $prof->identity_number }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="capitalize font-semibold text-[#2A2626] block">{{ $prof->vehicle_type }}</span>
                                    <span class="text-[10px] text-[#6B6565]">{{ $prof->vehicle_number }}</span>
                                </td>
                                <td class="p-4 font-mono font-bold text-[#2A2626]">
                                    {{ $prof->plate_number }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                        {{ $prof->status === 'approved' ? 'bg-green-100 text-green-700' : ($prof->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $prof->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($prof->status === 'pending')
                                            <form action="{{ route('admin.couriers.status', $prof->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-3 py-1.5 bg-[#5C6A57] hover:bg-[#434F3F] text-white rounded-lg text-xs font-semibold">
                                                    ✓ Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.couriers.status', $prof->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="px-3 py-1.5 border border-red-300 text-red-600 hover:bg-red-50 rounded-lg text-xs font-semibold">
                                                    Tolak
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.couriers.show', $prof->id) }}"
                                               class="px-3 py-1.5 border border-[#D8BABF] hover:bg-[#FAF8F5] rounded-lg text-xs font-semibold text-[#2A2626]">
                                                Rincian
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#EADFD4]">
                {{ $couriers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
