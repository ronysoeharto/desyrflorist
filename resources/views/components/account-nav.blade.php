<!-- Customer Account Sub-Navigation (Responsive Mobile Pills + Desktop Menu) -->
<div class="bg-white border-b border-[#EADFD4] mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 sm:gap-4 overflow-x-auto py-2.5 text-xs font-semibold scrollbar-none" aria-label="Navigasi Akun">
            <a href="{{ route('account.dashboard') }}"
               class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl whitespace-nowrap transition {{ request()->routeIs('account.dashboard') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold shadow-2xs' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                <span>📊</span>
                <span>Ringkasan</span>
            </a>

            <a href="{{ route('account.orders') }}"
               class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl whitespace-nowrap transition {{ request()->routeIs('account.orders*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold shadow-2xs' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                <span>📦</span>
                <span>Pesanan Bunga</span>
            </a>

            <a href="{{ route('account.addresses') }}"
               class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl whitespace-nowrap transition {{ request()->routeIs('account.addresses') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold shadow-2xs' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                <span>📍</span>
                <span>Buku Alamat</span>
            </a>

            <a href="{{ route('wishlist.index') }}"
               class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl whitespace-nowrap transition {{ request()->routeIs('wishlist*') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold shadow-2xs' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                <span>🤍</span>
                <span>Wishlist Favorit</span>
            </a>

            <a href="{{ route('account.profile') }}"
               class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl whitespace-nowrap transition {{ request()->routeIs('account.profile') ? 'bg-[#F8E8EA] text-[#B85D66] font-bold shadow-2xs' : 'text-[#6B6565] hover:bg-[#FAF8F5] hover:text-[#2A2626]' }}">
                <span>⚙️</span>
                <span>Pengaturan Profil</span>
            </a>
        </nav>
    </div>
</div>
