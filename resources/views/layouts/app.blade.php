<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Desy Florist — Artisan Floral Studio & Same Day Delivery Jakarta')</title>
    <meta name="description" content="@yield('meta_description', 'Desy Florist menyediakan buket bunga segar premium, bunga meja, bloom box, standing flower, dan buket pengantin dengan pengiriman hari yang sama di Jakarta.')">

    <!-- Fonts: Playfair Display & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#FAF8F5] text-[#2A2626] font-sans antialiased min-h-screen flex flex-col selection:bg-[#E5B2B6] selection:text-[#2A2626] pb-16 lg:pb-0">

    <!-- Top Announcement Bar -->
    <div class="bg-[#F3ECE4] text-[#6B6565] text-xs py-2 px-4 border-b border-[#EADFD4]">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-[#7B8B75] animate-pulse"></span>
                <span>Pengiriman <strong>Same-Day</strong> {{ $storeSettings['store_city'] ?? 'Surabaya' }} & Sekitarnya • Pesan sebelum {{ $storeSettings['same_day_cutoff'] ?? '16:00' }} WIB</span>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium">
                <a href="{{ route('checkout.tracking') }}" class="hover:text-[#B85D66] transition">Lacak Pesanan</a>
                <span class="text-[#D8BABF]">•</span>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $storeSettings['store_whatsapp'] ?? '6281234567890') }}?text=Halo%20{{ urlencode($storeSettings['store_name'] ?? 'Desy Florist') }},%20saya%20ingin%20konsultasi%20rangkaian%20bunga" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1 text-[#5C6A57] hover:text-[#2A2626] transition font-semibold">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Konsultasi WA: {{ $storeSettings['store_whatsapp'] ?? '0812-3456-7890' }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-[#FAF8F5]/95 backdrop-blur-md border-b border-[#EADFD4]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Left: Mobile Menu Button & Primary Nav Links -->
                <div class="flex items-center gap-8">
                    <button id="mobileMenuBtn" class="lg:hidden p-2 text-[#2A2626] hover:text-[#B85D66] focus:outline-none" aria-label="Toggle Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    <nav class="hidden lg:flex items-center gap-7 text-[13px] font-medium tracking-wide uppercase">
                        <a href="{{ route('home') }}" class="transition hover:text-[#B85D66] {{ request()->routeIs('home') ? 'text-[#B85D66] font-semibold' : 'text-[#2A2626]' }}">Beranda</a>
                        <a href="{{ route('shop.index') }}" class="transition hover:text-[#B85D66] {{ request()->routeIs('shop.index') ? 'text-[#B85D66] font-semibold' : 'text-[#2A2626]' }}">Katalog</a>
                        
                        <!-- Categories Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center gap-1 transition hover:text-[#B85D66] {{ request()->routeIs('shop.category') ? 'text-[#B85D66] font-semibold' : 'text-[#2A2626]' }}">
                                <span>Kategori</span>
                                <svg class="w-3.5 h-3.5 transition group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="absolute left-0 top-full pt-3 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 z-50">
                                <div class="bg-white rounded-lg shadow-xl border border-[#EADFD4] py-2 text-xs normal-case">
                                    <a href="{{ route('shop.category', 'hand-bouquet') }}" class="block px-4 py-2.5 text-[#2A2626] hover:bg-[#F8E8EA] hover:text-[#B85D66]">Hand Bouquet</a>
                                    <a href="{{ route('shop.category', 'table-flower') }}" class="block px-4 py-2.5 text-[#2A2626] hover:bg-[#F8E8EA] hover:text-[#B85D66]">Table Flower (Bunga Meja)</a>
                                    <a href="{{ route('shop.category', 'bloom-box') }}" class="block px-4 py-2.5 text-[#2A2626] hover:bg-[#F8E8EA] hover:text-[#B85D66]">Bloom Box</a>
                                    <a href="{{ route('shop.category', 'standing-flower') }}" class="block px-4 py-2.5 text-[#2A2626] hover:bg-[#F8E8EA] hover:text-[#B85D66]">Standing Flower</a>
                                    <a href="{{ route('shop.category', 'bunga-papan') }}" class="block px-4 py-2.5 text-[#2A2626] hover:bg-[#F8E8EA] hover:text-[#B85D66]">Bunga Papan</a>
                                    <a href="{{ route('shop.category', 'anggrek-bulan') }}" class="block px-4 py-2.5 text-[#2A2626] hover:bg-[#F8E8EA] hover:text-[#B85D66]">Anggrek Bulan</a>
                                    <a href="{{ route('shop.category', 'wedding-event') }}" class="block px-4 py-2.5 text-[#2A2626] hover:bg-[#F8E8EA] hover:text-[#B85D66]">Wedding & Event</a>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('custom-bouquet.index') }}" class="inline-flex items-center gap-1.5 text-[#B85D66] font-semibold hover:text-[#8F3B44] transition">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#B85D66]"></span>
                            Custom Buket
                        </a>
                        <a href="{{ route('about') }}" class="transition hover:text-[#B85D66] {{ request()->routeIs('about') ? 'text-[#B85D66] font-semibold' : 'text-[#2A2626]' }}">Tentang Kami</a>
                        <a href="{{ route('contact') }}" class="transition hover:text-[#B85D66] {{ request()->routeIs('contact') ? 'text-[#B85D66] font-semibold' : 'text-[#2A2626]' }}">Kontak</a>
                    </nav>
                </div>

                <!-- Center: Brand Logo -->
                <div class="text-center">
                    <a href="{{ route('home') }}" class="inline-block group">
                        <span class="font-serif text-2xl sm:text-3xl tracking-wide font-normal text-[#2A2626] group-hover:text-[#B85D66] transition block">
                            Desy Florist
                        </span>
                    </a>
                </div>

                <!-- Right: Search, Wishlist, Cart, Account -->
                <div class="flex items-center gap-4 sm:gap-5">
                    
                    <!-- Search Trigger -->
                    <a href="{{ route('shop.index') }}" class="p-2 text-[#2A2626] hover:text-[#B85D66] transition" title="Cari Bunga">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </a>

                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="p-2 text-[#2A2626] hover:text-[#B85D66] transition relative" title="Wishlist">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        @auth
                            @php $wishlistCount = Auth::user()->wishlists()->count(); @endphp
                            @if($wishlistCount > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 bg-[#B85D66] text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        @endauth
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="p-2 text-[#2A2626] hover:text-[#B85D66] transition relative" title="Keranjang Belanja">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        @php
                            $cartService = app(\App\Services\CartService::class);
                            $cartCount = $cartService->count();
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-[#B85D66] text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Account Dropdown -->
                    <div class="relative group">
                        @auth
                            <button class="flex items-center gap-1.5 p-1 text-xs font-medium text-[#2A2626] hover:text-[#B85D66] transition rounded-full focus:outline-none">
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-7 h-7 rounded-full object-cover border border-[#D8BABF]">
                                <span class="hidden md:inline-block max-w-[90px] truncate text-xs">{{ Auth::user()->name }}</span>
                            </button>
                            <div class="absolute right-0 top-full pt-2 w-52 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 z-50">
                                <div class="bg-white rounded-lg shadow-xl border border-[#EADFD4] py-2 text-xs">
                                    <div class="px-4 py-2 border-b border-[#F3ECE4] text-[11px] text-[#6B6565]">
                                        Masuk sebagai <strong>{{ Auth::user()->name }}</strong>
                                        <div class="text-[10px] text-[#9B9494] capitalize">Peran: {{ Auth::user()->role }}</div>
                                    </div>
                                    
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-[#B85D66] font-semibold hover:bg-[#F8E8EA]">Admin Dashboard</a>
                                    @endif

                                    @if(Auth::user()->isCourier())
                                        <a href="{{ route('courier.dashboard') }}" class="block px-4 py-2 text-[#5C6A57] font-semibold hover:bg-[#EEF2EB]">Kurir Dashboard</a>
                                    @endif

                                    <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-[#2A2626] hover:bg-[#F8E8EA]">Akun Saya</a>
                                    <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-[#2A2626] hover:bg-[#F8E8EA]">Riwayat Pesanan</a>
                                    <a href="{{ route('account.addresses') }}" class="block px-4 py-2 text-[#2A2626] hover:bg-[#F8E8EA]">Buku Alamat</a>

                                    <form action="{{ route('logout') }}" method="POST" class="border-t border-[#F3ECE4] mt-1 pt-1">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-[#2A2626] hover:text-[#B85D66] px-3 py-1.5 rounded border border-[#EADFD4] hover:border-[#B85D66] transition">
                                <span>Masuk</span>
                            </a>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- Storefront & Customer Mobile Sidebar Backdrop -->
    <div id="customerMobileSidebarBackdrop" class="fixed inset-0 bg-[#2A2626]/50 backdrop-blur-xs z-50 hidden lg:hidden transition-opacity"></div>

    <!-- Storefront & Customer Mobile Sidebar Drawer -->
    <aside id="customerMobileSidebar" class="fixed inset-y-0 left-0 w-80 max-w-[85vw] bg-white border-r border-[#EADFD4] z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden flex flex-col justify-between shadow-2xl">
        <div class="overflow-y-auto">
            <!-- Studio Brand Logo & Close Button -->
            <div class="p-5 border-b border-[#EADFD4] flex items-center justify-between">
                <div>
                    <span class="font-serif text-2xl font-normal text-[#2A2626] block">Desy Florist</span>
                    <span class="text-[9px] uppercase tracking-[0.25em] text-[#6B6565] -mt-1 block">Artisan Floral Studio</span>
                </div>
                <button id="customerMobileCloseBtn" class="p-2 text-[#6B6565] hover:text-[#2A2626] rounded-lg" aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Customer Account Area -->
            @auth
                <div class="p-4 mx-4 mt-4 bg-[#FAF8F5] rounded-2xl border border-[#EADFD4] space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-11 h-11 rounded-full object-cover border border-[#D8BABF]">
                        <div class="min-w-0 flex-1">
                            <span class="font-semibold text-xs text-[#2A2626] block truncate">{{ Auth::user()->name }}</span>
                            <span class="text-[11px] text-[#9B9494] block truncate">{{ Auth::user()->email }}</span>
                            <span class="inline-block mt-0.5 text-[9px] font-bold uppercase tracking-wider px-2 py-0.2 rounded-full {{ Auth::user()->isAdmin() ? 'bg-[#F8E8EA] text-[#B85D66]' : (Auth::user()->isCourier() ? 'bg-[#EEF2EB] text-[#5C6A57]' : 'bg-[#FAF0E6] text-[#8F3B44]') }}">
                                {{ Auth::user()->role }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#EADFD4] text-xs font-medium">
                        <a href="{{ route('account.dashboard') }}" class="px-2.5 py-1.5 rounded-lg bg-white border border-[#EADFD4] text-[#2A2626] hover:text-[#B85D66] text-center transition">
                            📊 Akun Saya
                        </a>
                        <a href="{{ route('account.orders') }}" class="px-2.5 py-1.5 rounded-lg bg-white border border-[#EADFD4] text-[#2A2626] hover:text-[#B85D66] text-center transition">
                            📦 Pesanan
                        </a>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block w-full py-2 px-3 text-center rounded-xl bg-[#B85D66] text-white text-xs font-semibold hover:bg-[#8F3B44] transition">
                            ⚡ Buka Panel Admin Studio
                        </a>
                    @elseif(Auth::user()->isCourier())
                        <a href="{{ route('courier.dashboard') }}" class="block w-full py-2 px-3 text-center rounded-xl bg-[#5C6A57] text-white text-xs font-semibold hover:bg-[#434F3F] transition">
                            🛵 Buka Portal Kurir
                        </a>
                    @endif
                </div>
            @else
                <div class="p-4 mx-4 mt-4 bg-[#FAF8F5] rounded-2xl border border-[#EADFD4] text-center space-y-3">
                    <p class="text-xs text-[#6B6565]">Masuk untuk melihat pesanan bunga & wishlist Anda</p>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="py-2 px-3 rounded-xl bg-white border border-[#B85D66] text-[#B85D66] text-xs font-bold hover:bg-[#F8E8EA] transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="py-2 px-3 rounded-xl bg-[#B85D66] text-white text-xs font-bold hover:bg-[#8F3B44] transition">
                            Daftar
                        </a>
                    </div>
                </div>
            @endauth

            <!-- Storefront Main Navigation -->
            <nav class="p-4 space-y-1 text-xs font-medium">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('home') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#2A2626] hover:bg-[#FAF8F5]' }}">
                    <span class="text-sm">🏠</span> Beranda
                </a>

                <a href="{{ route('shop.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('shop.index') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#2A2626] hover:bg-[#FAF8F5]' }}">
                    <span class="text-sm">🌸</span> Katalog Lengkap Bunga
                </a>

                <!-- Categories in Mobile Drawer -->
                <div class="pt-2 pb-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-[#9B9494] block mb-1">Koleksi Bunga</span>
                    <div class="space-y-0.5 pl-3 border-l-2 border-[#F3ECE4] ml-3 text-[12px]">
                        <a href="{{ route('shop.category', 'hand-bouquet') }}" class="block px-3 py-1.5 rounded-lg text-[#6B6565] hover:text-[#B85D66] hover:bg-[#FAF8F5]">Hand Bouquet</a>
                        <a href="{{ route('shop.category', 'table-flower') }}" class="block px-3 py-1.5 rounded-lg text-[#6B6565] hover:text-[#B85D66] hover:bg-[#FAF8F5]">Table Flower (Bunga Meja)</a>
                        <a href="{{ route('shop.category', 'bloom-box') }}" class="block px-3 py-1.5 rounded-lg text-[#6B6565] hover:text-[#B85D66] hover:bg-[#FAF8F5]">Bloom Box</a>
                        <a href="{{ route('shop.category', 'standing-flower') }}" class="block px-3 py-1.5 rounded-lg text-[#6B6565] hover:text-[#B85D66] hover:bg-[#FAF8F5]">Standing Flower</a>
                        <a href="{{ route('shop.category', 'bunga-papan') }}" class="block px-3 py-1.5 rounded-lg text-[#6B6565] hover:text-[#B85D66] hover:bg-[#FAF8F5]">Bunga Papan</a>
                        <a href="{{ route('shop.category', 'anggrek-bulan') }}" class="block px-3 py-1.5 rounded-lg text-[#6B6565] hover:text-[#B85D66] hover:bg-[#FAF8F5]">Anggrek Bulan</a>
                        <a href="{{ route('shop.category', 'wedding-event') }}" class="block px-3 py-1.5 rounded-lg text-[#6B6565] hover:text-[#B85D66] hover:bg-[#FAF8F5]">Wedding & Event</a>
                    </div>
                </div>

                <a href="{{ route('custom-bouquet.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-[#FAF0E6] text-[#8F3B44] font-bold transition hover:bg-[#F8E8EA]">
                    <div class="flex items-center gap-3">
                        <span class="text-sm">✨</span> Custom Rangkai Buket
                    </div>
                    <span class="text-[9px] bg-[#B85D66] text-white px-2 py-0.5 rounded-full uppercase tracking-wider">Artisan</span>
                </a>

                <a href="{{ route('checkout.tracking') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-[#5C6A57] font-semibold hover:bg-[#EEF2EB]">
                    <span class="text-sm">🔍</span> Lacak Pesanan Bunga
                </a>

                <a href="{{ route('about') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('about') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">🌿</span> Tentang Desy Florist
                </a>

                <a href="{{ route('contact') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('contact') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">📍</span> Kontak & Lokasi Studio
                </a>
            </nav>

            <!-- WhatsApp Callout -->
            <div class="p-4 mx-4 mb-4 bg-[#EEF2EB] rounded-2xl border border-[#7B8B75]/30">
                <span class="text-[10px] uppercase font-bold tracking-wider text-[#5C6A57] block mb-1">Konsultasi Rangkaian</span>
                <p class="text-[11px] text-[#2A2626] mb-2 leading-relaxed">Butuh buket bunga kustom untuk hari ini? Hubungi florist kami langsung.</p>
                <a href="https://wa.me/6281234567890?text=Halo%20Desy%20Florist,%20saya%20ingin%20konsultasi%20rangkaian%20bunga" target="_blank"
                   class="inline-flex items-center justify-center gap-1.5 w-full py-2 bg-[#5C6A57] text-white rounded-xl text-xs font-semibold hover:bg-[#434F3F] transition shadow-xs">
                    <span>Chat WhatsApp Studio</span>
                </a>
            </div>
        </div>

        <!-- Mobile Sidebar Footer -->
        <div class="p-4 border-t border-[#EADFD4] text-xs bg-white">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left py-1 text-red-600 hover:underline font-medium">Keluar (Logout)</button>
                </form>
            @else
                <p class="text-[11px] text-[#9B9494]">Desy Florist • Florist Studio Jakarta</p>
            @endauth
        </div>
    </aside>

    <!-- Flash Messages / Toaster -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-[#EEF2EB] border border-[#7B8B75]/40 text-[#2A2626] px-4 py-3 rounded-lg text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#5C6A57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-[#6B6565] hover:text-[#2A2626]">&times;</button>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-[#F8E8EA] border border-[#B85D66]/40 text-[#2A2626] px-4 py-3 rounded-lg text-sm shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#B85D66]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold">{{ session('error') ?? 'Terdapat beberapa kendala:' }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-[#6B6565] hover:text-[#2A2626]">&times;</button>
                </div>
                @if($errors->any())
                    <ul class="list-disc list-inside mt-2 text-xs space-y-1 text-[#8F3B44]">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-[#F3ECE4] border border-[#D8BABF] text-[#2A2626] px-4 py-3 rounded-lg text-sm flex items-center justify-between shadow-sm">
                <span>{{ session('info') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-[#6B6565] hover:text-[#2A2626]">&times;</button>
            </div>
        </div>
    @endif

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#2A2626] text-[#FAF8F5] pt-16 pb-12 border-t border-[#3D3737] mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8 pb-12 border-b border-[#4A4343]">
                
                <!-- Col 1: Brand Info -->
                <div class="space-y-4">
                    <h3 class="font-serif text-2xl text-[#E5B2B6] tracking-wide">{{ $storeSettings['store_name'] ?? 'Desy Florist' }}</h3>
                    <p class="text-xs text-[#C4BCBC] leading-relaxed">
                        {{ $storeSettings['store_tagline'] ?? ('Artisan Floral Studio terkemuka di ' . ($storeSettings['store_city'] ?? 'Surabaya') . '. Kami menghadirkan seni merangkai bunga segar bermutu tinggi dengan sentuhan romantis, elegan, dan kehangatan tulus untuk setiap momen berharga Anda.') }}
                    </p>
                    <div class="pt-2 text-xs text-[#9B9494] space-y-2">
                        <a href="{{ $storeMapsUrl ?? 'https://www.google.com/maps?q=-7.2654,112.7483' }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="group flex items-start gap-2 hover:text-[#E5B2B6] transition cursor-pointer"
                           title="Klik untuk membuka lokasi toko di Google Maps">
                            <span class="text-sm shrink-0 group-hover:scale-125 transition-transform text-[#E5B2B6]">📍</span>
                            <span class="leading-relaxed underline decoration-dotted underline-offset-4 group-hover:decoration-solid group-hover:text-[#E5B2B6]">
                                {{ $storeSettings['store_address'] ?? 'Jl. Kayoon No. 42 (Sentra Bunga Surabaya Pusat), Kec. Genteng, Surabaya, Jawa Timur 60271' }}
                            </span>
                        </a>
                        <p class="flex items-center gap-2">
                            <span class="text-sm shrink-0 text-[#E5B2B6]">🕒</span>
                            <span>{{ $storeSettings['store_open_hours'] ?? (!empty($storeSettings['operating_hours']) ? 'Buka ' . ($storeSettings['operating_days'] ?? 'Setiap Hari') . ': ' . $storeSettings['operating_hours'] : 'Buka Setiap Hari: 08.00 - 20.00 WIB') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-[#E5B2B6] mb-4">Koleksi Bunga</h4>
                    <ul class="text-xs space-y-2.5 text-[#C4BCBC]">
                        <li><a href="{{ route('shop.category', 'hand-bouquet') }}" class="hover:text-white transition">Hand Bouquet</a></li>
                        <li><a href="{{ route('shop.category', 'table-flower') }}" class="hover:text-white transition">Bunga Meja & Vas</a></li>
                        <li><a href="{{ route('shop.category', 'bloom-box') }}" class="hover:text-white transition">Bloom Box Mewah</a></li>
                        <li><a href="{{ route('shop.category', 'standing-flower') }}" class="hover:text-white transition">Standing Flower</a></li>
                        <li><a href="{{ route('shop.category', 'bunga-papan') }}" class="hover:text-white transition">Bunga Papan Modern</a></li>
                        <li><a href="{{ route('shop.category', 'anggrek-bulan') }}" class="hover:text-white transition">Anggrek Bulan Premium</a></li>
                        <li><a href="{{ route('custom-bouquet.index') }}" class="text-[#E5B2B6] hover:underline font-medium">Bikin Buket Sendiri</a></li>
                    </ul>
                </div>

                <!-- Col 3: Customer Care -->
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-[#E5B2B6] mb-4">Layanan Pelanggan</h4>
                    <ul class="text-xs space-y-2.5 text-[#C4BCBC]">
                        <li><a href="{{ route('checkout.tracking') }}" class="hover:text-white transition">Lacak Status Pengiriman</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">Tentang Desy Florist</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Hubungi Kami</a></li>
                        <li><a href="{{ route('register') }}?type=courier" class="text-[#7B8B75] hover:underline">Gabung Sebagai Mitra Kurir</a></li>
                        <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $storeSettings['store_whatsapp'] ?? '6281234567890') }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition">WhatsApp Customer Support</a></li>
                    </ul>
                </div>

                <!-- Col 4: Trust & Payment -->
                <div class="space-y-4">
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-[#E5B2B6] mb-4">Jaminan Kualitas</h4>
                    <div class="space-y-2.5 text-xs text-[#C4BCBC]">
                        <div class="flex items-center gap-2">
                            <span class="text-[#7B8B75]">✔</span>
                            <span>100% Bunga Segar Pilihan Terbaik</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[#7B8B75]">✔</span>
                            <span>Foto Hasil Rangkaian Sebelum Dikirim</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[#7B8B75]">✔</span>
                            <span>Kartu Ucapan Personalisasi Gratis</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[#7B8B75]">✔</span>
                            <span>Kurir Khusus Bunga Berpengalaman</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="text-[11px] text-[#9B9494] mb-2 uppercase tracking-wider">Metode Pembayaran Aman</div>
                        <div class="flex flex-wrap gap-2 text-[10px] text-[#2A2626] font-bold">
                            <span class="bg-white px-2 py-1 rounded">BCA</span>
                            <span class="bg-white px-2 py-1 rounded">MANDIRI</span>
                            <span class="bg-white px-2 py-1 rounded">QRIS</span>
                            <span class="bg-white px-2 py-1 rounded">GOPAY</span>
                            <span class="bg-white px-2 py-1 rounded">OVO</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-[#9B9494] gap-4">
                <p>&copy; {{ date('Y') }} {{ $storeSettings['store_name'] ?? 'Desy Florist' }}. Seluruh Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-[#E5B2B6] transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-[#E5B2B6] transition">Syarat & Ketentuan</a>
                    <a href="https://instagram.com/{{ ltrim($storeSettings['store_instagram'] ?? 'desyflorist.official', '@') }}" target="_blank" rel="noopener noreferrer" class="hover:text-[#E5B2B6] transition">Instagram {{ '@' . ltrim($storeSettings['store_instagram'] ?? 'desyflorist.official', '@') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Sticky Bottom Navigation Bar for Customers -->
    <nav class="lg:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-[#EADFD4] z-40 flex items-center justify-around h-16 px-1 shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('home') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('home') ? 'stroke-[#B85D66]' : 'stroke-[#6B6565]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="mt-1">Beranda</span>
        </a>
        <a href="{{ route('shop.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('shop.index') || request()->routeIs('shop.category') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('shop*') ? 'stroke-[#B85D66]' : 'stroke-[#6B6565]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            <span class="mt-1">Katalog</span>
        </a>
        <a href="{{ route('custom-bouquet.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('custom-bouquet*') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
            <span class="text-xl leading-none">✨</span>
            <span class="mt-0.5">Custom</span>
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] relative {{ request()->routeIs('cart*') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('cart*') ? 'stroke-[#B85D66]' : 'stroke-[#6B6565]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <span class="mt-1">Keranjang</span>
            @if(isset($cartCount) && $cartCount > 0)
                <span class="absolute top-1 right-3 w-4 h-4 bg-[#B85D66] text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
        @auth
            <a href="{{ route('account.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('account*') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-5 h-5 rounded-full object-cover border border-[#D8BABF]">
                <span class="mt-1">Akun</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('login') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
                <svg class="w-5 h-5 stroke-[#6B6565]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="mt-1">Masuk</span>
            </a>
        @endauth
    </nav>

    <!-- Mobile Drawer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('mobileMenuBtn');
            const closeBtn = document.getElementById('customerMobileCloseBtn');
            const sidebar = document.getElementById('customerMobileSidebar');
            const backdrop = document.getElementById('customerMobileSidebarBackdrop');

            function openSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (menuBtn) menuBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>

    @include('partials.image-lightbox-modal')
    @stack('scripts')
</body>
</html>
