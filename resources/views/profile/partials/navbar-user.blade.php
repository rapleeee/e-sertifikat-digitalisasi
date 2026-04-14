<nav
    x-data="{ scrolled: false, open: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
    :class="scrolled ? 'shadow-[0_4px_0_0_#000]' : ''"
    class="fixed top-0 left-0 w-full z-50 bg-amber-400 border-b-[3px] border-black transition-all duration-200"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-18">
            
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/smk.png') }}" alt="Logo SMK" class="relative w-auto h-10 lg:h-12">
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-1 lg:gap-2">
                <a href="/" class="px-4 py-1.5 text-sm lg:text-base font-bold text-black uppercase tracking-wide border-2 border-transparent hover:border-black hover:bg-white transition-all">
                    Home
                </a>
                <a href="{{ route('pencarian.sertifikat') }}" class="px-4 py-1.5 text-sm lg:text-base font-bold text-black uppercase tracking-wide border-2 border-transparent hover:border-black hover:bg-white transition-all">
                    Sertifikat
                </a>
                {{-- <a href="{{ route('pencarian.eligible') }}" class="px-4 py-1.5 text-sm lg:text-base font-bold text-black uppercase tracking-wide border-2 border-transparent hover:border-black hover:bg-white transition-all">
                    Eligible PTN
                </a> --}}
                <a href="{{ route('laporan.public.form') }}" class="px-4 py-1.5 text-sm lg:text-base font-bold text-black uppercase tracking-wide border-2 border-transparent hover:border-black hover:bg-white transition-all">
                    Laporan
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button
                class="md:hidden inline-flex items-center justify-center w-10 h-10 border-2 border-black bg-white text-black hover:bg-black hover:text-white focus:outline-none transition-colors"
                @click="open = !open"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-amber-300 border-t-2 border-black"
    >
        <div class="px-4 py-4 space-y-2">
            <a href="/" class="block px-4 py-3 font-bold text-black uppercase tracking-wide border-2 border-black bg-white hover:bg-black hover:text-white transition-colors">
                Beranda
            </a>
            <a href="{{ route('pencarian.sertifikat') }}" class="block px-4 py-3 font-bold text-black uppercase tracking-wide border-2 border-black bg-white hover:bg-black hover:text-white transition-colors">
                Sertifikat
            </a>
            {{-- <a href="{{ route('pencarian.eligible') }}" class="block px-4 py-3 font-bold text-black uppercase tracking-wide border-2 border-black bg-white hover:bg-black hover:text-white transition-colors">
                Eligible PTN
            </a> --}}
            <a href="{{ route('laporan.public.form') }}" class="block px-4 py-3 font-bold text-black uppercase tracking-wide border-2 border-black bg-white hover:bg-black hover:text-white transition-colors">
                Laporan
            </a>
        </div>
    </div>
</nav>
