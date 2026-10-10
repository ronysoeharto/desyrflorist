@extends('layouts.courier')

@section('title', 'Tugas Pengantaran — Desy Florist')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="font-serif text-2xl text-[#2A2626]">Pengantaran Aktif</h1>
        <span class="text-xs text-[#6B6565]">{{ $deliveries->count() }} pesanan</span>
    </div>

    @if($deliveries->isEmpty())
        <div class="bg-white p-8 rounded-2xl border border-[#EADFD4] text-center text-xs text-[#6B6565]">
            Tidak ada tugas pengantaran aktif saat ini.
        </div>
    @else
        <div class="space-y-4">
            @foreach($deliveries as $del)
                <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-[#F3ECE4]">
                        <div>
                            <span class="font-mono font-bold text-sm text-[#2A2626]">{{ $del->order->order_number }}</span>
                            @if($del->order->tracking_number)
                                <span class="text-[10px] font-mono text-[#5C6A57] bg-[#EEF2EB] px-1.5 py-0.5 rounded font-bold ml-1.5 border border-[#7B8B75]/20">
                                    Resi: {{ $del->order->tracking_number }}
                                </span>
                            @endif
                            <span class="text-[11px] text-[#6B6565] block">Slot: {{ $del->order->delivery_slot }} • {{ $del->order->delivery_date->format('d M Y') }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $del->status === 'on_delivery' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $del->status_label }}
                        </span>
                    </div>

                    <div class="space-y-1.5">
                        <p><strong>Penerima:</strong> {{ $del->order->recipient_name }} ({{ $del->order->recipient_phone }})</p>
                        <p><strong>Alamat:</strong> {{ $del->order->delivery_address }}</p>
                        @if($del->order->delivery_notes)
                            <p class="text-[11px] text-[#6B6565]"><strong>Catatan:</strong> {{ $del->order->delivery_notes }}</p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-[#F3ECE4]">
                        <a href="{{ $del->order->google_maps_url }}" target="_blank"
                           class="text-[#5C6A57] hover:underline flex items-center gap-1 font-semibold">
                            🗺️ Peta Lokasi
                        </a>

                        <a href="{{ route('courier.deliveries.show', $del->id) }}"
                           class="px-4 py-2 text-white rounded-xl font-semibold transition shadow-2xs {{ $del->status === 'assigned' ? 'bg-[#C5A059] hover:bg-[#A37E36]' : ($del->status === 'picked_up' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-[#5C6A57] hover:bg-[#434F3F]') }}">
                            @if($del->status === 'assigned')
                                🌸 Ambil Bunga di Toko →
                            @elseif($del->status === 'picked_up')
                                🛵 Mulai Antar Sekarang →
                            @elseif($del->status === 'on_delivery')
                                📷 Selesaikan & Foto Bukti →
                            @else
                                Lihat Detail →
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
