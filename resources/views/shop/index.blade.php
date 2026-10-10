@extends('layouts.app')

@section('title', 'Katalog Rangkaian Bunga Segar — Desy Florist')

@section('content')
<div class="bg-[#FAF8F5] py-8 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <nav class="text-xs text-[#9B9494] mb-2 flex items-center gap-1.5">
                    <a href="{{ route('home') }}" class="hover:text-[#B85D66]">Beranda</a>
                    <span>/</span>
                    <span class="text-[#2A2626]">Katalog Bunga</span>
                </nav>
                <h1 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Koleksi Rangkaian Bunga</h1>
                <p class="text-xs sm:text-sm text-[#6B6565] mt-1 font-light">Buket tangan, bunga meja, bloom box, standing flower & anggrek segar.</p>
            </div>
            
            <div class="text-xs text-[#6B6565]">
                Menampilkan <strong>{{ $products->total() }}</strong> rangkaian bunga
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left Filter Sidebar -->
        <aside class="space-y-6">
            <div class="bg-white p-5 rounded-xl border border-[#EADFD4] shadow-xs space-y-6">
                
                <!-- Search Form -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#2A2626] mb-2">Cari Bunga</h3>
                    <form method="GET" action="{{ route('shop.index') }}" class="relative">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama bunga atau momen..."
                               class="w-full pl-3 pr-8 py-2 text-xs rounded-lg border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/30">
                        <button type="submit" class="absolute right-2.5 top-2.5 text-[#6B6565] hover:text-[#B85D66]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Category Filter -->
                <div class="pt-4 border-t border-[#F3ECE4]">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#2A2626] mb-3">Kategori</h3>
                    <ul class="text-xs space-y-2">
                        <li>
                            <a href="{{ route('shop.index', array_merge(request()->except('category', 'page'))) }}"
                               class="flex items-center justify-between {{ !request('category') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565] hover:text-[#2A2626]' }}">
                                <span>Semua Kategori</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
                                   class="flex items-center justify-between {{ request('category') === $cat->slug ? 'text-[#B85D66] font-bold' : 'text-[#6B6565] hover:text-[#2A2626]' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-[10px] text-[#9B9494]">({{ $cat->products()->count() }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Price Range Filter -->
                <div class="pt-4 border-t border-[#F3ECE4]">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#2A2626] mb-3">Rentang Harga</h3>
                    <form method="GET" action="{{ route('shop.index') }}" class="space-y-2 text-xs">
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min (Rp)" class="p-2 border border-[#D8BABF] rounded text-xs">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max (Rp)" class="p-2 border border-[#D8BABF] rounded text-xs">
                        </div>
                        <button type="submit" class="w-full py-2 bg-[#F3ECE4] hover:bg-[#EADFD4] text-[#2A2626] rounded text-xs font-semibold transition">
                            Terapkan Filter
                        </button>
                    </form>
                </div>

                <!-- Reset All Filters -->
                @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'sort']))
                    <div class="pt-4 border-t border-[#F3ECE4]">
                        <a href="{{ route('shop.index') }}" class="block text-center text-xs text-[#8F3B44] hover:underline">
                            Hapus Semua Filter
                        </a>
                    </div>
                @endif

            </div>
        </aside>

        <!-- Right Products Grid -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Sort Bar -->
            <div class="flex items-center justify-between pb-3 border-b border-[#EADFD4] text-xs">
                <span class="text-[#6B6565]">Urutkan berdasarkan:</span>
                <form method="GET" action="{{ route('shop.index') }}" id="sortForm">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                    @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                    
                    <select name="sort" onchange="document.getElementById('sortForm').submit()"
                            class="px-3 py-1.5 rounded-lg border border-[#D8BABF] bg-white text-xs focus:outline-none focus:ring-1 focus:ring-[#B85D66]">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Populer</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                    </select>
                </form>
            </div>

            <!-- Products List -->
            @if($products->isEmpty())
                <div class="bg-white rounded-xl border border-[#EADFD4] p-12 text-center space-y-3">
                    <span class="text-4xl block">🌸</span>
                    <h3 class="font-serif text-xl text-[#2A2626]">Tidak Menemukan Bunga</h3>
                    <p class="text-xs text-[#6B6565] max-w-sm mx-auto">
                        Maaf, kami tidak menemukan produk bunga yang sesuai dengan filter pencarian Anda. Coba kata kunci lain atau hapus filter.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('shop.index') }}" class="inline-block px-4 py-2 bg-[#B85D66] text-white rounded-lg text-xs font-semibold">
                            Lihat Semua Bunga
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-8">
                    {{ $products->links() }}
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
