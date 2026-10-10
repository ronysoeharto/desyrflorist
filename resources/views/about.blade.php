@extends('layouts.app')

@section('title', 'Tentang Kami — Desy Florist Artisan Floral Studio')

@section('content')
@php
    $aboutTagline = $storeSettings['about_tagline'] ?? ('Artisan Florist ' . ($storeSettings['store_city'] ?? 'Surabaya'));
    $aboutTitle = $storeSettings['about_title'] ?? 'Kisah & Dedikasi Desy Florist';
    $aboutSubtitle = $storeSettings['about_subtitle'] ?? 'Menghubungkan hati lewat keindahan alami bunga segar bermutu tinggi sejak tahun 2018.';
    $aboutStoryTitle = $storeSettings['about_story_title'] ?? 'Filosofi Kami';
    $aboutStoryContent = $storeSettings['about_story_content'] ?? "Bagi kami, bunga bukan sekadar hadiah sementara, melainkan sebuah bahasa universal yang mampu menyampaikan rasa syukur, simpati, cinta, dan apresiasi tulus yang seringkali sulit terucap dengan kata-kata.\n\nSetiap bunga di Desy Florist dikurasi setiap pagi dari perkebunan bunga terpercaya di Bandung, Malang, hingga varietas impor pilihan. Kami merangkai setiap tangkai dengan pertimbangan palet warna yang harmonis, tekstur dedaunan segar, dan estetika feminin modern.";
    $aboutImage = !empty($storeSettings['about_image']) ? asset('storage/' . $storeSettings['about_image']) : 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=800&q=80';
    $val1Title = $storeSettings['about_val1_title'] ?? 'Kesegaran Terjamin';
    $val1Desc = $storeSettings['about_val1_desc'] ?? 'Bunga dipetik dan dirangkai tepat pada hari pengiriman untuk ketahanan mekar maksimal.';
    $val2Title = $storeSettings['about_val2_title'] ?? 'Sentuhan Floral Artist';
    $val2Desc = $storeSettings['about_val2_desc'] ?? 'Dirangkai oleh florist berpengalaman yang menguasai teknik wrapping Korea modern dan komposisi warna elegan.';
    $val3Title = $storeSettings['about_val3_title'] ?? 'Pengantaran Berhati-hati';
    $val3Desc = $storeSettings['about_val3_desc'] ?? 'Mitra kurir kami terlatih membawa buket dan vas bunga tegak sehingga tiba dalam kondisi sempurna.';
@endphp

<div class="bg-[#FAF8F5] py-12 border-b border-[#EADFD4]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-3">
        <span class="text-xs uppercase tracking-widest text-[#B85D66] font-semibold">{{ $aboutTagline }}</span>
        <h1 class="font-serif text-4xl sm:text-5xl text-[#2A2626] font-normal">{{ $aboutTitle }}</h1>
        <p class="text-xs sm:text-sm text-[#6B6565] font-light max-w-xl mx-auto leading-relaxed">
            {{ $aboutSubtitle }}
        </p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-16 space-y-16">
    
    <!-- Section 1: Story -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div class="space-y-4 text-xs sm:text-sm text-[#6B6565] leading-relaxed font-light">
            <h2 class="font-serif text-3xl text-[#2A2626]">{{ $aboutStoryTitle }}</h2>
            <div class="space-y-3">
                {!! nl2br(e($aboutStoryContent)) !!}
            </div>
        </div>
        <div class="aspect-[4/3] rounded-2xl overflow-hidden border border-[#EADFD4] shadow-md bg-[#F3ECE4]">
            <img src="{{ $aboutImage }}"
                 alt="{{ $aboutTitle }}" class="w-full h-full object-cover">
        </div>
    </div>

    <!-- Section 2: Values -->
    <div class="bg-white p-8 sm:p-12 rounded-2xl border border-[#EADFD4] shadow-xs">
        <div class="text-center max-w-xl mx-auto mb-10">
            <h3 class="font-serif text-2xl text-[#2A2626]">Komitmen Mutu Kami</h3>
            <p class="text-xs text-[#6B6565] mt-1 font-light">Standar tertinggi dalam setiap helai kelopak bunga</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center text-xs">
            <div class="space-y-2">
                <div class="w-12 h-12 rounded-full bg-[#F8E8EA] text-[#B85D66] mx-auto flex items-center justify-center text-xl">🌿</div>
                <h4 class="font-bold text-[#2A2626] text-sm">{{ $val1Title }}</h4>
                <p class="text-[#6B6565] leading-relaxed text-[11px]">{{ $val1Desc }}</p>
            </div>

            <div class="space-y-2">
                <div class="w-12 h-12 rounded-full bg-[#EEF2EB] text-[#5C6A57] mx-auto flex items-center justify-center text-xl">🎨</div>
                <h4 class="font-bold text-[#2A2626] text-sm">{{ $val2Title }}</h4>
                <p class="text-[#6B6565] leading-relaxed text-[11px]">{{ $val2Desc }}</p>
            </div>

            <div class="space-y-2">
                <div class="w-12 h-12 rounded-full bg-[#F3ECE4] text-[#C5A059] mx-auto flex items-center justify-center text-xl">🛵</div>
                <h4 class="font-bold text-[#2A2626] text-sm">{{ $val3Title }}</h4>
                <p class="text-[#6B6565] leading-relaxed text-[11px]">{{ $val3Desc }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
