<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim Pengembang - {{ config('app.name', 'Certisat') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .brutal-shadow { box-shadow: 5px 5px 0px 0px #000; }
        .brutal-shadow:hover { box-shadow: 3px 3px 0px 0px #000; transform: translate(2px, 2px); }
    </style>
</head>
<body class="bg-amber-50 text-black antialiased">

@include('profile.partials.navbar-user')

<main class="pt-24 pb-16 px-4 sm:px-6 lg:px-10 max-w-6xl mx-auto space-y-10">
    <section class="space-y-4">
        <div>
            <div class="inline-block border-[3px] border-black bg-orange-500 px-4 py-1 text-xs font-black uppercase tracking-widest text-white mb-3" style="box-shadow: 3px 3px 0px 0px #000;">
                Tentang Tim
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-black uppercase tracking-tight">Tim RPL SMK Informatika Pesat</h1>
            <p class="mt-2 text-sm sm:text-base text-gray-600 max-w-3xl">
                Kenali tim di balik pengembangan sistem e-sertifikat SMK Informatika Pesat. Setiap anggota
                berkontribusi dalam merancang, membangun, dan memastikan platform ini berjalan dengan baik
                untuk memenuhi kebutuhan informasi siswa.
            </p>
        </div>
    </section>

    <section class="space-y-6">
        <h2 class="text-lg font-black text-black uppercase tracking-tight">Struktur tim</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Project Manager -->
            <article class="border-[3px] border-black bg-white p-5 brutal-shadow flex flex-col items-center text-center gap-3 transition-all">
                <img src="{{ asset('images/tim-developer/raenal.jpg') }}" alt="Raenal Apriansyah" class="w-20 h-20 object-cover  object-top border-[3px] border-black">
                <div class="space-y-1">
                    <p class="text-sm font-black text-black uppercase">Raenal Apriansyah, S.Kom., Gr</p>
                    <p class="text-xs text-gray-600 font-bold uppercase tracking-wide">Project Manager</p>
                </div>
                <p class="text-xs text-gray-500">
                    Mengkoordinasikan jalannya proyek, membagi tugas, dan memastikan fitur sesuai kebutuhan sekolah.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-black text-lg">
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Instagram Project Manager">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="LinkedIn Project Manager">
                        <i class="fab fa-linkedin text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Portofolio Project Manager">
                        <i class="fas fa-globe text-sm"></i>
                    </a>
                </div>
            </article>

            <!-- Lead Developer -->
            <article class="border-[3px] border-black bg-white p-5 brutal-shadow flex flex-col items-center text-center gap-3 transition-all">
                <img src="{{ asset('images/tim-developer/rafli.jpg') }}" alt="Rafli Maulana" class="w-20 h-20 object-cover border-[3px] border-black">

                <div class="space-y-1">
                    <p class="text-sm font-black text-black uppercase">Rafli Maulana</p>
                    <p class="text-xs text-gray-600 font-bold uppercase tracking-wide">Lead Developer</p>
                </div>
                <p class="text-xs text-gray-500">
                    Mengatur arsitektur aplikasi, review kode, dan membantu anggota tim lain saat mengerjakan fitur.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-black text-lg">
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Instagram Lead Developer">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="GitHub Lead Developer">
                        <i class="fab fa-github text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Portofolio Lead Developer">
                        <i class="fas fa-globe text-sm"></i>
                    </a>
                </div>
            </article>

            <!-- Developer 1 -->
            <article class="border-[3px] border-black bg-white p-5 brutal-shadow flex flex-col items-center text-center gap-3 transition-all">
                <img src="{{ asset('images/tim-developer/nashat.jpg') }}" alt="Nashat Akram" class="w-20 h-20 object-cover border-[3px] border-black">
                <div class="space-y-1">
                    <p class="text-sm font-black text-black uppercase">Nashat Akram</p>
                    <p class="text-xs text-gray-600 font-bold uppercase tracking-wide">Developer</p>
                </div>
                <p class="text-xs text-gray-500">
                    Fokus pada pengembangan fitur backend seperti manajemen data siswa dan sertifikat.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-black text-lg">
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Instagram Developer 1">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="GitHub Developer 1">
                        <i class="fab fa-github text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Portofolio Developer 1">
                        <i class="fas fa-globe text-sm"></i>
                    </a>
                </div>
            </article>

            <!-- Developer 2 -->
            <article class="border-[3px] border-black bg-white p-5 brutal-shadow flex flex-col items-center text-center gap-3 transition-all">
                <img src="{{ asset('images/tim-developer/nugraha.jpg') }}" alt="Nugraha Algeio Firizki S" class="w-20 h-20 object-cover border-[3px] border-black">
                <div class="space-y-1">
                    <p class="text-sm font-black text-black uppercase">Nugraha Algeio Firizki S</p>
                    <p class="text-xs text-gray-600 font-bold uppercase tracking-wide">Developer</p>
                </div>
                <p class="text-xs text-gray-500">
                    Membantu mengerjakan tampilan antarmuka, komponen form, dan halaman dashboard admin.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-black text-lg">
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Instagram Developer 2">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="GitHub Developer 2">
                        <i class="fab fa-github text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Portofolio Developer 2">
                        <i class="fas fa-globe text-sm"></i>
                    </a>
                </div>
            </article>

            <!-- Developer 3 -->
            <article class="border-[3px] border-black bg-white p-5 brutal-shadow flex flex-col items-center text-center gap-3 transition-all">
                <img src="{{ asset('images/tim-developer/naufal.jpg') }}" alt="Naufal Bagaskara Budihutama" class="w-20 h-20 object-cover border-[3px] border-black">
                <div class="space-y-1">
                    <p class="text-sm font-black text-black uppercase">Naufal Bagaskara Budihutama</p>
                    <p class="text-xs text-gray-600 font-bold uppercase tracking-wide">Developer</p>
                </div>
                <p class="text-xs text-gray-500">
                    Mengembangkan halaman publik seperti landing page, halaman pencarian sertifikat, dan dokumentasi singkat.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2 text-black text-lg">
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Instagram Developer 3">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="GitHub Developer 3">
                        <i class="fab fa-github text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border-[2px] border-black hover:bg-amber-400 transition-colors" aria-label="Portofolio Developer 3">
                        <i class="fas fa-globe text-sm"></i>
                    </a>
                </div>
            </article>
        </div>
    </section>
</main>

@include('profile.partials.footer')

</body>
</html>

