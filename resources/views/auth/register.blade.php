@extends('layouts.auth')

@section('title', 'Daftar Akun Baru — Desy Florist')

@section('content')
<div class="space-y-6">
    <div class="text-center space-y-1">
        <h1 class="font-serif text-2xl text-[#2A2626]">Buat Akun Baru</h1>
        <p class="text-xs text-[#6B6565]">Pilih tipe akun untuk mendaftar di Desy Florist</p>
    </div>

    @if ($errors->any())
        <div class="p-3 bg-[#F8E8EA] border border-[#B85D66] text-[#8F3B44] rounded-lg text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Account Role Tabs -->
    <div class="flex border-b border-[#EADFD4] text-xs font-semibold">
        <button type="button" id="tabCustomerBtn" onclick="switchRole('customer')"
            class="flex-1 py-2.5 text-center border-b-2 border-[#B85D66] text-[#B85D66] transition">
            Pelanggan
        </button>
        <button type="button" id="tabCourierBtn" onclick="switchRole('courier')"
            class="flex-1 py-2.5 text-center border-b-2 border-transparent text-[#6B6565] hover:text-[#2A2626] transition">
            Mitra Kurir
        </button>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-4 text-xs">
        @csrf
        <input type="hidden" name="role" id="roleInput" value="{{ old('role', $type ?? 'customer') }}">

        <div>
            <label for="name" class="block font-medium text-[#2A2626] mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                placeholder="Nama Anda">
        </div>

        <div>
            <label for="email" class="block font-medium text-[#2A2626] mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                placeholder="nama@email.com">
        </div>

        <div>
            <label for="phone" class="block font-medium text-[#2A2626] mb-1">Nomor WhatsApp / HP</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                placeholder="08123456789">
        </div>

        <!-- Additional Courier Fields (Shown only if role == courier) -->
        <div id="courierFields" class="space-y-4 pt-2 border-t border-[#F3ECE4] hidden">
            <div class="p-2.5 bg-[#EEF2EB] rounded-lg text-[11px] text-[#5C6A57]">
                ℹ️ Pendaftaran mitra kurir memerlukan verifikasi berkas oleh admin sebelum akun dapat diaktifkan.
            </div>

            <div>
                <label for="identity_number" class="block font-medium text-[#2A2626] mb-1">Nomor KTP / NIK</label>
                <input id="identity_number" type="text" name="identity_number" value="{{ old('identity_number') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                    placeholder="3171xxxxxxxxxxxx">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="vehicle_type" class="block font-medium text-[#2A2626] mb-1">Tipe Kendaraan</label>
                    <select id="vehicle_type" name="vehicle_type" class="w-full px-3 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-xs">
                        <option value="motorcycle">Sepeda Motor</option>
                        <option value="car">Mobil</option>
                        <option value="van">Blind Van / Pick Up</option>
                    </select>
                </div>
                <div>
                    <label for="plate_number" class="block font-medium text-[#2A2626] mb-1">Nomor Plat</label>
                    <input id="plate_number" type="text" name="plate_number" value="{{ old('plate_number') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                        placeholder="B 1234 ABC">
                </div>
            </div>

            <div>
                <label for="vehicle_number" class="block font-medium text-[#2A2626] mb-1">Merk & Model Kendaraan</label>
                <input id="vehicle_number" type="text" name="vehicle_number" value="{{ old('vehicle_number') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                    placeholder="Honda Vario / Toyota Calya">
            </div>

            <div>
                <label for="address" class="block font-medium text-[#2A2626] mb-1">Alamat Domisili Lengkap</label>
                <textarea id="address" name="address" rows="2"
                    class="w-full px-3.5 py-2 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] bg-[#FAF8F5]/40 text-xs"
                    placeholder="Jalan, RT/RW, Kelurahan, Kecamatan">{{ old('address') }}</textarea>
            </div>
        </div>

        <div>
            <label for="password" class="block font-medium text-[#2A2626] mb-1">Kata Sandi</label>
            <div class="relative">
                <input id="password" type="password" name="password" required
                    class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                    placeholder="Minimal 8 karakter">
                <button type="button" onclick="togglePasswordVisibility('password', this)"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[#9B9494] hover:text-[#B85D66] focus:outline-none transition p-1"
                    title="Lihat / Sembunyikan Kata Sandi" aria-label="Toggle password visibility">
                    <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="block font-medium text-[#2A2626] mb-1">Konfirmasi Kata Sandi</label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                    placeholder="Ulangi kata sandi">
                <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[#9B9494] hover:text-[#B85D66] focus:outline-none transition p-1"
                    title="Lihat / Sembunyikan Konfirmasi Kata Sandi" aria-label="Toggle confirm password visibility">
                    <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" id="submitBtn" class="w-full py-3 px-4 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
            Daftar Sebagai Pelanggan
        </button>
    </form>

    <div class="pt-4 border-t border-[#F3ECE4] text-center text-xs text-[#6B6565]">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="text-[#B85D66] font-semibold hover:underline">Masuk ke akun</a>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;

        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            if (eyeOpen) eyeOpen.classList.add('hidden');
            if (eyeClosed) eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            if (eyeOpen) eyeOpen.classList.remove('hidden');
            if (eyeClosed) eyeClosed.classList.add('hidden');
        }
    }
    function switchRole(role) {
        const roleInput = document.getElementById('roleInput');
        const courierFields = document.getElementById('courierFields');
        const submitBtn = document.getElementById('submitBtn');
        const tabCustomerBtn = document.getElementById('tabCustomerBtn');
        const tabCourierBtn = document.getElementById('tabCourierBtn');

        roleInput.value = role;

        if (role === 'courier') {
            courierFields.classList.remove('hidden');
            submitBtn.textContent = 'Ajukan Pendaftaran Mitra Kurir';
            tabCourierBtn.className = 'flex-1 py-2.5 text-center border-b-2 border-[#B85D66] text-[#B85D66] transition';
            tabCustomerBtn.className = 'flex-1 py-2.5 text-center border-b-2 border-transparent text-[#6B6565] hover:text-[#2A2626] transition';
        } else {
            courierFields.classList.add('hidden');
            submitBtn.textContent = 'Daftar Sebagai Pelanggan';
            tabCustomerBtn.className = 'flex-1 py-2.5 text-center border-b-2 border-[#B85D66] text-[#B85D66] transition';
            tabCourierBtn.className = 'flex-1 py-2.5 text-center border-b-2 border-transparent text-[#6B6565] hover:text-[#2A2626] transition';
        }
    }

    // Initialize state
    document.addEventListener('DOMContentLoaded', () => {
        const initialRole = document.getElementById('roleInput').value;
        if (initialRole === 'courier') {
            switchRole('courier');
        }
    });
</script>
@endsection
