<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim Pengembang - {{ config('app.name', 'Certisat') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

@include('profile.partials.navbar-user')

<main class="pt-24 pb-16 px-4 sm:px-6 lg:px-10 max-w-6xl mx-auto space-y-10">
    <section class="space-y-4">
        <div>
            <p class="text-xs font-semibold text-orange-600 uppercase tracking-wide">Tentang tim</p>
            <h1 class="mt-1 text-2xl sm:text-3xl font-semibold text-slate-900">Tim RPL SMK Informatika Pesat</h1>
            <p class="mt-2 text-sm sm:text-base text-slate-600 max-w-3xl">
                Halaman ini berisi informasi singkat mengenai struktur tim yang mengerjakan proyek
                pengembangan sistem e-sertifikat ini. Data di bawah dapat kamu sesuaikan dengan nama asli
                anggota tim, foto, dan tautan profil masing-masing.
            </p>
        </div>
    </section>

    <section class="space-y-6">
        <h2 class="text-lg font-semibold text-slate-900">Struktur tim</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Project Manager -->
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col items-center text-center gap-3">
                <div class="w-20 h-20 rounded-full bg-orange-50 border border-orange-200 flex items-center justify-center text-2xl font-semibold text-orange-600">
                    RA
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-900">Raenal Apriansyah, S.Kom., Gr</p>
                    <p class="text-xs text-slate-500">Project Manager</p>
                </div>
                <p class="text-xs text-slate-500">
                    Mengkoordinasikan jalannya proyek, membagi tugas, dan memastikan fitur sesuai kebutuhan sekolah.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-slate-400 text-lg">
                    <a href="#" class="hover:text-orange-600" aria-label="Instagram Project Manager">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600" aria-label="LinkedIn Project Manager">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600 text-base" aria-label="Portofolio Project Manager">
                        <i class="fas fa-globe"></i>
                    </a>
                </div>
            </article>

            <!-- Lead Developer -->
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col items-center text-center gap-3">
                <img src="{{ asset('images/tim-developer/rafli.jpg') }}" alt="Rafli Maulana" class="w-20 h-20 rounded-full object-cover border border-orange-200">

                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-900">Rafli Maulana</p>
                    <p class="text-xs text-slate-500">Lead Developer</p>
                </div>
                <p class="text-xs text-slate-500">
                    Mengatur arsitektur aplikasi, review kode, dan membantu anggota tim lain saat mengerjakan fitur.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-slate-400 text-lg">
                    <a href="#" class="hover:text-orange-600" aria-label="Instagram Lead Developer">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600" aria-label="GitHub Lead Developer">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600 text-base" aria-label="Portofolio Lead Developer">
                        <i class="fas fa-globe"></i>
                    </a>
                </div>
            </article>

            <!-- Developer 1 -->
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col items-center text-center gap-3">
                <div class="w-20 h-20 rounded-full bg-orange-50 border border-orange-200 flex items-center justify-center text-2xl font-semibold text-orange-600">
                    NA
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-900">Nashat Akram</p>
                    <p class="text-xs text-slate-500">Developer</p>
                </div>
                <p class="text-xs text-slate-500">
                    Fokus pada pengembangan fitur backend seperti manajemen data siswa dan sertifikat.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-slate-400 text-lg">
                    <a href="#" class="hover:text-orange-600" aria-label="Instagram Developer 1">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600" aria-label="GitHub Developer 1">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600 text-base" aria-label="Portofolio Developer 1">
                        <i class="fas fa-globe"></i>
                    </a>
                </div>
            </article>

            <!-- Developer 2 -->
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col items-center text-center gap-3">
                <div class="w-20 h-20 rounded-full bg-orange-50 border border-orange-200 flex items-center justify-center text-xl font-semibold text-orange-600">
                    NAF
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-900">Nugraha Algeio Firizki S</p>
                    <p class="text-xs text-slate-500">Developer</p>
                </div>
                <p class="text-xs text-slate-500">
                    Membantu mengerjakan tampilan antarmuka, komponen form, dan halaman dashboard admin.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-slate-400 text-lg">
                    <a href="#" class="hover:text-orange-600" aria-label="Instagram Developer 2">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600" aria-label="GitHub Developer 2">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600 text-base" aria-label="Portofolio Developer 2">
                        <i class="fas fa-globe"></i>
                    </a>
                </div>
            </article>

            <!-- Developer 3 -->
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col items-center text-center gap-3">
                <div class="w-20 h-20 rounded-full bg-orange-50 border border-orange-200 flex items-center justify-center text-2xl font-semibold text-orange-600">
                    NB
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-900">Naufal Bagaskara Budihutama</p>
                    <p class="text-xs text-slate-500">Developer</p>
                </div>
                <p class="text-xs text-slate-500">
                    Mengembangkan halaman publik seperti landing page, halaman pencarian sertifikat, dan dokumentasi singkat.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-slate-400 text-lg">
                    <a href="#" class="hover:text-orange-600" aria-label="Instagram Developer 3">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600" aria-label="GitHub Developer 3">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="hover:text-orange-600 text-base" aria-label="Portofolio Developer 3">
                        <i class="fas fa-globe"></i>
                    </a>
                </div>
            </article>
        </div>
    </section>
</main>

@include('profile.partials.footer')

</body>
</html>

