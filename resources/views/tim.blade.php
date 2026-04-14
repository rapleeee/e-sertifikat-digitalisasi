@extends('layouts.glass')

@section('title', 'Tim Pengembang - ' . config('app.name', 'Certisat'))

@section('main-class', 'px-4 pb-24 md:pb-8 pt-8 md:pt-24')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header -->
    <section class="text-center space-y-3">
        <div class="inline-flex items-center gap-2 bg-pink-300 nb-border-2 nb-shadow-sm rounded-full px-4 py-1.5 text-xs font-bold text-[#1a1a2e] tracking-wide uppercase">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            Tentang Tim
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#1a1a2e] tracking-tight">Tim RPL SMK Informatika Pesat</h1>
        <p class="text-sm sm:text-base text-gray-500 max-w-2xl mx-auto leading-relaxed">
            Kenali tim di balik pengembangan sistem e-sertifikat SMK Informatika Pesat. Setiap anggota
            berkontribusi dalam merancang, membangun, dan memastikan platform ini berjalan dengan baik.
        </p>
    </section>

    <!-- Team Grid -->
    <section class="space-y-4">
        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest text-center">Struktur Tim</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $colors = ['bg-yellow-300', 'bg-blue-300', 'bg-pink-300', 'bg-green-300', 'bg-orange-300'];
            @endphp

            <!-- Project Manager -->
            <article class="nb-card rounded-2xl p-6 flex flex-col items-center text-center gap-3 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#1a1a2e] transition-all">
                <div class="bg-yellow-300 nb-border-2 rounded-xl p-0.5">
                    <img src="{{ asset('images/tim-developer/raenal.jpg') }}" alt="Raenal Apriansyah" class="w-20 h-20 object-cover object-top rounded-lg">
                </div>
                <div class="space-y-0.5">
                    <p class="text-sm font-bold text-[#1a1a2e]">Raenal Apriansyah, S.Kom., Gr</p>
                    <span class="inline-block text-[10px] font-bold uppercase tracking-wide bg-yellow-300 nb-border-2 rounded-full px-2.5 py-0.5">Project Manager</span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Mengkoordinasikan jalannya proyek, membagi tugas, dan memastikan fitur sesuai kebutuhan sekolah.
                </p>
                <div class="flex items-center justify-center gap-2 pt-1">
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Instagram"><i class="fab fa-instagram text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="LinkedIn"><i class="fab fa-linkedin text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Website"><i class="fas fa-globe text-[#1a1a2e] text-xs"></i></a>
                </div>
            </article>

            <!-- Lead Developer -->
            <article class="nb-card rounded-2xl p-6 flex flex-col items-center text-center gap-3 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#1a1a2e] transition-all">
                <div class="bg-blue-300 nb-border-2 rounded-xl p-0.5">
                    <img src="{{ asset('images/tim-developer/rafli.jpg') }}" alt="Rafli Maulana" class="w-20 h-20 object-cover rounded-lg">
                </div>
                <div class="space-y-0.5">
                    <p class="text-sm font-bold text-[#1a1a2e]">Rafli Maulana</p>
                    <span class="inline-block text-[10px] font-bold uppercase tracking-wide bg-blue-300 nb-border-2 rounded-full px-2.5 py-0.5">Lead Developer</span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Mengatur arsitektur aplikasi, review kode, dan membantu anggota tim lain saat mengerjakan fitur.
                </p>
                <div class="flex items-center justify-center gap-2 pt-1">
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Instagram"><i class="fab fa-instagram text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="GitHub"><i class="fab fa-github text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Website"><i class="fas fa-globe text-[#1a1a2e] text-xs"></i></a>
                </div>
            </article>

            <!-- Developer 1 -->
            <article class="nb-card rounded-2xl p-6 flex flex-col items-center text-center gap-3 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#1a1a2e] transition-all">
                <div class="bg-pink-300 nb-border-2 rounded-xl p-0.5">
                    <img src="{{ asset('images/tim-developer/nashat.jpg') }}" alt="Nashat Akram" class="w-20 h-20 object-cover rounded-lg">
                </div>
                <div class="space-y-0.5">
                    <p class="text-sm font-bold text-[#1a1a2e]">Nashat Akram</p>
                    <span class="inline-block text-[10px] font-bold uppercase tracking-wide bg-pink-300 nb-border-2 rounded-full px-2.5 py-0.5">Developer</span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Fokus pada pengembangan fitur backend seperti manajemen data siswa dan sertifikat.
                </p>
                <div class="flex items-center justify-center gap-2 pt-1">
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Instagram"><i class="fab fa-instagram text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="GitHub"><i class="fab fa-github text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Website"><i class="fas fa-globe text-[#1a1a2e] text-xs"></i></a>
                </div>
            </article>

            <!-- Developer 2 -->
            <article class="nb-card rounded-2xl p-6 flex flex-col items-center text-center gap-3 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#1a1a2e] transition-all">
                <div class="bg-green-300 nb-border-2 rounded-xl p-0.5">
                    <img src="{{ asset('images/tim-developer/nugraha.jpg') }}" alt="Nugraha Algeio Firizki S" class="w-20 h-20 object-cover rounded-lg">
                </div>
                <div class="space-y-0.5">
                    <p class="text-sm font-bold text-[#1a1a2e]">Nugraha Algeio Firizki S</p>
                    <span class="inline-block text-[10px] font-bold uppercase tracking-wide bg-green-300 nb-border-2 rounded-full px-2.5 py-0.5">Developer</span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Membantu mengerjakan tampilan antarmuka, komponen form, dan halaman dashboard admin.
                </p>
                <div class="flex items-center justify-center gap-2 pt-1">
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Instagram"><i class="fab fa-instagram text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="GitHub"><i class="fab fa-github text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Website"><i class="fas fa-globe text-[#1a1a2e] text-xs"></i></a>
                </div>
            </article>

            <!-- Developer 3 -->
            <article class="nb-card rounded-2xl p-6 flex flex-col items-center text-center gap-3 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#1a1a2e] transition-all">
                <div class="bg-orange-300 nb-border-2 rounded-xl p-0.5">
                    <img src="{{ asset('images/tim-developer/naufal.jpg') }}" alt="Naufal Bagaskara Budihutama" class="w-20 h-20 object-cover rounded-lg">
                </div>
                <div class="space-y-0.5">
                    <p class="text-sm font-bold text-[#1a1a2e]">Naufal Bagaskara Budihutama</p>
                    <span class="inline-block text-[10px] font-bold uppercase tracking-wide bg-orange-300 nb-border-2 rounded-full px-2.5 py-0.5">Developer</span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Mengembangkan halaman publik seperti landing page, halaman pencarian, dan dokumentasi.
                </p>
                <div class="flex items-center justify-center gap-2 pt-1">
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Instagram"><i class="fab fa-instagram text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="GitHub"><i class="fab fa-github text-[#1a1a2e] text-xs"></i></a>
                    <a href="#" class="nb-btn w-8 h-8 flex items-center justify-center rounded-lg bg-white" aria-label="Website"><i class="fas fa-globe text-[#1a1a2e] text-xs"></i></a>
                </div>
            </article>
        </div>
    </section>
</div>
@endsection
