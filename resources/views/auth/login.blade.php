@extends('layouts.auth')

@section('title', 'Masuk ke Akun Anda — Desy Florist')

@section('content')
<div class="space-y-6">
    <div class="text-center space-y-1">
        <h1 class="font-serif text-2xl text-[#2A2626]">Selamat Datang Kembali</h1>
        <p class="text-xs text-[#6B6565]">Masuk untuk melanjutkan pesanan atau melihat riwayat bunga Anda</p>
    </div>

    @if (session('status'))
        <div class="p-3 bg-[#EEF2EB] border border-[#7B8B75] text-[#2A2626] rounded-lg text-xs">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-3 bg-[#F8E8EA] border border-[#B85D66] text-[#8F3B44] rounded-lg text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Google OAuth Button -->
    <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 py-2.5 px-4 rounded-xl border border-[#D8BABF] hover:bg-[#FDFBF9] hover:border-[#B85D66] text-xs font-semibold text-[#2A2626] transition shadow-xs">
        <svg class="w-4 h-4" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Masuk dengan Google</span>
    </a>

    <div class="relative flex items-center justify-center my-4">
        <div class="border-t border-[#EADFD4] w-full"></div>
        <span class="bg-white px-3 text-[11px] text-[#9B9494] uppercase tracking-wider absolute">atau email</span>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4 text-xs">
        @csrf

        <div>
            <label for="email" class="block font-medium text-[#2A2626] mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                placeholder="nama@email.com">
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block font-medium text-[#2A2626]">Kata Sandi</label>
                <a href="{{ route('password.request') }}" class="text-[11px] text-[#B85D66] hover:underline">Lupa kata sandi?</a>
            </div>
            <div class="relative">
                <input id="password" type="password" name="password" required
                    class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                    placeholder="••••••••">
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

        <div class="flex items-center">
            <input id="remember" type="checkbox" name="remember" class="rounded border-[#D8BABF] text-[#B85D66] focus:ring-[#B85D66]">
            <label for="remember" class="ml-2 text-xs text-[#6B6565]">Ingat saya di perangkat ini</label>
        </div>

        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
            Masuk ke Akun
        </button>
    </form>

    <div class="pt-4 border-t border-[#F3ECE4] text-center text-xs text-[#6B6565]">
        Belum memiliki akun?
        <a href="{{ route('register') }}" class="text-[#B85D66] font-semibold hover:underline">Daftar sekarang</a>
    </div>

    <!-- Demo Accounts Quick Fill -->
    <div class="p-3 bg-[#FDFBF7] rounded-xl border border-[#EADFD4] text-[11px] text-[#6B6565] space-y-1">
        <p class="font-bold text-[#2A2626]">Akun Demo untuk Pengujian:</p>
        <div class="grid grid-cols-1 gap-1 text-[10px]">
            <div>• <strong>Admin:</strong> admin@desyflorist.com | password</div>
            <div>• <strong>Kurir:</strong> courier@desyflorist.com | password</div>
            <div>• <strong>Pelanggan:</strong> customer@desyflorist.com | password</div>
        </div>
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
</script>
@endsection
