@extends('layouts.app')

@section('title', 'Riwayat Pesanan — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <a href="{{ route('account.dashboard') }}" class="hover:text-[#B85D66]">Akun</a>
            <span>/</span>
            <span class="text-[#2A2626]">Riwayat Pesanan</span>
        </nav>
        <h1 class="font-serif text-3xl text-[#2A2626] font-normal">Riwayat Pesanan Bunga</h1>
    </div>
</div>

<x-account-nav />

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border border-[#EADFD4] p-12 text-center max-w-md mx-auto space-y-4">
            <span class="text-4xl block">📦</span>
            <h2 class="font-serif text-xl text-[#2A2626]">Belum Ada Pesanan</h2>
            <p class="text-xs text-[#6B6565]">Anda belum pernah melakukan pemesanan bunga di Desy Florist.</p>
            <div class="pt-2">
                <a href="{{ route('shop.index') }}" class="inline-block px-6 py-3 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold uppercase tracking-wider transition">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $ord)
                <div class="bg-white rounded-2xl border border-[#EADFD4] p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono font-bold text-sm text-[#2A2626]">{{ $ord->order_number }}</span>
                            @if($ord->tracking_number)
                                <span class="text-[11px] font-mono text-[#5C6A57] bg-[#EEF2EB] px-2 py-0.5 rounded font-bold border border-[#7B8B75]/20">
                                    Resi: {{ $ord->tracking_number }}
                                </span>
                            @endif
                            <span class="text-xs px-2.5 py-0.5 rounded-full {{ $ord->status_badge }}">
                                {{ $ord->status_label }}
                            </span>
                            <span class="text-xs px-2.5 py-0.5 rounded-full {{ $ord->payment_status_badge }}">
                                {{ $ord->payment_status_label }}
                            </span>
                        </div>
                        <div class="text-xs text-[#6B6565] space-y-1">
                            <p>📅 Tanggal Antar: <strong>{{ $ord->delivery_date->format('d M Y') }}</strong> (Slot: {{ $ord->delivery_slot }})</p>
                            <p>📍 Penerima: {{ $ord->recipient_name }} • {{ $ord->delivery_address }}</p>
                            <p class="text-[11px] text-[#9B9494]">Item: {{ $ord->items->pluck('name')->implode(', ') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between md:flex-col md:items-end gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-[#F3ECE4]">
                        <div class="font-serif text-lg font-bold text-[#B85D66]">
                            Rp {{ number_format($ord->total, 0, ',', '.') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('checkout.tracking', $ord->tracking_number ?? $ord->order_number) }}"
                               class="px-3.5 py-1.5 rounded-lg border border-[#B85D66] bg-[#FAF8F5] hover:bg-[#F8E8EA] text-xs font-semibold text-[#B85D66] transition flex items-center gap-1">
                                <span>🔍 Lacak</span>
                            </a>
                            <a href="{{ route('account.orders.detail', $ord->id) }}"
                               class="px-3.5 py-1.5 rounded-lg bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold transition">
                                Rincian
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="pt-6">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
