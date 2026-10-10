<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Kurir — Desy Florist')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-[#2A2626] font-sans antialiased min-h-screen flex flex-col selection:bg-[#E5B2B6] selection:text-[#2A2626] pb-16 md:pb-0">

    <!-- Courier Mobile Sidebar Backdrop -->
    <div id="courierMobileSidebarBackdrop" class="fixed inset-0 bg-[#2A2626]/50 backdrop-blur-xs z-40 hidden md:hidden transition-opacity"></div>

    <!-- Courier Mobile Sidebar Drawer -->
    <aside id="courierMobileSidebar" class="fixed inset-y-0 left-0 w-72 bg-white border-r border-[#EADFD4] z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden flex flex-col justify-between shadow-2xl">
        <div class="overflow-y-auto">
            <!-- Header & Close -->
            <div class="p-5 border-b border-[#EADFD4] flex items-center justify-between">
                <div>
                    <span class="font-serif text-xl font-bold text-[#2A2626] block">Desy Florist</span>
                    <span class="text-[9px] uppercase tracking-wider text-[#5C6A57] font-bold block bg-[#EEF2EB] px-2 py-0.5 rounded-full inline-block mt-0.5">Portal Mitra Kurir</span>
                </div>
                <button id="courierMobileCloseBtn" class="p-2 text-[#6B6565] hover:text-[#2A2626] rounded-lg" aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Courier Profile Card -->
            @php 
                $courierProfile = Auth::user()->courierProfile; 
                $activeDeliveriesCount = \App\Models\Delivery::where('courier_id', Auth::id())->whereIn('status', ['assigned', 'accepted', 'picked_up', 'on_delivery'])->count();
            @endphp
            <div class="p-4 mx-4 mt-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-2">
                <div class="flex items-center gap-3">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border border-[#A2B19D]">
                    <div class="min-w-0 flex-1">
                        <span class="font-semibold text-xs text-[#2A2626] block truncate">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-[#5C6A57] font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#5C6A57]"></span> Kurir Terverifikasi
                        </span>
                    </div>
                </div>
                @if($courierProfile)
                    <div class="pt-2 border-t border-[#EADFD4] text-[11px] text-[#6B6565] space-y-0.5">
                        <p>🛵 <strong>{{ $courierProfile->vehicle_number ?? 'Kendaraan' }}</strong></p>
                        <p class="text-[10px] text-[#9B9494]">Plat: {{ $courierProfile->plate_number ?? '-' }}</p>
                    </div>
                @endif
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-xs font-medium">
                <a href="{{ route('courier.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('courier.dashboard') ? 'bg-[#EEF2EB] text-[#5C6A57] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">📊</span> Ringkasan Dashboard
                </a>

                <a href="{{ route('courier.deliveries') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('courier.deliveries*') ? 'bg-[#EEF2EB] text-[#5C6A57] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-sm">🛵</span> Pengiriman Aktif
                    </div>
                    @if($activeDeliveriesCount > 0)
                        <span class="bg-[#5C6A57] text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">{{ $activeDeliveriesCount }}</span>
                    @endif
                </a>

                <a href="{{ route('courier.history') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('courier.history') ? 'bg-[#EEF2EB] text-[#5C6A57] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">📜</span> Riwayat Pengantaran
                </a>

                <a href="{{ route('courier.profile') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('courier.profile') ? 'bg-[#EEF2EB] text-[#5C6A57] font-bold' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                    <span class="text-sm">👤</span> Profil Kendaraan
                </a>
            </nav>
        </div>

        <!-- Mobile Sidebar Footer -->
        <div class="p-4 border-t border-[#EADFD4] text-xs space-y-2 bg-white">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left py-1 text-red-600 hover:underline">Keluar (Logout)</button>
            </form>
        </div>
    </aside>

    <!-- Courier Topbar -->
    <header class="bg-white border-b border-[#EADFD4] sticky top-0 z-30">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2 sm:gap-3">
                <button id="courierMobileMenuBtn" class="md:hidden p-2 -ml-2 text-[#2A2626] hover:text-[#5C6A57] rounded-lg focus:outline-none" aria-label="Buka Menu Kurir">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <a href="{{ route('courier.dashboard') }}" class="font-serif text-xl font-bold text-[#2A2626]">
                    Desy Florist <span class="text-xs font-sans font-medium text-[#5C6A57] bg-[#EEF2EB] px-2 py-0.5 rounded-full ml-1">Kurir</span>
                </a>
            </div>

            <div class="flex items-center gap-3 sm:gap-4 text-xs">
                <div class="flex items-center gap-2">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-7 h-7 rounded-full object-cover border border-[#A2B19D]">
                    <span class="text-[#2A2626] font-semibold truncate max-w-[100px] sm:max-w-[150px]">{{ Auth::user()->name }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="text-red-600 hover:underline">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Courier Navigation Tab Bar (Desktop / Tablet) -->
    <div class="bg-white border-b border-[#EADFD4] hidden md:block">
        <div class="max-w-4xl mx-auto px-4 flex gap-6 text-xs font-semibold overflow-x-auto">
            <a href="{{ route('courier.dashboard') }}"
               class="py-3 border-b-2 transition whitespace-nowrap {{ request()->routeIs('courier.dashboard') ? 'border-[#5C6A57] text-[#5C6A57]' : 'border-transparent text-[#6B6565] hover:text-[#2A2626]' }}">
                Dashboard
            </a>
            <a href="{{ route('courier.deliveries') }}"
               class="py-3 border-b-2 transition whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('courier.deliveries*') ? 'border-[#5C6A57] text-[#5C6A57]' : 'border-transparent text-[#6B6565] hover:text-[#2A2626]' }}">
                <span>Pengiriman Aktif</span>
                @if(isset($activeDeliveriesCount) && $activeDeliveriesCount > 0)
                    <span class="bg-[#5C6A57] text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">{{ $activeDeliveriesCount }}</span>
                @endif
            </a>
            <a href="{{ route('courier.history') }}"
               class="py-3 border-b-2 transition whitespace-nowrap {{ request()->routeIs('courier.history') ? 'border-[#5C6A57] text-[#5C6A57]' : 'border-transparent text-[#6B6565] hover:text-[#2A2626]' }}">
                Riwayat Selesai
            </a>
            <a href="{{ route('courier.profile') }}"
               class="py-3 border-b-2 transition whitespace-nowrap {{ request()->routeIs('courier.profile') ? 'border-[#5C6A57] text-[#5C6A57]' : 'border-transparent text-[#6B6565]' }}">
                Profil Kendaraan
            </a>
        </div>
    </div>

    <!-- Flash message -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto px-4 mt-4">
            <div class="bg-[#EEF2EB] border border-[#7B8B75] text-[#2A2626] p-3 rounded-xl text-xs">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Mobile Sticky Bottom Navigation Bar for Courier -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 bg-white border-t border-[#EADFD4] z-30 flex items-center justify-around h-14 px-2 shadow-lg">
        <a href="{{ route('courier.dashboard') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('courier.dashboard') ? 'text-[#5C6A57] font-bold' : 'text-[#6B6565]' }}">
            <span class="text-base leading-none">📊</span>
            <span class="mt-0.5">Beranda</span>
        </a>
        <a href="{{ route('courier.deliveries') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] relative {{ request()->routeIs('courier.deliveries*') ? 'text-[#5C6A57] font-bold' : 'text-[#6B6565]' }}">
            <span class="text-base leading-none">🛵</span>
            <span class="mt-0.5">Antaran</span>
            @if(isset($activeDeliveriesCount) && $activeDeliveriesCount > 0)
                <span class="absolute top-0.5 right-4 w-3.5 h-3.5 bg-[#5C6A57] text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                    {{ $activeDeliveriesCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('courier.history') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('courier.history') ? 'text-[#5C6A57] font-bold' : 'text-[#6B6565]' }}">
            <span class="text-base leading-none">📜</span>
            <span class="mt-0.5">Riwayat</span>
        </a>
        <a href="{{ route('courier.profile') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] {{ request()->routeIs('courier.profile') ? 'text-[#5C6A57] font-bold' : 'text-[#6B6565]' }}">
            <span class="text-base leading-none">👤</span>
            <span class="mt-0.5">Profil</span>
        </a>
    </nav>

    <footer class="bg-white border-t border-[#EADFD4] py-4 text-center text-xs text-[#9B9494] hidden md:block">
        <p>&copy; {{ date('Y') }} Desy Florist Courier System</p>
    </footer>

    <!-- Mobile Drawer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('courierMobileMenuBtn');
            const closeBtn = document.getElementById('courierMobileCloseBtn');
            const sidebar = document.getElementById('courierMobileSidebar');
            const backdrop = document.getElementById('courierMobileSidebarBackdrop');

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
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>
    @include('partials.image-lightbox-modal')
</body>
</html>
