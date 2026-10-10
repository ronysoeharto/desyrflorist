@extends('layouts.admin')

@section('title', 'Kelola Konten Tentang Kami — Desy Florist Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl text-[#2A2626]">Konten Halaman Tentang Kami</h1>
            <p class="text-xs text-[#6B6565] mt-0.5">
                Kelola narasi kisah florist, filosofi, foto studio, dan 3 nilai komitmen mutu yang tampil di halaman publik Tentang Kami.
            </p>
        </div>
        <a href="{{ route('about') }}" target="_blank"
           class="px-4 py-2 bg-white border border-[#D8BABF] text-[#2A2626] rounded-xl text-xs font-semibold hover:border-[#5C6A57] transition flex items-center gap-1.5 shadow-2xs">
            <span>👁️ Lihat Halaman Publik</span>
            <span>↗</span>
        </a>
    </div>

    <!-- Navigation Tabs -->
    @include('admin.settings._tabs')

    <form action="{{ route('admin.settings.about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Main Content Form (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Section 1: Header Hero Text -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                    <div class="border-b border-[#EADFD4] pb-3">
                        <h2 class="font-serif text-xl text-[#2A2626]">1. Header & Subjudul Halaman</h2>
                        <span class="text-[11px] text-[#6B6565]">Judul utama yang muncul pada bagian teratas halaman Tentang Kami</span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Tagline Kecil Sub-Header</label>
                            <input type="text" name="about_tagline"
                                   value="{{ old('about_tagline', $settings['about_tagline'] ?? ('Artisan Florist ' . ($settings['store_city'] ?? 'Surabaya'))) }}"
                                   placeholder="Contoh: Artisan Florist Surabaya"
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Judul Utama Halaman *</label>
                            <input type="text" name="about_title" required
                                   value="{{ old('about_title', $settings['about_title'] ?? 'Kisah & Dedikasi Desy Florist') }}"
                                   placeholder="Contoh: Kisah & Dedikasi Desy Florist"
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-sm font-serif">
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Deskripsi Singkat Subjudul</label>
                            <textarea name="about_subtitle" rows="2"
                                      placeholder="Keterangan singkat kisah florist Anda..."
                                      class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-xs">{{ old('about_subtitle', $settings['about_subtitle'] ?? 'Menghubungkan hati lewat keindahan alami bunga segar bermutu tinggi sejak tahun 2018.') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Story & Photo -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                    <div class="border-b border-[#EADFD4] pb-3">
                        <h2 class="font-serif text-xl text-[#2A2626]">2. Cerita & Foto Utama Studio</h2>
                        <span class="text-[11px] text-[#6B6565]">Kisah naratif filosofi perangkaian bunga dan foto studio toko</span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Judul Seksi Cerita</label>
                            <input type="text" name="about_story_title"
                                   value="{{ old('about_story_title', $settings['about_story_title'] ?? 'Filosofi Kami') }}"
                                   placeholder="Contoh: Filosofi Kami / Perjalanan Kami"
                                   class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Isi Cerita / Paragraf *</label>
                            <textarea name="about_story_content" rows="6" required
                                      placeholder="Ketik narasi filosofi atau sejarah florist Anda di sini..."
                                      class="w-full p-2.5 rounded-xl border border-[#D8BABF] focus:ring-1 focus:ring-[#B85D66] text-xs leading-relaxed">{{ old('about_story_content', $settings['about_story_content'] ?? "Bagi kami, bunga bukan sekadar hadiah sementara, melainkan sebuah bahasa universal yang mampu menyampaikan rasa syukur, simpati, cinta, dan apresiasi tulus yang seringkali sulit terucap dengan kata-kata.\n\nSetiap bunga di Desy Florist dikurasi setiap pagi dari perkebunan bunga terpercaya di Bandung, Malang, hingga varietas impor pilihan. Kami merangkai setiap tangkai dengan pertimbangan palet warna yang harmonis, tekstur dedaunan segar, dan estetika feminin modern.") }}</textarea>
                        </div>

                        <div>
                            <label class="block font-semibold text-[#2A2626] mb-1">Unggah / Ganti Foto Utama Studio</label>
                            <input type="file" name="about_image" accept="image/*"
                                   class="w-full p-2 border border-[#D8BABF] rounded-xl text-xs bg-[#FAF8F5]">
                            <span class="text-[10px] text-[#9B9494] block mt-1">Format: JPG, PNG, WEBP. Maksimal 5 MB.</span>
                        </div>
                    </div>
                </div>

                <!-- Section 3: 3 Commitment Values -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                    <div class="border-b border-[#EADFD4] pb-3">
                        <h2 class="font-serif text-xl text-[#2A2626]">3. Nilai Komitmen Mutu Toko (3 Kartu)</h2>
                        <span class="text-[11px] text-[#6B6565]">Tiga poin keunggulan utama toko Desy Florist</span>
                    </div>

                    <div class="space-y-4">
                        <!-- Card 1 -->
                        <div class="p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-2">
                            <span class="font-bold text-[#B85D66] block">Kartu 1: 🌿 Keunggulan Bunga</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-[#6B6565] mb-1">Judul Kartu 1</label>
                                    <input type="text" name="about_val1_title"
                                           value="{{ old('about_val1_title', $settings['about_val1_title'] ?? 'Kesegaran Terjamin') }}"
                                           class="w-full p-2 rounded-lg border border-[#D8BABF] text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] text-[#6B6565] mb-1">Deskripsi Kartu 1</label>
                                    <input type="text" name="about_val1_desc"
                                           value="{{ old('about_val1_desc', $settings['about_val1_desc'] ?? 'Bunga dipetik dan dirangkai tepat pada hari pengiriman untuk ketahanan mekar maksimal.') }}"
                                           class="w-full p-2 rounded-lg border border-[#D8BABF] text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-2">
                            <span class="font-bold text-[#5C6A57] block">Kartu 2: 🎨 Sentuhan Florist</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-[#6B6565] mb-1">Judul Kartu 2</label>
                                    <input type="text" name="about_val2_title"
                                           value="{{ old('about_val2_title', $settings['about_val2_title'] ?? 'Sentuhan Floral Artist') }}"
                                           class="w-full p-2 rounded-lg border border-[#D8BABF] text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] text-[#6B6565] mb-1">Deskripsi Kartu 2</label>
                                    <input type="text" name="about_val2_desc"
                                           value="{{ old('about_val2_desc', $settings['about_val2_desc'] ?? 'Dirangkai oleh florist berpengalaman yang menguasai teknik wrapping Korea modern dan komposisi warna elegan.') }}"
                                           class="w-full p-2 rounded-lg border border-[#D8BABF] text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="p-4 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] space-y-2">
                            <span class="font-bold text-[#C5A059] block">Kartu 3: 🛵 Pengiriman Spesial</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-[#6B6565] mb-1">Judul Kartu 3</label>
                                    <input type="text" name="about_val3_title"
                                           value="{{ old('about_val3_title', $settings['about_val3_title'] ?? 'Pengantaran Berhati-hati') }}"
                                           class="w-full p-2 rounded-lg border border-[#D8BABF] text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] text-[#6B6565] mb-1">Deskripsi Kartu 3</label>
                                    <input type="text" name="about_val3_desc"
                                           value="{{ old('about_val3_desc', $settings['about_val3_desc'] ?? 'Mitra kurir kami terlatih membawa buket dan vas bunga tegak sehingga tiba dalam kondisi sempurna.') }}"
                                           class="w-full p-2 rounded-lg border border-[#D8BABF] text-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="py-3 px-8 bg-[#B85D66] hover:bg-[#8F3B44] text-white rounded-xl font-bold uppercase tracking-wider transition shadow-sm text-xs">
                        💾 Simpan Perubahan Halaman Tentang Kami
                    </button>
                </div>

            </div>

            <!-- Right: Live Image Preview & Info Box (5 cols) -->
            <div class="lg:col-span-5 space-y-6 sticky top-20">
                
                <div class="bg-white p-6 rounded-2xl border border-[#EADFD4] shadow-xs space-y-4 text-xs">
                    <h3 class="font-serif text-lg text-[#2A2626]">Foto Studio Saat Ini</h3>

                    <div class="aspect-[4/3] rounded-xl overflow-hidden border border-[#EADFD4] bg-[#FAF8F5] shadow-inner">
                        @php
                            $imgSrc = !empty($settings['about_image']) ? asset('storage/' . $settings['about_image']) : 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=800&q=80';
                        @endphp
                        <img src="{{ $imgSrc }}" alt="Pratinjau Foto Studio" class="w-full h-full object-cover">
                    </div>

                    <div class="p-3 bg-[#FAF8F5] rounded-xl border border-[#EADFD4] text-[11px] text-[#6B6565] leading-relaxed">
                        💡 Foto di atas akan tampil secara otomatis pada halaman publik <strong>Tentang Kami</strong> bersandingan dengan teks cerita filosofi toko Anda.
                    </div>
                </div>

                <div class="bg-[#EEF2EB] p-6 rounded-2xl border border-[#7B8B75]/40 text-xs space-y-2">
                    <span class="font-bold text-[#2A2626] block text-sm">ℹ️ Informasi Penting</span>
                    <p class="text-[#6B6565] leading-relaxed">
                        Seluruh teks dan foto yang Anda simpan di halaman ini akan langsung disinkronkan ke halaman publik <code>/about</code> tanpa perlu reload aplikasi.
                    </p>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection
