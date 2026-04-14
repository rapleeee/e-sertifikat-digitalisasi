<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Certisat') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .brutal-shadow { box-shadow: 5px 5px 0px 0px #000; }
        .brutal-shadow:hover { box-shadow: 3px 3px 0px 0px #000; transform: translate(2px, 2px); }
        .brutal-shadow-lg { box-shadow: 8px 8px 0px 0px #000; }
        .brutal-shadow-amber { box-shadow: 5px 5px 0px 0px #f59e0b; }
        .marquee { animation: marquee 20s linear infinite; }
        @keyframes marquee { 0% { transform: translateX(0%); } 100% { transform: translateX(-50%); } }
        .float-anim { animation: floaty 3s ease-in-out infinite; }
        @keyframes floaty { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    </style>
</head>
<body class="bg-amber-50 text-black antialiased">

@include('profile.partials.navbar-user')

<main class="pt-20 pb-0 min-h-screen">

    <!-- Marquee Banner -->

    <!-- Hero -->
    <section class="border-b-[3px] border-black">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 py-16 sm:py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div class="space-y-6">
                    <div class="inline-block border-[3px] border-black bg-white px-4 py-2 font-black text-sm uppercase tracking-widest brutal-shadow">
                        <span class="text-orange-500">SMK</span> — Portal Sertifikat Digital Pesat
                    </div>

                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-black leading-[1.1] uppercase tracking-tight">
                        Cek 
                        <span class="bg-amber-400 px-2 inline-block border-[3px] border-black -rotate-1">Sertifikat</span> 
                        dengan Mudah
                    </h1>
                    <p class="text-base sm:text-lg text-gray-700 max-w-xl leading-relaxed">
                        Masukkan <span class="font-black bg-amber-200 px-1 border-b-2 border-black">NIS</span> atau <span class="font-black bg-amber-200 px-1 border-b-2 border-black">Nama Siswa</span>
                        untuk mencari sertifikat yang diterbitkan langsung oleh sekolah secara resmi.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('pencarian.sertifikat') }}" class="inline-flex items-center gap-2 border-[3px] border-black bg-orange-500 px-6 py-3 text-sm font-black text-white uppercase tracking-wide brutal-shadow transition-all hover:bg-orange-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0014 0z" />
                            </svg>
                            Cek Sertifikat
                        </a>
                        {{-- <a href="{{ route('pencarian.eligible') }}" class="inline-flex items-center gap-2 border-[3px] border-black bg-white px-6 py-3 text-sm font-black text-black uppercase tracking-wide brutal-shadow transition-all hover:bg-amber-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Cek Eligible PTN & TKA
                        </a> --}}
                    </div>
                </div>

                <div class="flex justify-center md:justify-end">
                    <div class="w-full max-w-sm float-anim">
                        <div class="border-[3px] border-black brutal-shadow-lg bg-white p-2 rotate-2">
                            <img
                                src="{{ asset('images/Desian-Web.jpg') }}"
                                alt="Ilustrasi sertifikat"
                                class="w-full h-auto object-cover"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <div class="bg-black text-amber-400 border-b-[2px] border-amber-400 py-2 overflow-hidden">
        <div class="marquee whitespace-nowrap flex gap-8 text-sm font-black uppercase tracking-widest">
            <span>★ Portal Informasi Siswa SMK Informatika Pesat ★ Cek Sertifikat ★ Cek Eligible PTN ★ Hasil TKA ★ Portal Informasi Siswa SMK Informatika Pesat ★ Cek Sertifikat ★ Cek Eligible PTN ★ Hasil TKA ★</span>
        </div>
    </div> -->

    <!-- Stats Bar -->
    <section class="bg-black text-white border-b-[3px] border-amber-400">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x-[2px] divide-gray-700">
                <div class="py-6 text-center">
                    <p class="text-2xl sm:text-3xl font-black text-amber-400">100%</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mt-1">Resmi</p>
                </div>
                <div class="py-6 text-center">
                    <p class="text-2xl sm:text-3xl font-black text-amber-400">24/7</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mt-1">Akses</p>
                </div>
                <div class="py-6 text-center">
                    <p class="text-2xl sm:text-3xl font-black text-amber-400">Instan</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mt-1">Pencarian</p>
                </div>
                <div class="py-6 text-center">
                    <p class="text-2xl sm:text-3xl font-black text-amber-400">Aman</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mt-1">& Terpercaya</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Cards -->
    <section class="border-b-[3px] border-black">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 py-16 sm:py-20">
            <div class="text-center space-y-3 mb-14">
                <div class="inline-block border-[3px] border-black bg-amber-400 px-6 py-2 font-black text-sm uppercase tracking-widest brutal-shadow -rotate-1">
                    Cara Menggunakan
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-black uppercase tracking-tight pt-4">
                    Panduan Penggunaan
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-base">Ikuti langkah-langkah berikut untuk memaksimalkan platform kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card 1 -->
                <div class="border-[3px] border-black bg-white p-6 brutal-shadow transition-all group hover:bg-amber-50">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 flex h-14 w-14 items-center justify-center border-[3px] border-black bg-orange-500 text-white font-black text-xl">
                            01
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-black text-lg uppercase tracking-tight">Cari Sertifikat</h3>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                Masukkan NIS atau nama siswa pada menu <span class="font-black border-b-2 border-orange-500">Cari Sertifikat</span> untuk menemukan sertifikat yang sudah diterbitkan oleh sekolah secara instan.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Cek Kelulusan (disabled - sertifikat only)
                <div class="border-[3px] border-black bg-white p-6 brutal-shadow transition-all group hover:bg-amber-50">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 flex h-14 w-14 items-center justify-center border-[3px] border-black bg-amber-400 text-black font-black text-xl">
                            02
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-black text-lg uppercase tracking-tight">Cek Kelulusan</h3>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                Gunakan fitur <span class="font-black border-b-2 border-amber-500">Cek Kelulusan</span> untuk melihat status kelulusan siswa beserta informasi terkait prestasi dan pencapaian akademik.
                            </p>
                        </div>
                    </div>
                </div> --}}

                {{-- Card 3: Cek Eligible PTN (disabled - sertifikat only)
                <div class="border-[3px] border-black bg-white p-6 brutal-shadow transition-all group hover:bg-amber-50">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 flex h-14 w-14 items-center justify-center border-[3px] border-black bg-lime-400 text-black font-black text-xl">
                            03
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-black text-lg uppercase tracking-tight">Cek Eligible PTN</h3>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                Cek apakah kamu memenuhi syarat eligibilitas untuk melanjutkan ke Perguruan Tinggi Negeri melalui fitur <span class="font-black border-b-2 border-lime-500">Cek Eligible PTN</span>.
                            </p>
                        </div>
                    </div>
                </div> --}}

                <!-- Card 2 -->
                <div class="border-[3px] border-black bg-white p-6 brutal-shadow transition-all group hover:bg-amber-50">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 flex h-14 w-14 items-center justify-center border-[3px] border-black bg-amber-400 text-black font-black text-xl">
                            02
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-black text-lg uppercase tracking-tight">Terpercaya & Aman</h3>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                Semua data dikelola langsung oleh sekolah dengan jaminan keakuratan dan keamanan. Akses tersedia kapan saja, di mana saja.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-orange-500 border-b-[3px] border-black">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-10 py-16 text-center">
            <h2 class="text-3xl sm:text-4xl font-black text-white uppercase tracking-tight">
                Mulai Cek Sekarang!
            </h2>
            <p class="text-orange-100 text-base mt-3 max-w-lg mx-auto">
                Temukan sertifikat kamu hanya dalam hitungan detik.
            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-8">
                <a href="{{ route('pencarian.sertifikat') }}" class="inline-flex items-center gap-2 border-[3px] border-black bg-white px-8 py-3 text-sm font-black text-black uppercase tracking-wide brutal-shadow transition-all hover:bg-amber-100">
                    Cek Sertifikat →
                </a>
                {{-- <a href="{{ route('pencarian.eligible') }}" class="inline-flex items-center gap-2 border-[3px] border-black bg-black px-8 py-3 text-sm font-black text-white uppercase tracking-wide brutal-shadow-amber transition-all hover:bg-gray-900">
                    Cek Eligible PTN →
                </a> --}}
            </div>
        </div>
    </section>
</main>

@include('profile.partials.footer')

</body>
</html>
