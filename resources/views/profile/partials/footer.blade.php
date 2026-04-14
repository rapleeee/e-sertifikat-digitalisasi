<!-- Tambahkan ini di dalam <head> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<footer class="bg-black text-white border-t-[4px] border-amber-400">
    <div class="max-w-screen-xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <!-- Info Sekolah -->
        <div>
            <h2 class="text-xl font-black uppercase tracking-tight mb-3 text-amber-400">SMK Informatika Pesat</h2>
            <p class="text-sm text-gray-300 leading-relaxed">
                Platform cek status eligible PTN dan hasil TKA online untuk siswa SMK Informatika Pesat yang diverifikasi langsung oleh sekolah.
            </p>
        </div>

        <!-- Navigasi -->
        <div>
            <h2 class="text-xl font-black uppercase tracking-tight mb-3 text-amber-400">Navigasi</h2>
            <ul class="space-y-2 text-sm">
                <li><a href="/" class="inline-block font-bold uppercase tracking-wide hover:text-amber-400 border-b-2 border-transparent hover:border-amber-400 transition-all">Beranda</a></li>
                <li><a href="{{ route('pencarian.eligible') }}" class="inline-block font-bold uppercase tracking-wide hover:text-amber-400 border-b-2 border-transparent hover:border-amber-400 transition-all">Eligible PTN</a></li>
                <li><a href="{{ route('laporan.public.form') }}" class="inline-block font-bold uppercase tracking-wide hover:text-amber-400 border-b-2 border-transparent hover:border-amber-400 transition-all">Laporan</a></li>
            </ul>
        </div>

        <!-- Sosial Media -->
        <div>
            <h2 class="text-xl font-black uppercase tracking-tight mb-3 text-amber-400">Terhubung</h2>
            <div class="flex space-x-3 mt-2">
                <a href="https://smkpesat.sch.id/" target="_blank" class="inline-flex items-center justify-center w-10 h-10 border-2 border-white text-white hover:bg-amber-400 hover:border-amber-400 hover:text-black transition-all text-lg">
                    <i class="fas fa-globe"></i>
                </a>
                <a href="https://www.instagram.com/smkpesat_itxpro/" target="_blank" class="inline-flex items-center justify-center w-10 h-10 border-2 border-white text-white hover:bg-amber-400 hover:border-amber-400 hover:text-black transition-all text-lg">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.facebook.com/people/SMK-Informatika-Pesat-It-XPro/100092495414821/" target="_blank" class="inline-flex items-center justify-center w-10 h-10 border-2 border-white text-white hover:bg-amber-400 hover:border-amber-400 hover:text-black transition-all text-lg">
                    <i class="fab fa-facebook"></i>
                </a>
            </div>
            <p class="text-sm text-gray-400 mt-4">
                <a
                    href="{{ route('tim.profil') }}"
                    class="inline-flex items-center gap-1 font-bold uppercase tracking-wide hover:text-amber-400 transition-colors"
                >
                    <span>Tim RPL SMK Informatika Pesat →</span>
                </a>
            </p>
        </div>
    </div>

    <div class="text-center text-gray-500 border-t-2 border-gray-800 py-4 text-sm font-bold uppercase tracking-wide">
        © {{ date('Y') }} SMK Informatika Pesat. All rights reserved.
    </div>
</footer>
