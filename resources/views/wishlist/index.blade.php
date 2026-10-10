@extends('layouts.app')

@section('title', 'Wishlist Saya — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <span class="text-[#2A2626]">Wishlist</span>
        </nav>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Daftar Keinginan Anda</h1>
        <p class="text-xs sm:text-sm text-[#6B6565] mt-1 font-light">Rangkaian bunga favorit yang telah Anda simpan</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($items->isEmpty())
        <div class="bg-white rounded-2xl border border-[#EADFD4] p-12 text-center max-w-md mx-auto space-y-4">
            <span class="text-4xl block">🤍</span>
            <h2 class="font-serif text-xl text-[#2A2626]">Wishlist Anda Masih Kosong</h2>
            <p class="text-xs text-[#6B6565]">Simpan rangkaian bunga favorit Anda dengan menekan ikon hati pada katalog produk.</p>
            <div class="pt-2">
                <a href="{{ route('shop.index') }}" class="inline-block px-6 py-3 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold uppercase tracking-wider transition">
                    Jelajahi Bunga
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($items as $item)
                @if($item->product)
                    <div class="bg-white rounded-xl border border-[#EADFD4] overflow-hidden flex flex-col justify-between">
                        <div class="relative aspect-square bg-[#F3ECE4]">
                            <img src="{{ $item->product->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=400&q=80' }}"
                                 alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <h3 class="font-serif text-base text-[#2A2626] line-clamp-1">
                                    <a href="{{ route('shop.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                </h3>
                                <div class="text-xs font-bold text-[#B85D66] mt-1">
                                    Rp {{ number_format($item->product->current_price, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-2 border-t border-[#F3ECE4]">
                                <form action="{{ route('wishlist.move-to-cart', $item->product->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-lg text-xs font-semibold transition">
                                        Pindah ke Keranjang
                                    </button>
                                </form>
                                <form action="{{ route('wishlist.toggle', $item->product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 border border-[#D8BABF] rounded-lg text-[#8F3B44] hover:bg-[#F8E8EA]" title="Hapus">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
