@extends('layouts.courier')

@section('title', 'Riwayat Pengantaran Selesai — Desy Florist')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="font-serif text-2xl text-[#2A2626]">Riwayat Selesai</h1>
        <span class="text-xs text-[#6B6565]">{{ $deliveries->total() }} pesanan selesai</span>
    </div>

    @if($deliveries->isEmpty())
        <div class="bg-white p-8 rounded-2xl border border-[#EADFD4] text-center text-xs text-[#6B6565]">
            Belum ada riwayat pengantaran yang diselesaikan.
        </div>
    @else
        <div class="space-y-4">
            @foreach($deliveries as $del)
                <div class="bg-white p-5 rounded-2xl border border-[#EADFD4] shadow-xs space-y-2 text-xs">
                    <div class="flex items-center justify-between pb-2 border-b border-[#F3ECE4]">
                        <span class="font-mono font-bold text-[#2A2626]">{{ $del->order->order_number }}</span>
                        <span class="text-[10px] text-[#5C6A57] font-semibold">Terkirim: {{ $del->delivered_at?->format('d M Y, H:i') }} WIB</span>
                    </div>

                    <div class="text-[11px] text-[#6B6565]">
                        <p>Penerima: <strong>{{ $del->order->recipient_name }}</strong></p>
                        <p>Alamat: {{ $del->order->delivery_address }}</p>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <a href="{{ route('courier.deliveries.show', $del->id) }}" class="text-xs text-[#5C6A57] font-semibold hover:underline">
                            Lihat Rincian & Bukti →
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="pt-4">
                {{ $deliveries->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
