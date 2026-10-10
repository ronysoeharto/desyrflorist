@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi — Desy Florist')

@section('content')
<div class="space-y-6">
    <div class="text-center space-y-1">
        <h1 class="font-serif text-2xl text-[#2A2626]">Atur Ulang Kata Sandi</h1>
        <p class="text-xs text-[#6B6565]">Masukkan kata sandi baru untuk akun Anda</p>
    </div>

    @if ($errors->any())
        <div class="p-3 bg-[#F8E8EA] border border-[#B85D66] text-[#8F3B44] rounded-lg text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.reset') }}" class="space-y-4 text-xs">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="block font-medium text-[#2A2626] mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm">
        </div>

        <div>
            <label for="password" class="block font-medium text-[#2A2626] mb-1">Kata Sandi Baru</label>
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
            <label for="password_confirmation" class="block font-medium text-[#2A2626] mb-1">Ulangi Kata Sandi Baru</label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                    placeholder="Konfirmasi kata sandi baru">
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

        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
            Simpan Kata Sandi Baru
        </button>
    </form>
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
