<nav
    x-data="{ scrolled: false, open: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
    :class="scrolled ? 'bg-white border-b border-slate-200 shadow-sm' : 'bg-white border-b border-slate-200'"
    class="fixed top-0 left-0 w-full z-50 transition-colors duration-300"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-18">
            
            <!-- Logo -->
            <div class="flex items-center gap-3 logo-container">
                <img src="{{ asset('images/smk.png') }}" alt="Logo SMK" class="relative w-auto h-10 lg:h-12">
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-1 lg:gap-2">
                <a href="/" class="px-4 py-2 text-sm lg:text-base font-medium text-slate-700 hover:text-orange-600 transition-colors">
                    <span class="relative z-10">Home</span>
                </a>
                <a href="{{ route('pencarian.sertifikat') }}" class="px-4 py-2 text-sm lg:text-base font-medium text-slate-700 hover:text-orange-600 transition-colors">
                    <span class="relative z-10">Cek Sertifikat</span>
                </a>
                <a href="{{ route('pencarian.sertifikat') }}" class="px-4 py-2 text-sm lg:text-base font-medium text-slate-700 hover:text-orange-600 transition-colors">
                    <span class="relative z-10">Cek Kelulusan</span>
                </a>
                <a href="{{ route('pencarian.sertifikat') }}" class="px-4 py-2 text-sm lg:text-base font-medium text-slate-700 hover:text-orange-600 transition-colors">
                    <span class="relative z-10">Cek Eligible PTN</span>
                </a>
                <a href="{{ route('laporan.public.form') }}" class="px-4 py-2 text-sm lg:text-base font-medium text-slate-700 hover:text-orange-600 transition-colors">
                    <span class="relative z-10">Laporan</span>
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button
                class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50"
                @click="open = !open"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Enhanced Mobile Menu -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-4"
        class="md:hidden bg-white border-t border-slate-200 shadow-sm"
    >
        <div class="px-4 py-6 space-y-4">
            <a href="/" class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:text-orange-600 hover:bg-slate-50 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">Beranda</span>
            </a>
            
            <a href="{{ route('pencarian.sertifikat') }}" class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:text-orange-600 hover:bg-slate-50 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-medium">Sertifikat</span>
            </a>
            
            <a href="{{ route('laporan.public.form') }}" class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:text-orange-600 hover:bg-slate-50 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 8h7a2 2 0 012 2v4a2 2 0 01-2 2H5l-2 2V8a2 2 0 012-2z" />
                </svg>
                <span class="font-medium">Laporan</span>
            </a>
        </div>
    </div>
</nav>
