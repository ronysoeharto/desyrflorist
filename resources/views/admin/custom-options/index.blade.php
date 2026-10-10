@extends('layouts.admin')

@section('title', 'Kustomisasi Buket — Admin Desy Florist')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-serif text-2xl text-[#2A2626]">Opsi Kustomisasi Buket</h1>
            <p class="text-xs text-[#6B6565] mt-1">Kelola bunga, kertas pembungkus, pita, dan aksesoris untuk custom bouquet.</p>
        </div>
        <a href="{{ route('admin.custom-options.create') }}" class="bg-[#B85D66] hover:bg-[#8F3B44] text-white px-4 py-2 rounded-xl text-xs font-semibold tracking-wider transition">
            + Tambah Opsi
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-[#EADFD4] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FAF8F5] border-b border-[#EADFD4] text-xs uppercase tracking-wider text-[#9B9494]">
                        <th class="p-4 font-medium">Gambar</th>
                        <th class="p-4 font-medium">Nama</th>
                        <th class="p-4 font-medium">Tipe</th>
                        <th class="p-4 font-medium">Harga</th>
                        <th class="p-4 font-medium">Status</th>
                        <th class="p-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EADFD4]/60 text-sm">
                    @forelse($options as $opt)
                        <tr class="hover:bg-[#FAF8F5]/50 transition">
                            <td class="p-4">
                                @if($opt->image)
                                    <img src="{{ $opt->image }}" alt="{{ $opt->name }}" class="w-12 h-12 object-cover rounded-lg border border-[#EADFD4]">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-400">No Img</div>
                                @endif
                            </td>
                            <td class="p-4 font-medium text-[#2A2626]">{{ $opt->name }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full 
                                    @if($opt->type == 'flower') bg-pink-100 text-pink-700
                                    @elseif($opt->type == 'wrapping') bg-amber-100 text-amber-700
                                    @elseif($opt->type == 'ribbon') bg-purple-100 text-purple-700
                                    @else bg-blue-100 text-blue-700 @endif
                                ">
                                    {{ ucfirst($opt->type) }}
                                </span>
                            </td>
                            <td class="p-4">Rp {{ number_format($opt->price, 0, ',', '.') }}</td>
                            <td class="p-4">
                                @if($opt->status)
                                    <span class="text-green-600 bg-green-50 px-2.5 py-1 text-[10px] rounded-full font-semibold">Aktif</span>
                                @else
                                    <span class="text-red-600 bg-red-50 px-2.5 py-1 text-[10px] rounded-full font-semibold">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.custom-options.edit', $opt->id) }}" class="text-[#6B6565] hover:text-[#B85D66] text-xs font-medium">Edit</a>
                                <form action="{{ route('admin.custom-options.destroy', $opt->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus opsi ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-[#9B9494] text-sm">Belum ada opsi kustomisasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
