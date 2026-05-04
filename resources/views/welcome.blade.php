@extends('layouts.glass')

@section('title', config('app.name', 'Certisat') . ' - Cek Eligible PTN')

@section('main-class', 'px-4 pb-24 md:pb-0 pt-6 md:pt-24')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 md:space-y-12">

    <!-- Hero -->
    <section class="py-8 sm:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
            <div class="space-y-5 text-center md:text-left">
                <div class="inline-flex items-center gap-2 bg-yellow-300 nb-border-2 nb-shadow-sm rounded-full px-4 py-1.5 text-xs font-bold text-[#1a1a2e] tracking-wide uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    SMK Informatika Pesat
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-[#1a1a2e] leading-[1.1] tracking-tight">
                    Cek
                    <span class="bg-yellow-300 px-2 nb-border-2 inline-block -rotate-1">Eligible PTN</span>
                    <br>& Hasil TKA
                </h1>
                <p class="text-base sm:text-lg text-gray-600 max-w-xl leading-relaxed">
                    Masukkan <span class="font-bold text-[#1a1a2e] bg-blue-200 px-1">NIS</span>
                    untuk melihat status kelayakan masuk PTN dan hasil TKA yang telah ditetapkan oleh sekolah.
                </p>

                <div class="flex flex-wrap gap-3 pt-2 justify-center md:justify-start">
                    <a href="{{ route('pencarian.eligible') }}" class="nb-btn inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-blue-500 text-white text-sm font-bold tracking-wide">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Cek Eligible PTN & TKA
                    </a>
                    <a href="{{ route('kelulusan.index') }}" class="nb-btn inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-yellow-300 text-[#1a1a2e] text-sm font-bold tracking-wide">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 3m6-4a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Cek Kelulusan
                    </a>
                </div>
            </div>

            <div class="flex justify-center md:justify-end">
                <div class="w-full max-w-sm">
                    <div class="nb-card rounded-2xl p-3 rotate-2">
                        <img
                            src="{{ asset('images/Desian-Web.jpg') }}"
                            alt="Ilustrasi"
                            class="w-full h-auto object-cover rounded-xl"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section>
        <div class="nb-card rounded-2xl p-1">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-1">
                <div class="py-5 text-center rounded-xl">
                    <p class="text-2xl sm:text-3xl font-bold text-[#1a1a2e]">100%</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mt-1">Resmi</p>
                </div>
                <div class="py-5 text-center rounded-xl bg-yellow-100">
                    <p class="text-2xl sm:text-3xl font-bold text-[#1a1a2e]">24/7</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mt-1">Akses</p>
                </div>
                <div class="py-5 text-center rounded-xl">
                    <p class="text-2xl sm:text-3xl font-bold text-[#1a1a2e]">Instan</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mt-1">Pencarian</p>
                </div>
                <div class="py-5 text-center rounded-xl bg-blue-100">
                    <p class="text-2xl sm:text-3xl font-bold text-[#1a1a2e]">Aman</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mt-1">& Terpercaya</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Cards -->
    <section class="pb-6">
        <div class="text-center space-y-3 mb-10">
            <div class="inline-flex items-center gap-2 bg-pink-300 nb-border-2 nb-shadow-sm rounded-full px-4 py-1.5 text-xs font-bold text-[#1a1a2e] tracking-wide uppercase">
                Panduan
            </div>
            <h2 class="text-3xl sm:text-4xl font-bold text-[#1a1a2e] tracking-tight">
                Cara Menggunakan
            </h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-sm sm:text-base">Ikuti langkah-langkah berikut untuk memaksimalkan platform kami</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="nb-card rounded-2xl p-6 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#1a1a2e] transition-all">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 nb-border-2 text-white font-bold text-sm">01</div>
                    <div class="flex-1">
                        <h3 class="font-bold text-[#1a1a2e] text-base">Cek Eligible PTN</h3>
                        <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">Masukkan NIS pada menu Cek Eligible untuk melihat apakah kamu memenuhi syarat masuk Perguruan Tinggi Negeri.</p>
                    </div>
                </div>
            </div>
            <div class="nb-card rounded-2xl p-6 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#1a1a2e] transition-all">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-300 nb-border-2 text-[#1a1a2e] font-bold text-sm">02</div>
                    <div class="flex-1">
                        <h3 class="font-bold text-[#1a1a2e] text-base">Cek Kelulusan</h3>
                        <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">Buka menu Cek Kelulusan saat countdown selesai, lalu cari data menggunakan NIS atau nama lengkap.</p>
                    </div>
                </div>
            </div>
            <div class="nb-card rounded-2xl p-6 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#1a1a2e] transition-all">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex h-12 w-12 items-center justify-center rounded-xl bg-pink-300 nb-border-2 text-[#1a1a2e] font-bold text-sm">03</div>
                    <div class="flex-1">
                        <h3 class="font-bold text-[#1a1a2e] text-base">Terpercaya & Aman</h3>
                        <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">Semua data dikelola langsung oleh sekolah. Akses tersedia kapan saja, di mana saja.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="pb-8">
        <div class="nb-card bg-yellow-300 rounded-2xl p-8 sm:p-12 text-center" style="background: #fde047;">
            <h2 class="text-3xl sm:text-4xl font-bold text-[#1a1a2e] tracking-tight">
                Mulai Cek Sekarang!
            </h2>
            <p class="text-[#1a1a2e]/60 text-sm sm:text-base mt-3 max-w-lg mx-auto">
                Cek status eligible PTN dan hasil TKA kamu hanya dalam hitungan detik.
            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <a href="{{ route('pencarian.eligible') }}" class="nb-btn inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-white text-[#1a1a2e] text-sm font-bold">
                    Cek Eligible PTN & TKA →
                </a>
                <a href="{{ route('kelulusan.index') }}" class="nb-btn inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-blue-500 text-white text-sm font-bold">
                    Cek Kelulusan →
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
