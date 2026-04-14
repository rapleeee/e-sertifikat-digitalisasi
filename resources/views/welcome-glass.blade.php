@extends('layouts.glass')

@section('title', config('app.name', 'Certisat') . ' - Cek Eligible PTN')

@section('main-class', 'px-4 pb-24 md:pb-0 pt-6 md:pt-24')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 md:space-y-12">

    <!-- Hero -->
    <section class="py-8 sm:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
            <div class="space-y-5 text-center md:text-left">
                <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-1.5 text-xs font-semibold text-white/90 tracking-wide uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    SMK Informatika Pesat
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white leading-[1.1] tracking-tight">
                    Cek
                    <span class="bg-gradient-to-r from-amber-300 to-orange-400 bg-clip-text text-transparent">Eligible PTN</span>
                    <br>& Hasil TKA
                </h1>
                <p class="text-base sm:text-lg text-white/70 max-w-xl leading-relaxed">
                    Masukkan <span class="font-bold text-white/90">NIS</span>
                    untuk melihat status kelayakan masuk PTN dan hasil TKA yang telah ditetapkan oleh sekolah.
                </p>

                <div class="flex flex-wrap gap-3 pt-2 justify-center md:justify-start">
                    <a href="{{ route('pencarian.eligible') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-bold tracking-wide hover:from-indigo-600 hover:to-purple-700 active:scale-[0.98] transition-all shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Cek Eligible PTN & TKA
                    </a>
                </div>
            </div>

            <div class="flex justify-center md:justify-end">
                <div class="w-full max-w-sm">
                    <div class="glass-strong rounded-3xl p-3 rotate-2 shadow-2xl shadow-black/10">
                        <img
                            src="{{ asset('images/Desian-Web.jpg') }}"
                            alt="Ilustrasi"
                            class="w-full h-auto object-cover rounded-2xl"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section>
        <div class="glass-strong rounded-3xl p-1">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-1">
                <div class="py-5 text-center rounded-2xl">
                    <p class="text-2xl sm:text-3xl font-extrabold bg-gradient-to-r from-amber-300 to-orange-400 bg-clip-text text-transparent">100%</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-1">Resmi</p>
                </div>
                <div class="py-5 text-center rounded-2xl">
                    <p class="text-2xl sm:text-3xl font-extrabold bg-gradient-to-r from-amber-300 to-orange-400 bg-clip-text text-transparent">24/7</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-1">Akses</p>
                </div>
                <div class="py-5 text-center rounded-2xl">
                    <p class="text-2xl sm:text-3xl font-extrabold bg-gradient-to-r from-amber-300 to-orange-400 bg-clip-text text-transparent">Instan</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-1">Pencarian</p>
                </div>
                <div class="py-5 text-center rounded-2xl">
                    <p class="text-2xl sm:text-3xl font-extrabold bg-gradient-to-r from-amber-300 to-orange-400 bg-clip-text text-transparent">Aman</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-1">& Terpercaya</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Cards -->
    <section class="pb-6">
        <div class="text-center space-y-3 mb-10">
            <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-1.5 text-xs font-semibold text-white/90 tracking-wide uppercase">
                Panduan
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                Cara Menggunakan
            </h2>
            <p class="text-white/60 max-w-2xl mx-auto text-sm sm:text-base">Ikuti langkah-langkah berikut untuk memaksimalkan platform kami</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Card 1 -->
            <div class="glass-card rounded-3xl p-6 hover:bg-white/45 transition-all group">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold text-sm shadow-lg shadow-indigo-500/20">
                        01
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 text-base">Cek Eligible PTN</h3>
                        <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">
                            Masukkan NIS pada menu Cek Eligible untuk melihat apakah kamu memenuhi syarat masuk Perguruan Tinggi Negeri.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="glass-card rounded-3xl p-6 hover:bg-white/45 transition-all group">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white font-bold text-sm shadow-lg shadow-amber-500/20">
                        02
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 text-base">Lihat Hasil TKA</h3>
                        <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">
                            Lihat hasil Tes Kemampuan Akademik yang telah ditetapkan oleh sekolah untuk mendukung proses seleksi PTN.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="glass-card rounded-3xl p-6 hover:bg-white/45 transition-all group">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white font-bold text-sm shadow-lg shadow-emerald-500/20">
                        03
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 text-base">Terpercaya & Aman</h3>
                        <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">
                            Semua data dikelola langsung oleh sekolah. Akses tersedia kapan saja, di mana saja.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="pb-8">
        <div class="glass rounded-3xl p-8 sm:p-12 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                Mulai Cek Sekarang!
            </h2>
            <p class="text-white/60 text-sm sm:text-base mt-3 max-w-lg mx-auto">
                Cek status eligible PTN dan hasil TKA kamu hanya dalam hitungan detik.
            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <a href="{{ route('pencarian.eligible') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-white/90 text-gray-800 text-sm font-bold hover:bg-white transition-all shadow-lg shadow-black/5">
                    Cek Eligible PTN & TKA →
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
