<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk / Daftar — Desy Florist')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-[#2A2626] font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-[#E5B2B6] selection:text-[#2A2626]">

    <!-- Simple Top Logo Bar -->
    <header class="py-6 px-4 text-center border-b border-[#EADFD4]">
        <a href="{{ route('home') }}" class="inline-block">
            <span class="font-serif text-3xl font-normal text-[#2A2626] tracking-wide block">Desy Florist</span>
        </a>
    </header>

    <!-- Main Auth Form Container -->
    <main class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-[#EADFD4] p-8 sm:p-10">
            @yield('content')
        </div>
    </main>

    <!-- Footer Simple -->
    <footer class="py-6 text-center text-xs text-[#9B9494] border-t border-[#EADFD4]">
        <p>&copy; {{ date('Y') }} Desy Florist. Bunga segar diantar dengan kehangatan.</p>
    </footer>

</body>
</html>
