@extends('layouts.app')

@section('title', $category->name . ' — Koleksi Desy Florist')
@section('meta_description', $category->description)

@section('content')
<div class="bg-[#FAF8F5] py-10 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-xs text-[#9B9494] mb-3 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
            <span>/</span>
            <a href="{{ route('shop.index') }}" class="hover:text-[#B85D66]">Katalog</a>
            <span>/</span>
            <span class="text-[#2A2626] font-medium">{{ $category->name }}</span>
        </nav>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">{{ $category->name }}</h1>
        <p class="text-xs sm:text-sm text-[#6B6565] mt-1 max-w-2xl font-light">{{ $category->description }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($products->isEmpty())
        <div class="bg-white rounded-xl border border-[#EADFD4] p-12 text-center space-y-3">
            <span class="text-4xl block">🌸</span>
            <h3 class="font-serif text-xl text-[#2A2626]">Belum Ada Rangkaian Bunga di Kategori Ini</h3>
            <p class="text-xs text-[#6B6565]">Silakan jelajahi kategori lainnya.</p>
            <div class="pt-2">
                <a href="{{ route('shop.index') }}" class="inline-block px-4 py-2 bg-[#B85D66] text-white rounded-lg text-xs font-semibold">
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="pt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
