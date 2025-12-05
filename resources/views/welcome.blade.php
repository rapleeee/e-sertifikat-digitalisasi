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
                    <span>Portal sertifikat siswa SMK</span>
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl font-semibold text-slate-900 leading-tight">
                    Cek sertifikat siswa kapan saja.
                </h1>
                <p class="text-sm sm:text-base text-slate-600 max-w-xl">
                    Masukkan <span class="font-semibold text-slate-800">NIS</span> atau nama siswa
                    untuk melihat sertifikat yang sudah diterbitkan oleh sekolah secara resmi.
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a
                        href="{{ route('pencarian.sertifikat') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-orange-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-slate-50"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0014 0z" />
                        </svg>
                        Cari Sertifikat
                    </a>
                </div>

                <div class="pt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs sm:text-sm">
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <p class="font-semibold text-slate-800">Cari dengan NIS / nama</p>
                        <p class="mt-1 text-slate-500">
                            Cukup ketik NIS atau nama siswa untuk menampilkan daftar sertifikatnya.
                        </p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <p class="font-semibold text-slate-800">Semua sertifikat di satu tempat</p>
                        <p class="mt-1 text-slate-500">
                            Satu siswa bisa punya banyak sertifikat, tersimpan rapi dalam satu riwayat.
                        </p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <p class="font-semibold text-slate-800">Verifikasi resmi sekolah</p>
                        <p class="mt-1 text-slate-500">
                            Data dikelola oleh admin sekolah sehingga mudah dipakai untuk administrasi.
                        </p>
                    </div>
                </div>
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

    <!-- Info singkat -->
    <!-- <section class="mt-14 border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs sm:text-sm">
                <div class="space-y-1.5">
                    <p class="font-semibold text-slate-800">Untuk siswa & orang tua</p>
                    <p class="text-slate-500">
                        Buka menu <span class="font-semibold text-slate-700">Cari Sertifikat</span>,
                        lalu masukkan NIS atau nama siswa untuk melihat sertifikat yang sudah diterbitkan.
                    </p>
                </div>
                <div class="space-y-1.5">
                    <p class="font-semibold text-slate-800">Untuk admin / guru</p>
                    <p class="text-slate-500">
                        Login admin hanya untuk petugas sekolah guna menambah siswa,
                        mengunggah sertifikat, dan memperbarui data.
                    </p>
                </div>
                <div class="space-y-1.5">
                    <p class="font-semibold text-slate-800">Alur singkat</p>
                    <p class="text-slate-500">
                        Admin menginput data siswa dan sertifikat berbasis NIS,
                        kemudian siswa/orang tua dapat mengecek sertifikat kapan saja melalui halaman ini.
                    </p>
                </div>
            </div>
        </div>
    </section> -->
</main>

@include('profile.partials.footer')

</body>
</html>
