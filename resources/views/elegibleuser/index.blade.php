<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cek Eligible PTN - Certisat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@lottiefiles/dotlottie-web/+esm"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Space Grotesk', sans-serif; }

        /* Neubrutalist utilities */
        .nb-shadow { box-shadow: 4px 4px 0px 0px #1a1a2e; }
        .nb-shadow-sm { box-shadow: 3px 3px 0px 0px #1a1a2e; }
        .nb-shadow-lg { box-shadow: 6px 6px 0px 0px #1a1a2e; }
        .nb-border { border: 3px solid #1a1a2e; }
        .nb-border-2 { border: 2px solid #1a1a2e; }

        .nb-card {
            background: #ffffff;
            border: 3px solid #1a1a2e;
            box-shadow: 4px 4px 0px 0px #1a1a2e;
        }

        .nb-btn {
            border: 3px solid #1a1a2e;
            box-shadow: 4px 4px 0px 0px #1a1a2e;
            transition: all 0.1s ease;
        }
        .nb-btn:hover {
            box-shadow: 2px 2px 0px 0px #1a1a2e;
            transform: translate(2px, 2px);
        }
        .nb-btn:active {
            box-shadow: 0px 0px 0px 0px #1a1a2e;
            transform: translate(4px, 4px);
        }

        /* SweetAlert2 Neubrutalist Override */
        .swal-nb {
            background: #ffffff !important;
            border: 3px solid #1a1a2e !important;
            box-shadow: 6px 6px 0px 0px #1a1a2e !important;
            border-radius: 16px !important;
        }
        .swal-nb .swal2-title { font-family: 'Space Grotesk', sans-serif !important; font-weight: 700 !important; color: #1a1a2e !important; }
        .swal-nb .swal2-html-container { font-family: 'Space Grotesk', sans-serif !important; color: #4b5563 !important; }
        .swal-btn-nb {
            border: 2px solid #1a1a2e !important;
            box-shadow: 3px 3px 0px 0px #1a1a2e !important;
            border-radius: 12px !important;
            font-family: 'Space Grotesk', sans-serif !important;
            font-weight: 700 !important;
            padding: 10px 28px !important;
            transition: all 0.1s !important;
        }
        .swal-btn-nb:hover {
            box-shadow: 1px 1px 0px 0px #1a1a2e !important;
            transform: translate(2px, 2px) !important;
        }

        /* Bottom nav active indicator */
        .bottom-nav-item.active .nav-indicator { opacity: 1; transform: scaleX(1); }
        .bottom-nav-item .nav-indicator { opacity: 0; transform: scaleX(0); transition: all 0.3s ease; }

        /* Grid pattern bg */
        .bg-grid {
            background-image:
                linear-gradient(rgba(26, 26, 46, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26, 26, 46, 0.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
    <body class="min-h-screen flex flex-col bg-[#fefbf4] bg-grid text-[#1a1a2e] antialiased overflow-x-hidden relative">

        <!-- Decorative shapes -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-yellow-300 rounded-full border-[3px] border-[#1a1a2e] opacity-20"></div>
            <div class="absolute top-1/3 -left-16 w-32 h-32 bg-blue-400 rounded-full border-[3px] border-[#1a1a2e] opacity-15"></div>
            <div class="absolute bottom-1/4 right-10 w-24 h-24 bg-pink-300 border-[3px] border-[#1a1a2e] opacity-15 rotate-12"></div>
        </div>

        <!-- Desktop Top Navbar (hidden on mobile) -->
        <nav class="hidden md:block fixed top-0 left-0 w-full z-40">
            <div class="mx-4 mt-4">
                <div class="nb-card rounded-2xl px-6 py-3 max-w-5xl mx-auto">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/smk.png') }}" alt="Logo SMK" class="h-9">
                            <span class="font-bold text-[#1a1a2e] text-sm tracking-wide">SMK Informatika Pesat</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="/" class="px-4 py-2 text-sm font-bold text-[#1a1a2e] rounded-xl hover:bg-yellow-100 transition-all">
                                Home
                            </a>
                            <a href="{{ route('pencarian.eligible') }}" class="px-4 py-2 text-sm font-bold bg-yellow-300 nb-border-2 nb-shadow-sm rounded-xl transition-all">
                                Eligible PTN
                            </a>
                            <a href="{{ route('kelulusan.index') }}" class="px-4 py-2 text-sm font-bold text-[#1a1a2e] rounded-xl hover:bg-yellow-100 transition-all">
                                Kelulusan
                            </a>
                            <a href="{{ route('laporan.public.form') }}" class="px-4 py-2 text-sm font-bold text-[#1a1a2e] rounded-xl hover:bg-yellow-100 transition-all">
                                Laporan
                            </a>
                            {{-- <a href="{{ route('tim.profil') }}" class="px-4 py-2 text-sm font-bold text-[#1a1a2e] rounded-xl hover:bg-yellow-100 transition-all">
                                Tim
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center px-4 pb-24 md:pb-8 pt-8 md:pt-24 relative z-10">
            <div class="w-full max-w-lg mx-auto space-y-6">
                <!-- Header -->
                <div class="text-center space-y-3 px-2">
                    <div class="inline-flex items-center gap-2 bg-yellow-300 nb-border-2 nb-shadow-sm rounded-full px-4 py-1.5 text-xs font-bold text-[#1a1a2e] tracking-wide uppercase">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        SMK Informatika Pesat
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-[#1a1a2e] leading-tight tracking-tight">
                        Cek Eligible PTN<br>
                        <span class="text-gray-400 text-lg sm:text-xl font-medium">& Hasil TKA</span>
                    </h1>
                    <p class="text-sm text-gray-500 max-w-md mx-auto leading-relaxed">
                        Masukkan NIS untuk melihat status kelayakan masuk PTN dan hasil TKA.
                    </p>
                </div>

                <!-- Search Card -->
                <div class="nb-card rounded-2xl p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-3 bg-[#fefbf4] nb-border-2 rounded-xl px-4 py-3.5 focus-within:border-blue-500 transition-all">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0114 0z" />
                        </svg>
                        <input
                            id="searchInput"
                            type="text"
                            placeholder="Masukkan NIS siswa..."
                            class="w-full bg-transparent border-0 focus:ring-0 text-sm text-[#1a1a2e] placeholder-gray-400 font-medium p-0"
                            inputmode="numeric"
                        >
                    </div>
                    <button
                        id="searchButton"
                        class="nb-btn w-full py-3.5 rounded-xl bg-blue-500 text-white text-sm font-bold tracking-wide"
                    >
                        Cari Status Eligible
                    </button>

                    <!-- Loading -->
                    <div id="loadingIndicator" class="hidden">
                        <div class="flex justify-center items-center gap-3 py-2 text-sm text-[#1a1a2e] font-medium">
                            <span class="inline-block h-5 w-5 border-2 border-[#1a1a2e] border-t-transparent rounded-full animate-spin"></span>
                            <span>Sedang mencari data...</span>
                        </div>
                    </div>
                </div>

                <!-- Info hint -->
                <div class="nb-card bg-blue-100 rounded-2xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-500 nb-border-2 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-xs text-[#1a1a2e] leading-relaxed">
                            <p class="font-bold mb-1">Informasi</p>
                            Data eligible dan hasil TKA dikelola resmi oleh SMK Informatika Pesat. Pastikan NIS yang dimasukkan sudah benar.
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Hasil (hidden section for results) -->
        <section id="resultsSection" class="hidden">
            <div class="space-y-6">
                <div id="resultsHeader" class="text-center space-y-2">
                    <h2 class="text-xl font-bold text-[#1a1a2e]">Hasil pencarian</h2>
                    <p id="resultsCount" class="text-sm text-gray-500 font-medium"></p>
                </div>
                <div id="noResults" class="hidden text-center py-10 space-y-3">
                    <p class="text-5xl">✕</p>
                    <h3 id="noResultsTitle" class="text-base font-bold text-[#1a1a2e]">NIS tidak ditemukan</h3>
                    <p id="noResultsText" class="text-xs sm:text-sm text-gray-500">
                        Pastikan NIS yang kamu masukkan sudah benar, lalu coba lagi.
                    </p>
                </div>
            </div>
        </section>

        <!-- Modal Result -->
        <div id="resultModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4 opacity-0 invisible transition-all duration-200 pointer-events-none">
            <div class="relative">
                <div id="canvasContainer" class="absolute left-1/2 -translate-x-1/2 -top-20 z-10"></div>
                <div id="modalDialog" class="max-w-md w-full nb-card rounded-2xl overflow-hidden" style="margin-top: 60px;">
                    <div id="modalContent" class="p-6 sm:p-8"></div>
                </div>
            </div>
        </div>

        <!-- Mobile Bottom Navigation (visible on mobile only) -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 px-3 pb-3 pt-2">
            <div class="nb-card rounded-2xl px-2 py-2">
                <div class="flex justify-around items-center">
                    <a href="/" class="bottom-nav-item flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all">
                        <svg class="w-5 h-5 text-[#1a1a2e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z" />
                        </svg>
                        <span class="text-[10px] font-bold text-[#1a1a2e]">Home</span>
                    </a>
                    <a href="{{ route('pencarian.eligible') }}" class="bottom-nav-item active flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl bg-yellow-300 transition-all">
                        <svg class="w-5 h-5 text-[#1a1a2e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-[10px] font-bold text-[#1a1a2e]">Eligible</span>
                        <div class="nav-indicator w-1 h-1 rounded-full bg-[#1a1a2e]"></div>
                    </a>
                    <a href="{{ route('kelulusan.index') }}" class="bottom-nav-item flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all">
                        <svg class="w-5 h-5 text-[#1a1a2e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 3m6-4a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-[10px] font-bold text-[#1a1a2e]">Lulus</span>
                    </a>
                    <a href="{{ route('laporan.public.form') }}" class="bottom-nav-item flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all">
                        <svg class="w-5 h-5 text-[#1a1a2e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span class="text-[10px] font-bold text-[#1a1a2e]">Laporan</span>
                    </a>
                    {{-- <a href="{{ route('tim.profil') }}" class="bottom-nav-item flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all">
                        <svg class="w-5 h-5 text-[#1a1a2e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-[10px] font-bold text-[#1a1a2e]">Tim</span>
                    </a> --}}
                </div>
            </div>
        </nav>

        <!-- Desktop Footer (hidden on mobile) -->
        <footer class="hidden md:block relative z-10 mt-4">
            <div class="nb-card rounded-t-2xl mx-4">
                <div class="max-w-5xl mx-auto px-6 py-8">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/smk.png') }}" alt="Logo SMK" class="h-8">
                            <span class="text-sm font-bold text-[#1a1a2e]">SMK Informatika Pesat</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="https://smkpesat.sch.id/" target="_blank" class="nb-btn w-9 h-9 rounded-xl bg-yellow-100 hover:bg-yellow-300 flex items-center justify-center transition-all">
                                <i class="fas fa-globe text-[#1a1a2e] text-sm"></i>
                            </a>
                            <a href="https://www.instagram.com/smkpesat_itxpro/" target="_blank" class="nb-btn w-9 h-9 rounded-xl bg-yellow-100 hover:bg-yellow-300 flex items-center justify-center transition-all">
                                <i class="fab fa-instagram text-[#1a1a2e] text-sm"></i>
                            </a>
                            <a href="https://www.facebook.com/people/SMK-Informatika-Pesat-It-XPro/100092495414821/" target="_blank" class="nb-btn w-9 h-9 rounded-xl bg-yellow-100 hover:bg-yellow-300 flex items-center justify-center transition-all">
                                <i class="fab fa-facebook text-[#1a1a2e] text-sm"></i>
                            </a>
                        </div>
                        <p class="text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} SMK Informatika Pesat</p>
                    </div>
                </div>
            </div>
        </footer>

        <script>
        // Data quotes untuk eligible dan tidak eligible
        const quotesEligible = [
            "Terus semangat! Pertahankan prestasi kamu dan jangan berhenti belajar. Kesempatan ini adalah hasil dari kerja keras kamu!",
            "Kamu telah membuktikan kemampuanmu. Jangan berhenti di sini, lanjutkan perjalanan menuju PTN impianmu!",
            "Selamat telah memenuhi syarat kelayakan. Persiapkan diri dengan baik untuk masa depan yang lebih cemerlang!",
            "Ini adalah buah dari dedikasi dan kerja keras kamu. Percayai diri sendiri dan raih kesempatan emas ini!",
            "Kamu pantas mendapatkan ini. Manfaatkan kesempatan ini sebaik mungkin untuk meraih impianmu!"
        ];
        
        const quotesNotEligible = [
            "Jangan menyerah! Ada jalur penerimaan mahasiswa lain seperti jalur mandiri, beasiswa, dan program unggulan yang bisa kamu kejar.",
            "Ini bukan akhir, tapi awal dari perjalanan baru. Masih banyak kesempatan dan pintu terbuka untukmu!",
            "Gunakan ini sebagai motivasi untuk terus berkembang. Prestasi sejati diraih melalui kerja keras dan ketekunan.",
            "Jangan khawatir, ada berbagai jalur alternatif yang bisa menjadi jalan kesuksesanmu menuju pendidikan tinggi.",
            "Setiap kegagalan adalah pelajaran. Terus belajar, berkembang, dan ciptakan kesempatan baru untukmu sendiri!"
        ];
        
        function getRandomQuote(quotes) {
            return quotes[Math.floor(Math.random() * quotes.length)];
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const resultModal = document.getElementById('resultModal');
            const modalContent = document.getElementById('modalContent');
            
            async function performSearch() {
                const searchTerm = searchInput.value.trim();
                
                if (!searchTerm) {
                    Swal.fire({
                        title: 'Pencarian Kosong',
                        text: 'Masukkan NIS siswa terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonColor: '#3b82f6',
                        customClass: { popup: 'swal-nb', confirmButton: 'swal-btn-nb' }
                    });
                    return;
                }
                
                loadingIndicator.classList.remove('hidden');
                
                try {
                    const response = await fetch(`/api/pencarian-eligible?type=nis&query=${encodeURIComponent(searchTerm)}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    
                    const data = await response.json();
                    
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    
                    loadingIndicator.classList.add('hidden');
                    
                    if (data.results && data.results.length > 0) {
                        displayResultModal(data.results[0]);
                    } else {
                        displayNoResults();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    loadingIndicator.classList.add('hidden');
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mencari data.',
                        icon: 'error',
                        confirmButtonColor: '#3b82f6',
                        customClass: { popup: 'swal-nb', confirmButton: 'swal-btn-nb' }
                    });
                }
            }
            
            function displayResultModal(siswa) {
                const isEligible = siswa.eligibilitas === 'eligible';
                const randomQuote = getRandomQuote(isEligible ? quotesEligible : quotesNotEligible);
                
                let content = '';
                
                if (isEligible) {
                    content = `
                        <div class="space-y-5">
                            <div class="text-center space-y-2">
                                <span class="inline-flex items-center gap-1.5 bg-green-300 text-[#1a1a2e] text-xs font-bold px-3 py-1 rounded-full" style="border: 2px solid #1a1a2e;">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    ELIGIBLE
                                </span>
                                <h1 class="text-xl font-bold text-[#1a1a2e] leading-tight">Selamat! Kamu Eligible<br><span class="text-sm font-medium text-gray-500">untuk Seleksi SNBP</span></h1>
                            </div>
                            
                            <div class="space-y-2 bg-[#fefbf4] rounded-xl p-4" style="border: 2px solid #1a1a2e;">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400 font-medium text-xs">Nama</span>
                                    <span class="text-[#1a1a2e] font-bold">${siswa.nama}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400 font-medium text-xs">NIS</span>
                                    <span class="text-[#1a1a2e] font-bold">${siswa.nis}</span>
                                </div>
                            </div>
                            
                            <p class="text-xs text-gray-500 italic text-center leading-relaxed">"${randomQuote}"</p>
                            
                            <div class="flex gap-2">
                                <a href="https://s.id/Hasil-TKA" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-3 font-bold text-white text-sm rounded-xl bg-blue-500 transition-all" style="border: 3px solid #1a1a2e; box-shadow: 3px 3px 0px 0px #1a1a2e;">
                                    Lihat Hasil TKA
                                </a>
                                <button onclick="window.closeModal()" class="flex-1 inline-flex items-center justify-center px-4 py-3 font-bold text-[#1a1a2e] text-sm rounded-xl bg-gray-100 transition-all" style="border: 3px solid #1a1a2e; box-shadow: 3px 3px 0px 0px #1a1a2e;">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    `;
                    
                    modalContent.innerHTML = content;
                    
                    setTimeout(() => {
                        const canvasContainer = document.getElementById('canvasContainer');
                        if (canvasContainer) {
                            canvasContainer.innerHTML = '<canvas id="lottieCanvas" width="140" height="140" style="max-width: 100%; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));"></canvas>';
                        }
                    }, 10);
                } else {
                    content = `
                        <div class="space-y-5">
                            <div class="text-center space-y-2">
                                <span class="inline-flex items-center gap-1.5 bg-red-300 text-[#1a1a2e] text-xs font-bold px-3 py-1 rounded-full" style="border: 2px solid #1a1a2e;">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                    TIDAK ELIGIBLE
                                </span>
                                <h1 class="text-xl font-bold text-[#1a1a2e] leading-tight">Tidak Eligible SNBP<br><span class="text-sm font-medium text-gray-500">Coba di SNBT ya!</span></h1>
                            </div>
                            
                            <div class="space-y-2 bg-[#fefbf4] rounded-xl p-4" style="border: 2px solid #1a1a2e;">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400 font-medium text-xs">Nama</span>
                                    <span class="text-[#1a1a2e] font-bold">${siswa.nama}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400 font-medium text-xs">NIS</span>
                                    <span class="text-[#1a1a2e] font-bold">${siswa.nis}</span>
                                </div>
                            </div>
                            
                            <p class="text-xs text-gray-500 italic text-center leading-relaxed">"${randomQuote}"</p>
                            
                            <div class="flex gap-2">
                                <a href="https://s.id/Hasil-TKA" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-3 font-bold text-white text-sm rounded-xl bg-blue-500 transition-all" style="border: 3px solid #1a1a2e; box-shadow: 3px 3px 0px 0px #1a1a2e;">
                                    Lihat Hasil TKA
                                </a>
                                <button onclick="window.closeModal()" class="flex-1 inline-flex items-center justify-center px-4 py-3 font-bold text-[#1a1a2e] text-sm rounded-xl bg-gray-100 transition-all" style="border: 3px solid #1a1a2e; box-shadow: 3px 3px 0px 0px #1a1a2e;">
                                    Mengerti
                                </button>
                            </div>
                        </div>
                    `;
                    
                    modalContent.innerHTML = content;
                    
                    setTimeout(() => {
                        const canvasContainer = document.getElementById('canvasContainer');
                        if (canvasContainer) {
                            canvasContainer.innerHTML = '<canvas id="lottieCanvas" width="140" height="140" style="max-width: 100%; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));"></canvas>';
                        }
                    }, 10);
                }
                
                resultModal.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
                resultModal.classList.add('opacity-100', 'visible', 'pointer-events-auto');
                
                if (isEligible) {
                    setTimeout(async () => {
                        const { DotLottie } = await import('https://cdn.jsdelivr.net/npm/@lottiefiles/dotlottie-web/+esm');
                        const canvas = document.getElementById('lottieCanvas');
                        if (canvas) {
                            new DotLottie({
                                autoplay: true,
                                loop: true,
                                canvas: canvas,
                                src: "https://lottie.host/a84500eb-0408-4fe4-96e4-2d9ee213ccef/vuNgdL8uYT.lottie"
                            });
                        }
                    }, 50);
                } else {
                    setTimeout(async () => {
                        const { DotLottie } = await import('https://cdn.jsdelivr.net/npm/@lottiefiles/dotlottie-web/+esm');
                        const canvas = document.getElementById('lottieCanvas');
                        if (canvas) {
                            new DotLottie({
                                autoplay: true,
                                loop: true,
                                canvas: canvas,
                                src: "https://lottie.host/20315d35-c05e-45c6-94bf-b0dd699db847/7YqTQUMJrF.lottie"
                            });
                        }
                    }, 50);
                }
            }
            
            function displayNoResults() {
                Swal.fire({
                    title: 'NIS Tidak Ditemukan',
                    text: 'Pastikan NIS yang kamu masukkan sudah benar, lalu coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'swal-nb', confirmButton: 'swal-btn-nb' }
                });
            }
            
            window.closeModal = function() {
                resultModal.classList.add('opacity-0', 'invisible', 'pointer-events-none');
                resultModal.classList.remove('opacity-100', 'visible', 'pointer-events-auto');
                searchInput.value = '';
            }
            
            resultModal.addEventListener('click', function(e) {
                if (e.target === resultModal) {
                    window.closeModal();
                }
            });
            
            searchButton.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        });
        </script>
    </body>
</html>
