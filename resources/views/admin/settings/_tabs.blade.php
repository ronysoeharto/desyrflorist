<div class="bg-white p-2 rounded-2xl border border-[#EADFD4] shadow-2xs mb-6 overflow-x-auto">
    <nav class="flex items-center gap-1.5 min-w-max text-xs font-semibold">
        <a href="{{ route('admin.settings.profile') }}"
           class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ request()->routeIs('admin.settings.profile') ? 'bg-[#B85D66] text-white shadow-xs' : 'text-[#6B6565] hover:text-[#2A2626] hover:bg-[#FAF8F5]' }}">
            <span>📍</span>
            <span>Profil & Lokasi Toko</span>
        </a>

        <a href="{{ route('admin.settings.about') }}"
           class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ request()->routeIs('admin.settings.about') ? 'bg-[#B85D66] text-white shadow-xs' : 'text-[#6B6565] hover:text-[#2A2626] hover:bg-[#FAF8F5]' }}">
            <span>ℹ️</span>
            <span>Konten Tentang Kami</span>
        </a>

        <a href="{{ route('admin.settings.zones') }}"
           class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ request()->routeIs('admin.settings.zones') ? 'bg-[#B85D66] text-white shadow-xs' : 'text-[#6B6565] hover:text-[#2A2626] hover:bg-[#FAF8F5]' }}">
            <span>🗺️</span>
            <span>Zona Pengiriman & Wilayah</span>
        </a>

        <a href="{{ route('admin.settings.rates') }}"
           class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ request()->routeIs('admin.settings.rates') ? 'bg-[#B85D66] text-white shadow-xs' : 'text-[#6B6565] hover:text-[#2A2626] hover:bg-[#FAF8F5]' }}">
            <span>💰</span>
            <span>Tarif Ongkir</span>
        </a>

        <a href="{{ route('admin.settings.hours') }}"
           class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ request()->routeIs('admin.settings.hours') ? 'bg-[#B85D66] text-white shadow-xs' : 'text-[#6B6565] hover:text-[#2A2626] hover:bg-[#FAF8F5]' }}">
            <span>🕒</span>
            <span>Jam Operasional</span>
        </a>

        <a href="{{ route('admin.settings.slots') }}"
           class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ request()->routeIs('admin.settings.slots') ? 'bg-[#B85D66] text-white shadow-xs' : 'text-[#6B6565] hover:text-[#2A2626] hover:bg-[#FAF8F5]' }}">
            <span>📅</span>
            <span>Jadwal & Slot Pengiriman</span>
        </a>
    </nav>
</div>
