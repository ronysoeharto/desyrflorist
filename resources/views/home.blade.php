@extends('layouts.app')

@section('title', 'Desy Florist — Toko Bunga Segar & Artisan Floral Studio Jakarta')

@section('content')
<!-- 1. Hero Section (Dynamic Banner Showcase) -->
@php
    $hasBanners = isset($banners) && $banners->isNotEmpty();
@endphp

<section class="relative bg-[#FAF8F5] overflow-hidden border-b border-[#EADFD4]" id="heroBannerCarousel">
    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Admin Quick Access Badge -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-0 flex justify-end">
            <a href="{{ route('admin.banners.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/90 border border-[#D8BABF] text-[11px] font-semibold text-[#B85D66] hover:bg-[#F8E8EA] transition shadow-xs">
                <span>⚙️ Kelola Banner (Admin)</span>
            </a>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16 lg:py-20">
        @if($hasBanners)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left Editorial Text (Slide synced) -->
                <div class="lg:col-span-7 relative">
                    @foreach($banners as $idx => $banner)
                        <div class="hero-text-slide space-y-6 transition-all duration-700 ease-out {{ $idx === 0 ? 'block opacity-100 translate-y-0' : 'hidden opacity-0 translate-y-2' }}"
                             data-slide-index="{{ $idx }}">
                            
                            @if(!empty($banner->subtitle))
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F8E8EA] border border-[#E5B2B6] text-xs font-semibold text-[#B85D66]">
                                    <span class="w-2 h-2 rounded-full bg-[#B85D66]"></span>
                                    <span>{{ $banner->subtitle }}</span>
                                </div>
                            @endif

                            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-[#2A2626] leading-[1.15] font-normal">
                                {!! nl2br(e($banner->title ?: ($storeSettings['store_name'] ?? 'Desy Florist'))) !!}
                            </h1>

                            @if(!empty($banner->description))
                                <p class="text-sm sm:text-base text-[#6B6565] leading-relaxed max-w-xl font-light">
                                    {{ $banner->description }}
                                </p>
                            @endif

                            <!-- Action Buttons -->
                            @if((!empty($banner->button_text) && !empty($banner->link_url)) || (!empty($banner->secondary_button_text) && !empty($banner->secondary_button_url)))
                                <div class="pt-2 flex flex-wrap items-center gap-4">
                                    @if(!empty($banner->button_text) && !empty($banner->link_url))
                                        <a href="{{ $banner->link_url }}"
                                           class="px-7 py-3.5 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
                                            {{ $banner->button_text }}
                                        </a>
                                    @endif

                                    @if(!empty($banner->secondary_button_text) && !empty($banner->secondary_button_url))
                                        <a href="{{ $banner->secondary_button_url }}"
                                           class="px-7 py-3.5 rounded-xl border border-[#D8BABF] hover:border-[#B85D66] hover:bg-[#F8E8EA] text-xs font-semibold tracking-wider uppercase text-[#2A2626] transition">
                                            {{ $banner->secondary_button_text }}
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="pt-2 flex flex-wrap items-center gap-4">
                                    <a href="{{ route('shop.index') }}"
                                       class="px-7 py-3.5 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
                                        Jelajahi Katalog
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Micro Trust Bar -->
                    <div class="mt-8 pt-6 grid grid-cols-2 sm:grid-cols-3 gap-4 border-t border-[#EADFD4] text-xs text-[#6B6565]">
                        <div class="flex items-center gap-2">
                            <span class="text-[#7B8B75] font-bold">✓</span>
                            <span>Bunga Segar Tiap Pagi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[#7B8B75] font-bold">✓</span>
                            <span>Foto Sebelum Dikirim</span>
                        </div>
                        <div class="flex items-center gap-2 col-span-2 sm:col-span-1">
                            <span class="text-[#7B8B75] font-bold">✓</span>
                            <span>Gratis Kartu Ucapan</span>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Image Showcase (Carousel Slider) -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <!-- Main Image Frame -->
                        <div class="rounded-2xl overflow-hidden shadow-lg border border-[#EADFD4] bg-[#F3ECE4] relative group">
                            @foreach($banners as $idx => $banner)
                                <div class="hero-image-slide transition-opacity duration-700 ease-in-out {{ $idx === 0 ? 'relative opacity-100 z-10' : 'absolute inset-0 opacity-0 z-0 pointer-events-none' }}"
                                     data-slide-index="{{ $idx }}">
                                    @if(!empty($banner->image_url))
                                        @if($banner->link_url)
                                            <a href="{{ $banner->link_url }}" class="block w-full h-full relative group/link" title="{{ $banner->title ?? 'Lihat Detail' }}">
                                                <img src="{{ $banner->image_url }}"
                                                     alt="{{ $banner->title ?? 'Banner Desy Florist' }}"
                                                     class="w-full h-full object-cover transition duration-700 group-hover/link:scale-105">
                                                <div class="absolute inset-0 bg-black/0 group-hover/link:bg-black/10 transition-colors duration-300"></div>
                                            </a>
                                        @else
                                            <img src="{{ $banner->image_url }}"
                                                 alt="{{ $banner->title ?? 'Banner Desy Florist' }}"
                                                 class="w-full h-full object-cover">
                                        @endif
                                    @else
                                        <div class="w-full aspect-[4/3] flex items-center justify-center bg-[#FAF8F5] text-[#9B9494]">
                                            <span class="text-xs">Foto Banner Desy Florist</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            <!-- Next / Previous Controls (Shown if more than 1 banner) -->
                            @if($banners->count() > 1)
                                <div class="absolute inset-y-0 left-3 flex items-center z-20">
                                    <button id="heroPrevBtn" aria-label="Banner Sebelumnya"
                                            class="w-9 h-9 rounded-full bg-white/80 hover:bg-white text-[#2A2626] hover:text-[#B85D66] border border-[#EADFD4] shadow-sm backdrop-blur-sm flex items-center justify-center text-sm transition transform hover:scale-105">
                                        ‹
                                    </button>
                                </div>
                                <div class="absolute inset-y-0 right-3 flex items-center z-20">
                                    <button id="heroNextBtn" aria-label="Banner Berikutnya"
                                            class="w-9 h-9 rounded-full bg-white/80 hover:bg-white text-[#2A2626] hover:text-[#B85D66] border border-[#EADFD4] shadow-sm backdrop-blur-sm flex items-center justify-center text-sm transition transform hover:scale-105">
                                        ›
                                    </button>
                                </div>

                                <!-- Carousel Dots Indicator -->
                                <div class="absolute bottom-3 inset-x-0 flex items-center justify-center gap-1.5 z-20">
                                    @foreach($banners as $idx => $banner)
                                        <button class="hero-dot w-2 h-2 rounded-full transition-all duration-300 {{ $idx === 0 ? 'bg-[#B85D66] w-6' : 'bg-white/70 hover:bg-white' }}"
                                                data-slide-index="{{ $idx }}"
                                                aria-label="Pindah ke banner {{ $idx + 1 }}">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Floating Quality Badge (Shown only if current slide has badge_text) -->
                        <div id="heroFloatingBadge" class="absolute -bottom-6 -left-6 bg-white/95 backdrop-blur-sm p-4 rounded-xl border border-[#EADFD4] shadow-md max-w-[240px] {{ !empty($banners->first()->badge_text) ? 'hidden sm:block' : 'hidden' }} z-20 transition-all duration-500">
                            <div class="flex items-center gap-2 text-[#C5A059] text-xs mb-1">
                                ★★★★★
                            </div>
                            <p id="heroBadgeText" class="text-[11px] font-medium text-[#2A2626]">
                                {{ $banners->first()->badge_text ?? '' }}
                            </p>
                            <span id="heroBadgeAuthor" class="text-[10px] text-[#9B9494] block mt-1 {{ !empty($banners->first()->badge_author) ? '' : 'hidden' }}">
                                {{ $banners->first()->badge_author ?? '' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Default Hero Section (When no custom banners are configured) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F8E8EA] border border-[#E5B2B6] text-xs font-semibold text-[#B85D66]">
                        <span class="w-2 h-2 rounded-full bg-[#B85D66]"></span>
                        <span>{{ $storeSettings['store_tagline'] ?? ('Artisan Florist ' . ($storeSettings['store_city'] ?? 'Jakarta')) }}</span>
                    </div>

                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-[#2A2626] leading-[1.15] font-normal">
                        {{ $storeSettings['store_name'] ?? 'Desy Florist' }}
                    </h1>

                    <p class="text-sm sm:text-base text-[#6B6565] leading-relaxed max-w-xl font-light">
                        {{ $storeSettings['store_about_short'] ?? 'Rangkaian bunga segar pilihan dengan kurasi terbaik untuk setiap momen berharga Anda.' }}
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <a href="{{ route('shop.index') }}"
                           class="px-7 py-3.5 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
                            Jelajahi Katalog Bunga
                        </a>
                        <a href="{{ route('custom-bouquet.index') }}"
                           class="px-7 py-3.5 rounded-xl border border-[#D8BABF] hover:border-[#B85D66] hover:bg-[#F8E8EA] text-xs font-semibold tracking-wider uppercase text-[#2A2626] transition">
                            ✨ Rangkai Buket Sendiri
                        </a>
                    </div>

                    <!-- Micro Trust Bar -->
                    <div class="mt-8 pt-6 grid grid-cols-2 sm:grid-cols-3 gap-4 border-t border-[#EADFD4] text-xs text-[#6B6565]">
                        <div class="flex items-center gap-2">
                            <span class="text-[#7B8B75] font-bold">✓</span>
                            <span>Bunga Segar Tiap Pagi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[#7B8B75] font-bold">✓</span>
                            <span>Foto Sebelum Dikirim</span>
                        </div>
                        <div class="flex items-center gap-2 col-span-2 sm:col-span-1">
                            <span class="text-[#7B8B75] font-bold">✓</span>
                            <span>Gratis Kartu Ucapan</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 relative">
                    <div class="rounded-2xl border border-[#EADFD4] bg-white p-8 sm:p-10 text-center space-y-4 shadow-xs">
                        <div class="w-16 h-16 rounded-full bg-[#FAF8F5] border border-[#EADFD4] mx-auto flex items-center justify-center text-3xl">
                            🌸
                        </div>
                        <h3 class="font-serif text-2xl text-[#2A2626]">{{ $storeSettings['store_name'] ?? 'Desy Florist' }}</h3>
                        <p class="text-xs text-[#6B6565] leading-relaxed">
                            Koleksi buket tangan, bunga meja, dan standing flower segar dirangkai oleh floris berpengalaman.
                        </p>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <div class="pt-2">
                                <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#B85D66] text-white text-xs font-semibold hover:bg-[#8F3B44] transition shadow-xs">
                                    + Unggah Foto Banner Toko
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@if($hasBanners && $banners->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const section = document.getElementById('heroBannerCarousel');
    if (!section) return;

    const textSlides = section.querySelectorAll('.hero-text-slide');
    const imageSlides = section.querySelectorAll('.hero-image-slide');
    const dots = section.querySelectorAll('.hero-dot');
    const prevBtn = document.getElementById('heroPrevBtn');
    const nextBtn = document.getElementById('heroNextBtn');
    const floatingBadge = document.getElementById('heroFloatingBadge');
    const badgeText = document.getElementById('heroBadgeText');
    const badgeAuthor = document.getElementById('heroBadgeAuthor');

    const totalSlides = imageSlides.length;
    let currentSlide = 0;
    let autoSlideTimer = null;

    // Badges array from rendered slides
    const badgesData = [
        @foreach($banners as $b)
            {
                text: {!! json_encode($b->badge_text ?? '') !!},
                author: {!! json_encode($b->badge_author ?? '') !!}
            },
        @endforeach
    ];

    function showSlide(index) {
        if (index < 0) index = totalSlides - 1;
        if (index >= totalSlides) index = 0;
        currentSlide = index;

        // Image slides
        imageSlides.forEach((slide, i) => {
            if (i === currentSlide) {
                slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                slide.classList.add('opacity-100', 'z-10');
            } else {
                slide.classList.remove('opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'pointer-events-none', 'z-0');
            }
        });

        // Text slides
        textSlides.forEach((slide, i) => {
            if (i === currentSlide) {
                slide.classList.remove('hidden', 'opacity-0', 'translate-y-2');
                slide.classList.add('block', 'opacity-100', 'translate-y-0');
            } else {
                slide.classList.remove('block', 'opacity-100', 'translate-y-0');
                slide.classList.add('hidden', 'opacity-0', 'translate-y-2');
            }
        });

        // Dots
        dots.forEach((dot, i) => {
            if (i === currentSlide) {
                dot.className = 'hero-dot h-2 rounded-full transition-all duration-300 bg-[#B85D66] w-6';
            } else {
                dot.className = 'hero-dot h-2 rounded-full transition-all duration-300 bg-white/70 hover:bg-white w-2';
            }
        });

        // Floating Badge
        if (floatingBadge) {
            const data = badgesData[currentSlide];
            if (data && data.text) {
                if (badgeText) badgeText.textContent = data.text;
                if (badgeAuthor) {
                    if (data.author) {
                        badgeAuthor.textContent = data.author;
                        badgeAuthor.classList.remove('hidden');
                    } else {
                        badgeAuthor.classList.add('hidden');
                    }
                }
                floatingBadge.classList.remove('hidden');
                floatingBadge.classList.add('sm:block');
            } else {
                floatingBadge.classList.add('hidden');
                floatingBadge.classList.remove('sm:block');
            }
        }
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    function startAutoSlide() {
        stopAutoSlide();
        autoSlideTimer = setInterval(nextSlide, 5000);
    }

    function stopAutoSlide() {
        if (autoSlideTimer) {
            clearInterval(autoSlideTimer);
            autoSlideTimer = null;
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            startAutoSlide();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            startAutoSlide();
        });
    }

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            showSlide(idx);
            startAutoSlide();
        });
    });

    // Pause on hover
    section.addEventListener('mouseenter', stopAutoSlide);
    section.addEventListener('mouseleave', startAutoSlide);

    // Initial start
    startAutoSlide();
});
</script>
@endif

<!-- 2. Featured Categories Section -->
<section class="py-16 md:py-24 bg-[#FAF8F5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-[11px] uppercase tracking-widest text-[#B85D66] font-semibold block mb-1">Koleksi Desy Florist</span>
            <h2 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Kategori Rangkaian Bunga</h2>
            <p class="text-xs sm:text-sm text-[#6B6565] mt-2 font-light">
                Pilih gaya rangkaian yang paling mewakili ungkapan hati dan perhatian Anda
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($categories as $category)
                <a href="{{ route('shop.category', $category->slug) }}"
                   class="group relative aspect-[3/4] rounded-xl overflow-hidden shadow-xs border border-[#EADFD4] bg-[#F3ECE4] flex flex-col justify-end p-4 transition duration-300 hover:shadow-md">
                    <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=600&q=80' }}"
                         alt="{{ $category->name }}"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    
                    <!-- Dark Gradient Overlay for legible white text -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                    <div class="relative z-10 text-white space-y-1">
                        <h3 class="font-serif text-lg sm:text-xl font-normal group-hover:text-[#E5B2B6] transition">
                            {{ $category->name }}
                        </h3>
                        <p class="text-[11px] text-white/80 line-clamp-1 font-light">
                            {{ $category->description }}
                        </p>
                        <span class="inline-flex items-center gap-1 text-[10px] text-[#E5B2B6] font-semibold pt-1 uppercase tracking-wider group-hover:translate-x-1 transition">
                            Lihat Produk →
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- 3. Best Sellers Section -->
<section class="py-16 md:py-20 bg-white border-y border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-[11px] uppercase tracking-widest text-[#B85D66] font-semibold block mb-1">Favorit Pelanggan</span>
                <h2 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Paling Banyak Dipilih</h2>
                <p class="text-xs sm:text-sm text-[#6B6565] mt-1 font-light">Rangkaian bunga terpopuler yang selalu sukses memberi kesan mendalam.</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-semibold text-[#B85D66] hover:text-[#8F3B44] transition flex items-center gap-1 uppercase tracking-wider">
                Semua Produk Katalog →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- 4. Custom Bouquet Builder Highlight Banner -->
<section class="py-16 md:py-24 bg-[#F8E8EA]/50 border-b border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-[#E5B2B6] p-8 sm:p-12 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <div class="lg:col-span-7 space-y-4">
                <span class="text-[11px] uppercase tracking-widest text-[#B85D66] font-bold">Fitur Unggulan Studio</span>
                <h2 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">
                    Rangkai Buket Unik Anda Sendiri
                </h2>
                <p class="text-xs sm:text-sm text-[#6B6565] leading-relaxed font-light">
                    Ingin buket yang sepenuhnya personal? Di Desy Florist, Anda bebas memilih kombinasi kuntum bunga (Mawar Ecuador, Tulip, Lily, Baby Breath), jenis kertas pembungkus matte bernuansa elegan, warna pita satin ganda, hingga menambahkan cokelat Ferrero atau lampu peri.
                </p>

                <div class="pt-2 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs text-[#2A2626]">
                    <div class="p-3 bg-[#FAF8F5] rounded-lg border border-[#EADFD4]">
                        <span class="text-[#B85D66] font-bold block mb-0.5">1. Pilih Bunga</span>
                        <span class="text-[11px] text-[#6B6565]">Tentukan jenis & jumlah tangkai</span>
                    </div>
                    <div class="p-3 bg-[#FAF8F5] rounded-lg border border-[#EADFD4]">
                        <span class="text-[#B85D66] font-bold block mb-0.5">2. Pilih Wrapping</span>
                        <span class="text-[11px] text-[#6B6565]">Warna kertas & pita elegan</span>
                    </div>
                    <div class="p-3 bg-[#FAF8F5] rounded-lg border border-[#EADFD4] col-span-2 sm:col-span-1">
                        <span class="text-[#B85D66] font-bold block mb-0.5">3. Pesan Khusus</span>
                        <span class="text-[11px] text-[#6B6565]">Kartu ucapan & pengiriman</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('custom-bouquet.index') }}"
                       class="inline-block px-7 py-3.5 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
                        Mulai Rangkai Sekarang
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="aspect-square rounded-xl overflow-hidden border border-[#EADFD4] bg-[#F3ECE4] shadow-xs">
                    <img src="https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&w=800&q=80"
                         alt="Custom Bouquet Studio"
                         class="w-full h-full object-cover">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 5. Occasions Curation -->
<section class="py-16 md:py-20 bg-[#FAF8F5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-[11px] uppercase tracking-widest text-[#B85D66] font-semibold block mb-1">Momen Spesial</span>
            <h2 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Bunga untuk Setiap Perayaan</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 text-center">
            <a href="{{ route('shop.index', ['search' => 'ulang tahun']) }}" class="p-6 bg-white rounded-xl border border-[#EADFD4] hover:border-[#B85D66] transition shadow-xs group">
                <div class="text-3xl mb-3">🎂</div>
                <h4 class="font-serif text-base text-[#2A2626] group-hover:text-[#B85D66] transition font-medium">Ulang Tahun</h4>
                <p class="text-[11px] text-[#6B6565] mt-1">Buket ceria penuh semangat</p>
            </a>

            <a href="{{ route('shop.index', ['search' => 'wisuda']) }}" class="p-6 bg-white rounded-xl border border-[#EADFD4] hover:border-[#B85D66] transition shadow-xs group">
                <div class="text-3xl mb-3">🎓</div>
                <h4 class="font-serif text-base text-[#2A2626] group-hover:text-[#B85D66] transition font-medium">Wisuda & Kelulusan</h4>
                <p class="text-[11px] text-[#6B6565] mt-1">Apresiasi pencapaian terbaik</p>
            </a>

            <a href="{{ route('shop.index', ['search' => 'anniversary']) }}" class="p-6 bg-white rounded-xl border border-[#EADFD4] hover:border-[#B85D66] transition shadow-xs group">
                <div class="text-3xl mb-3">💍</div>
                <h4 class="font-serif text-base text-[#2A2626] group-hover:text-[#B85D66] transition font-medium">Anniversary & Cinta</h4>
                <p class="text-[11px] text-[#6B6565] mt-1">Mawar dusty pink romantis</p>
            </a>

            <a href="{{ route('shop.index', ['search' => 'opening']) }}" class="p-6 bg-white rounded-xl border border-[#EADFD4] hover:border-[#B85D66] transition shadow-xs group">
                <div class="text-3xl mb-3">🎀</div>
                <h4 class="font-serif text-base text-[#2A2626] group-hover:text-[#B85D66] transition font-medium">Grand Opening & Bisnis</h4>
                <p class="text-[11px] text-[#6B6565] mt-1">Standing flower & papan elegan</p>
            </a>
        </div>
    </div>
</section>

<!-- 6. Customer Testimonials -->
@if(isset($reviews) && $reviews->isNotEmpty())
<section class="py-16 md:py-20 bg-white border-t border-[#EADFD4]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-[11px] uppercase tracking-widest text-[#B85D66] font-semibold block mb-1">Kepuasan Pelanggan</span>
            <h2 class="font-serif text-3xl sm:text-4xl text-[#2A2626] font-normal">Kisah & Pengalaman Mereka</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews as $rev)
                <div class="p-6 rounded-xl border border-[#EADFD4] bg-[#FAF8F5] flex flex-col justify-between shadow-2xs">
                    <div class="space-y-3">
                        <div class="text-amber-400 text-sm tracking-wider font-bold">
                            {{ str_repeat('★', $rev->rating) }}<span class="text-gray-300">{{ str_repeat('☆', 5 - $rev->rating) }}</span>
                        </div>
                        <p class="text-xs text-[#2A2626] leading-relaxed italic">
                            "{{ $rev->comment ?? 'Rangkaian bunga segar dan sangat indah!' }}"
                        </p>
                    </div>
                    <div class="pt-4 border-t border-[#EADFD4] mt-4 flex items-center justify-between text-[11px]">
                        <div>
                            <span class="font-semibold text-[#2A2626] block">{{ $rev->user->name ?? 'Pelanggan Desy Florist' }}</span>
                            @if($rev->product)
                                <span class="text-[10px] text-[#B85D66] font-medium block">{{ $rev->product->name }}</span>
                            @endif
                        </div>
                        <span class="text-[#9B9494] text-[10px]">{{ $rev->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
