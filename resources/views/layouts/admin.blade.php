<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel — Desy Florist')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-[#2A2626] font-sans antialiased min-h-screen flex flex-col lg:flex-row selection:bg-[#E5B2B6] selection:text-[#2A2626] overflow-x-hidden pb-16 lg:pb-0">

    <!-- Mobile Sidebar Backdrop -->
    <div id="adminMobileSidebarBackdrop" class="fixed inset-0 bg-[#2A2626]/50 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    <!-- Mobile Sidebar Drawer -->
    <aside id="adminMobileSidebar" class="fixed inset-y-0 left-0 w-72 bg-white border-r border-[#EADFD4] z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden flex flex-col justify-between shadow-2xl">
        <div class="overflow-y-auto">
            <!-- Studio Admin Logo & Close Button -->
            <div class="p-5 border-b border-[#EADFD4] flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <span class="font-serif text-xl font-normal text-[#2A2626] block">Desy Florist</span>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-[#B85D66] font-bold block -mt-0.5">Management Studio</span>
                </a>
                <button id="adminMobileCloseBtn" class="p-2 text-[#6B6565] hover:text-[#2A2626] rounded-lg" aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Admin User Card in Mobile Sidebar -->
            <div class="p-4 mx-4 mt-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] flex items-center gap-3">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-[#D8BABF]">
                <div class="min-w-0 flex-1">
                    <span class="font-semibold text-xs text-[#2A2626] block truncate">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-[#B85D66] font-medium block">Studio Administrator</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-xs font-medium">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">📊</span> Dashboard
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-sm">📦</span> Pesanan Bunga
                    </div>
                    @php $pendingCount = \App\Models\Order::whereIn('status', ['pending', 'paid'])->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="bg-[#B85D66] text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">🌸</span> Katalog Produk
                </a>

                <a href="{{ route('admin.custom-options.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.custom-options*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">💐</span> Kustomisasi Buket
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.categories*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">📑</span> Kategori
                </a>

                <a href="{{ route('admin.banners.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.banners*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">🖼️</span> Banner Beranda
                </a>

                <a href="{{ route('admin.deliveries.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.deliveries*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-sm">🚚</span> Manajemen Pengiriman
                    </div>
                    @php $activeDel = \App\Models\Delivery::whereIn('status', ['assigned', 'picked_up', 'on_delivery'])->count(); @endphp
                    @if($activeDel > 0)
                        <span class="bg-purple-600 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">{{ $activeDel }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.couriers.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.couriers*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-sm">🛵</span> Mitra Kurir
                    </div>
                    @php $pendingKurir = \App\Models\CourierProfile::where('status', 'pending')->count(); @endphp
                    @if($pendingKurir > 0)
                        <span class="bg-[#C5A059] text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">{{ $pendingKurir }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.promotions.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.promotions*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">🏷️</span> Kupon Promo
                </a>

                <a href="{{ route('admin.inventory.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.inventory*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">📋</span> Stok & Inventori
                </a>

                <a href="{{ route('admin.customers.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.customers*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">👥</span> Pelanggan
                </a>

                <!-- Group Pengaturan Lokasi & Toko -->
                <div class="pt-2 border-t border-[#EADFD4]/60">
                    <span class="px-3 text-[9px] uppercase tracking-wider font-bold text-[#9B9494] block mb-1">
                        Konfigurasi Florist
                    </span>
                    <a href="{{ route('admin.settings.profile') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.profile') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">📍</span> Profil & Lokasi Toko
                    </a>
                    <a href="{{ route('admin.settings.about') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.about') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">ℹ️</span> Tentang Kami
                    </a>
                    <a href="{{ route('admin.settings.zones') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.zones') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">🗺️</span> Zona & Wilayah
                    </a>
                    <a href="{{ route('admin.settings.rates') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.rates') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">💰</span> Tarif Ongkir
                    </a>
                    <a href="{{ route('admin.settings.hours') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.hours') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">🕒</span> Jam Operasional
                    </a>
                    <a href="{{ route('admin.settings.slots') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.slots') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">📅</span> Jadwal & Slot
                    </a>
                </div>
            </nav>
        </div>

        <!-- Mobile Sidebar Footer -->
        <div class="p-4 border-t border-[#EADFD4] text-xs space-y-2 bg-white">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between text-[#6B6565] hover:text-[#B85D66] transition py-1">
                <span>Lihat Website Toko</span>
                <span>↗</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left py-1 text-red-600 hover:underline">Keluar (Logout)</button>
            </form>
        </div>
    </aside>

    <!-- Desktop Sidebar (Warm Floral Studio Style) -->
    <aside class="w-64 bg-white border-r border-[#EADFD4] flex flex-col justify-between flex-shrink-0 min-h-screen sticky top-0 hidden lg:flex">
        <div class="overflow-y-auto">
            <!-- Studio Admin Logo -->
            <div class="p-6 border-b border-[#EADFD4]">
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <span class="font-serif text-2xl font-normal text-[#2A2626] block">Desy Florist</span>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-[#B85D66] font-bold block -mt-0.5">Management Studio</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-xs font-medium">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">📊</span> Dashboard
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-sm">📦</span> Pesanan Bunga
                    </div>
                    @php $pendingCount = \App\Models\Order::whereIn('status', ['pending', 'paid'])->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="bg-[#B85D66] text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">🌸</span> Katalog Produk
                </a>

                <a href="{{ route('admin.custom-options.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.custom-options*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">💐</span> Kustomisasi Buket
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.categories*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">📑</span> Kategori
                </a>

                <a href="{{ route('admin.banners.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.banners*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">🖼️</span> Banner Beranda
                </a>

                <a href="{{ route('admin.deliveries.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.deliveries*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-sm">🚚</span> Manajemen Pengiriman
                    </div>
                    @if($activeDel > 0)
                        <span class="bg-purple-600 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">{{ $activeDel }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.couriers.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.couriers*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-sm">🛵</span> Mitra Kurir
                    </div>
                    @if($pendingKurir > 0)
                        <span class="bg-[#C5A059] text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">{{ $pendingKurir }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.promotions.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.promotions*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">🏷️</span> Kupon Promo
                </a>

                <a href="{{ route('admin.inventory.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.inventory*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">📋</span> Stok & Inventori
                </a>

                <a href="{{ route('admin.customers.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.customers*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">👥</span> Pelanggan
                </a>

                <!-- Group Pengaturan Lokasi & Toko -->
                <div class="pt-2 border-t border-[#EADFD4]/60">
                    <span class="px-3 text-[9px] uppercase tracking-wider font-bold text-[#9B9494] block mb-1">
                        Konfigurasi Florist
                    </span>
                    <a href="{{ route('admin.settings.profile') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.profile') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">📍</span> Profil & Lokasi Toko
                    </a>
                    <a href="{{ route('admin.settings.about') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.about') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">ℹ️</span> Tentang Kami
                    </a>
                    <a href="{{ route('admin.settings.zones') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.zones') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">🗺️</span> Zona & Wilayah
                    </a>
                    <a href="{{ route('admin.settings.rates') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.rates') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">💰</span> Tarif Ongkir
                    </a>
                    <a href="{{ route('admin.settings.hours') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.hours') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">🕒</span> Jam Operasional
                    </a>
                    <a href="{{ route('admin.settings.slots') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings.slots') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                        <span class="text-sm">📅</span> Jadwal & Slot
                    </a>
                </div>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-[#EADFD4] text-xs space-y-2">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between text-[#6B6565] hover:text-[#B85D66] transition">
                <span>Lihat Website Toko</span>
                <span>↗</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left py-1 text-red-600 hover:underline">Keluar (Logout)</button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 w-full overflow-x-hidden">
        
        <!-- Header Topbar -->
        <header class="bg-white border-b border-[#EADFD4] h-16 flex items-center justify-between px-3.5 sm:px-6 sticky top-0 z-20 w-full shadow-2xs">
            <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                <button id="adminMobileMenuBtn" class="lg:hidden p-2 -ml-1 text-[#2A2626] hover:text-[#B85D66] hover:bg-[#FAF8F5] rounded-xl focus:outline-none transition flex-shrink-0" aria-label="Buka Menu Admin">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex flex-col min-w-0">
                    <span class="font-serif text-lg sm:text-xl font-normal text-[#2A2626] truncate">Desy Florist</span>
                    <span class="text-[9px] sm:text-xs text-[#9B9494] truncate">Studio Management</span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4 text-xs flex-shrink-0">
                <a href="{{ route('home') }}" target="_blank" class="text-[#6B6565] hover:text-[#B85D66] hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#EADFD4] bg-[#FAF8F5] hover:border-[#B85D66] transition">
                    <span>Lihat Toko Publik</span>
                    <span>↗</span>
                </a>
                <div class="flex items-center gap-1.5 sm:gap-2 bg-[#FAF8F5] sm:bg-transparent py-1 px-2 sm:p-0 rounded-full sm:rounded-none border border-[#EADFD4] sm:border-0">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-7 h-7 rounded-full object-cover border border-[#D8BABF] flex-shrink-0">
                    <span class="text-[#2A2626] font-semibold truncate max-w-[75px] sm:max-w-[130px]">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Flash messages -->
        @if(session('success'))
            <div class="px-4 sm:px-6 pt-4">
                <div class="bg-[#EEF2EB] border border-[#7B8B75] text-[#2A2626] p-3 rounded-xl text-xs flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-[#6B6565]">&times;</button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="px-4 sm:px-6 pt-4">
                <div class="bg-[#F8E8EA] border border-[#B85D66] text-[#8F3B44] p-3 rounded-xl text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <main class="p-3.5 sm:p-6 md:p-8 flex-grow w-full max-w-full overflow-x-hidden">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Sticky Bottom Navigation Bar for Admin -->
    <nav class="lg:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-[#EADFD4] z-30 flex items-center justify-around h-14 px-1 shadow-lg">
        <a href="{{ route('admin.dashboard') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('admin.dashboard') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
            <span class="text-base leading-none">📊</span>
            <span class="mt-0.5">Beranda</span>
        </a>
        <a href="{{ route('admin.orders.index') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] relative {{ request()->routeIs('admin.orders*') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
            <span class="text-base leading-none">📦</span>
            <span class="mt-0.5">Pesanan</span>
            @php $pendingCount = \App\Models\Order::whereIn('status', ['pending', 'paid'])->count(); @endphp
            @if($pendingCount > 0)
                <span class="absolute top-0.5 right-3 min-w-4 h-4 px-1 bg-[#B85D66] text-white text-[9px] font-bold rounded-full flex items-center justify-center leading-none">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('admin.products*') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
            <span class="text-base leading-none">🌸</span>
            <span class="mt-0.5">Produk</span>
        </a>
        <a href="{{ route('admin.custom-options.index') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('admin.custom-options*') ? 'text-[#B85D66] font-bold' : 'text-[#6B6565]' }}">
            <span class="text-base leading-none">💐</span>
            <span class="mt-0.5">Kustom</span>
        </a>
        <button type="button" id="adminBottomMenuBtn"
           class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] text-[#6B6565] hover:text-[#B85D66] focus:outline-none">
            <span class="text-base leading-none">☰</span>
            <span class="mt-0.5">Menu</span>
        </button>
    </nav>

    <!-- Mobile Drawer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('adminMobileMenuBtn');
            const bottomMenuBtn = document.getElementById('adminBottomMenuBtn');
            const closeBtn = document.getElementById('adminMobileCloseBtn');
            const sidebar = document.getElementById('adminMobileSidebar');
            const backdrop = document.getElementById('adminMobileSidebarBackdrop');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (menuBtn) menuBtn.addEventListener('click', openSidebar);
            if (bottomMenuBtn) bottomMenuBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>
    @include('partials.image-lightbox-modal')
</body>
</html>
