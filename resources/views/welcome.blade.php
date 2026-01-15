<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Certisat') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

@include('profile.partials.navbar-user')

<main class="pt-24 pb-16 min-h-screen">
    <!-- Hero -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="space-y-5">
                <div class="inline-flex items-center gap-3 rounded-full bg-white border border-slate-200 px-3 py-1 text-xs font-medium text-slate-500 shadow-sm">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">
                        SMK
                    </span>
                    <span>Portal Informasi Siswa Pesat</span>
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl font-semibold text-slate-900 leading-tight">
                    Cek Informasi Siswa dengan Mudah
                </h1>
                <p class="text-sm sm:text-base text-slate-600 max-w-xl">
                    Masukkan <span class="font-semibold text-slate-800">NIS</span> atau <span class="font-semibold text-slate-800">Nama Siswa</span>
                    untuk melihat informasi yang kamu cari dan diterbitkan langsung oleh sekolah secara resmi.
                </p>

                <div class="flex flex-wrap gap-3 pt-6">
                        <a href="{{ route('pencarian.sertifikat') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-orange-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0014 0z" />
                            </svg>
                            Cek Sertifikat
                        </a>
                        <!-- <a href="{{ route('pencarian.sertifikat') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-orange-600 shadow-sm border border-orange-200 hover:bg-orange-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0014 0z" />
                            </svg>
                            Cek Kelulusan
                        </a> -->
                        <a href="{{ route('pencarian.eligible') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm border border-slate-200 hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Cek Eligible PTN dan Hasil TKA
                        </a>
                </div>
                

                <!-- <div class="pt-6 space-y-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Mengapa memilih kami?</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="group rounded-lg border border-slate-200 bg-white px-4 py-4 shadow-sm hover:shadow-md hover:border-orange-300 transition-all duration-200">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm">Pencarian Instan</p>
                                    <p class="mt-1 text-slate-600 text-xs leading-relaxed">
                                        Temukan sertifikat hanya dengan NIS atau nama siswa dalam hitungan detik.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="group rounded-lg border border-slate-200 bg-white px-4 py-4 shadow-sm hover:shadow-md hover:border-orange-300 transition-all duration-200">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm">Terpercaya & Resmi</p>
                                    <p class="mt-1 text-slate-600 text-xs leading-relaxed">
                                        Semua data dikelola langsung oleh sekolah dengan jaminan keakuratan.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="group rounded-lg border border-slate-200 bg-white px-4 py-4 shadow-sm hover:shadow-md hover:border-orange-300 transition-all duration-200">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm">Mudah Diakses</p>
                                    <p class="mt-1 text-slate-600 text-xs leading-relaxed">
                                        Tersedia kapan saja, di mana saja, untuk siswa, orang tua, dan sekolah.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            <div class="flex justify-center md:justify-end">
                <div class="w-full max-w-sm">
                        <img
                            src="{{ asset('images/Desian-Web.jpg') }}"
                            alt="Ilustrasi sertifikat"
                            class="w-full h-auto rounded-xl object-cover"
                        >
                </div>
            </div>
        </div>
    </section>

    <!-- Info Cards -->
    <section class="mt-32">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10">
            <div class="text-center space-y-3 mb-12">
                <h2 class="text-2xl sm:text-3xl font-semibold text-slate-900">Bagaimana Cara Menggunakan?</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Panduan lengkap untuk memaksimalkan penggunaan platform kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card 1: Siswa & Orang Tua -->
                <div class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900 text-base">Cari Sertifikat</h3>
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                                Masukkan NIS atau nama siswa pada menu <span class="font-medium text-slate-700">Cari Sertifikat</span> untuk menemukan sertifikat yang sudah diterbitkan oleh sekolah secara instan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Cek Kelulusan -->
                <div class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900 text-base">Cek Kelulusan</h3>
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                                Gunakan fitur <span class="font-medium text-slate-700">Cek Kelulusan</span> untuk melihat status kelulusan siswa beserta informasi terkait prestasi dan pencapaian akademik.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Cek Eligible PTN -->
                <div class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900 text-base">Cek Eligible PTN</h3>
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                                Cek apakah kamu memenuhi syarat eligibilitas untuk melanjutkan ke Perguruan Tinggi Negeri melalui fitur <span class="font-medium text-slate-700">Cek Eligible PTN</span>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Terpercaya & Aman -->
                <div class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900 text-base">Terpercaya & Aman</h3>
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                                Semua data dikelola langsung oleh sekolah dengan jaminan keakuratan dan keamanan. Akses tersedia kapan saja, di mana saja untuk kenyamanan Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@include('profile.partials.footer')

</body>
</html>
