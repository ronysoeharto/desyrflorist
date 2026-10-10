@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi — Desy Florist')

@section('content')
<div class="space-y-6">
    <div class="text-center space-y-1">
        <h1 class="font-serif text-2xl text-[#2A2626]">Pemulihan Kata Sandi</h1>
        <p class="text-xs text-[#6B6565]">Masukkan email Anda untuk menerima tautan pemulihan kata sandi</p>
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

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4 text-xs">
        @csrf
        <div>
            <label for="email" class="block font-medium text-[#2A2626] mb-1">Alamat Email Terdaftar</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-3.5 py-2.5 rounded-xl border border-[#D8BABF] focus:outline-none focus:ring-1 focus:ring-[#B85D66] focus:border-[#B85D66] bg-[#FAF8F5]/40 text-sm"
                placeholder="nama@email.com">
        </div>

        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#B85D66] hover:bg-[#8F3B44] text-white text-xs font-semibold tracking-wider uppercase transition shadow-sm">
            Kirim Tautan Pemulihan
        </button>
    </form>

    <div class="pt-4 border-t border-[#F3ECE4] text-center text-xs text-[#6B6565]">
        <a href="{{ route('login') }}" class="text-[#B85D66] font-semibold hover:underline">← Kembali ke Halaman Masuk</a>
    </div>
</div>
@endsection
