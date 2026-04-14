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
    <style>
        .brutal-shadow { box-shadow: 5px 5px 0px 0px #000; }
        .brutal-shadow:hover { box-shadow: 3px 3px 0px 0px #000; transform: translate(2px, 2px); }
        /* SweetAlert2 Brutalist Override */
        .swal-brutal { border: 3px solid #000 !important; border-radius: 0 !important; box-shadow: 8px 8px 0px 0px #000 !important; font-family: inherit !important; }
        .swal-brutal .swal2-title { font-weight: 900 !important; text-transform: uppercase !important; letter-spacing: -0.025em !important; color: #000 !important; }
        .swal-brutal .swal2-html-container { color: #374151 !important; }
        .swal-btn-brutal { border: 3px solid #000 !important; border-radius: 0 !important; font-weight: 900 !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; box-shadow: 3px 3px 0px 0px #000 !important; transition: all 0.1s !important; }
        .swal-btn-brutal:hover { box-shadow: 1px 1px 0px 0px #000 !important; transform: translate(2px, 2px) !important; }
    </style>
</head>
    <body class="bg-amber-50 text-black antialiased">
        @include('profile.partials.navbar-user')

        <main class="pt-24 pb-16 px-4 sm:px-6 lg:px-10 max-w-6xl mx-auto space-y-10">
            <!-- Search Section -->
            <section>
                <div class="max-w-3xl mx-auto text-center space-y-6">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-black text-black uppercase tracking-tight">Cek Status Eligible PTN dan Hasil TKA</h1>
                        <p class="mt-4 text-sm sm:text-base text-gray-600">
                            Masukkan NIS siswa untuk melihat status kelulusan kelayakan untuk masuk PTN yang telah ditetapkan oleh sekolah dan untuk melihat hasil TKA.
                        </p>
                    </div>

                    <!-- Search Bar -->
                    <div class="border-[3px] border-black bg-white px-4 py-3 flex items-center gap-3 brutal-shadow">
                        <div class="flex items-center w-full gap-2">
                            <span class="text-black">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input
                                id="searchInput"
                                type="text"
                                placeholder="Masukkan NIS siswa"
                                class="w-full border-0 focus:ring-0 text-sm text-black placeholder-gray-400 bg-transparent font-medium"
                                inputmode="numeric"
                            >
                        </div>
                        <button
                            id="searchButton"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-black uppercase tracking-wide border-[3px] border-black bg-orange-500 text-white hover:bg-orange-600 transition-colors"
                        >
                            Cari
                        </button>
                    </div>

                    <!-- Loading -->
                    <div id="loadingIndicator" class="hidden mt-4">
                        <div class="flex justify-center items-center gap-3 text-sm text-black font-bold">
                            <span class="inline-block h-5 w-5 border-[3px] border-orange-500 border-t-transparent rounded-full animate-spin"></span>
                            <span>Sedang mencari siswa...</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Hasil -->
            <section id="resultsSection" class="hidden">
                <div class="space-y-6">
                    <div id="resultsHeader" class="text-center space-y-2">
                        <h2 class="text-xl font-black text-black uppercase tracking-tight">Hasil pencarian</h2>
                        <p id="resultsCount" class="text-sm text-gray-600 font-medium"></p>
                    </div>

                    <div id="noResults" class="hidden text-center py-10 space-y-3">
                        <p class="text-5xl">✕</p>
                        <h3 id="noResultsTitle" class="text-base font-black text-black uppercase">NIS tidak ditemukan</h3>
                        <p id="noResultsText" class="text-xs sm:text-sm text-gray-600">
                            Pastikan NIS yang kamu masukkan sudah benar, lalu coba lagi.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Modal Result -->
        <div id="resultModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 opacity-0 invisible transition-opacity transition-visibility duration-150 pointer-events-none">
            <div class="relative">
                <!-- Canvas positioned half outside -->
                <div id="canvasContainer" class="absolute left-1/2 -translate-x-1/2 -top-20 z-10"></div>
                <!-- Card content -->
                <div id="modalDialog" class="max-w-md w-full bg-white border-[3px] border-black shadow-[8px_8px_0px_0px_#000] overflow-hidden" style="margin-top: 60px;">
                    <div id="modalContent" class="p-8"></div>
                </div>
            </div>
        </div>

        <script src="https://kit.fontawesome.com/a2d9d5a64f.js" crossorigin="anonymous"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
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
        
        // Function untuk ambil quotes random
        function getRandomQuote(quotes) {
            return quotes[Math.floor(Math.random() * quotes.length)];
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Get all elements
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const resultModal = document.getElementById('resultModal');
            const modalContent = document.getElementById('modalContent');
            
            console.log('✅ Page loaded. All elements found.');
            
            // Search function
            async function performSearch() {
                const searchTerm = searchInput.value.trim();
                
                if (!searchTerm) {
                    Swal.fire({
                        title: 'Pencarian Kosong',
                        text: 'Masukkan NIS siswa terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonColor: '#f97316',
                        customClass: { popup: 'swal-brutal', confirmButton: 'swal-btn-brutal' }
                    });
                    return;
                }
                
                console.log('🔍 Searching for NIS:', searchTerm);
                loadingIndicator.classList.remove('hidden');
                
                try {
                    // Fetch dari API
                    const response = await fetch(`/api/pencarian-eligible?type=nis&query=${encodeURIComponent(searchTerm)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    console.log('✅ API Response:', data);
                    
                    // Show loading untuk 2 detik
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    console.log('⏱️ Loading delay completed');
                    
                    loadingIndicator.classList.add('hidden');
                    
                    if (data.results && data.results.length > 0) {
                        displayResultModal(data.results[0]);
                    } else {
                        displayNoResults();
                    }
                } catch (error) {
                    console.error('❌ Error:', error);
                    loadingIndicator.classList.add('hidden');
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mencari data.',
                        icon: 'error',
                        confirmButtonColor: '#f97316',
                        customClass: { popup: 'swal-brutal', confirmButton: 'swal-btn-brutal' }
                    });
                }
            }
            
            // Display result modal
            function displayResultModal(siswa) {
                console.log('📋 Displaying result for:', siswa.nama);
                const isEligible = siswa.eligibilitas === 'eligible';
                const randomQuote = getRandomQuote(isEligible ? quotesEligible : quotesNotEligible);
                
                let content = '';
                
                if (isEligible) {
                    content = `
                        <div class="space-y-4">
                            <!-- Greeting -->
                            <div class="text-center space-y-1">
                                <p class="text-sm text-gray-600 font-bold uppercase tracking-wide">Selamat</p>
                                <h1 class="text-2xl font-black text-black tracking-tight uppercase">Kamu Eligible untuk <br/> <span class="text-sm font-bold normal-case text-gray-600">Seleksi SNBP</span></h1>
                            </div>
                            
                            <!-- Data Siswa -->
                            <div class="space-y-2 border-y-[2px] border-black py-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-xs">Nama</span>
                                    <span class="text-black font-black">${siswa.nama}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-xs">NIS</span>
                                    <span class="text-black font-black">${siswa.nis}</span>
                                </div>
                            </div>
                            
                            <!-- Quote -->
                            <p class="text-sm text-gray-600 italic text-center">
                                "${randomQuote}"
                            </p>
                            
                            <div class="flex gap-2">
                                <a href="https://s.id/Hasil-TKA" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 font-black text-white text-sm uppercase tracking-wide border-[3px] border-black bg-orange-500 hover:bg-orange-600 transition-colors">
                                    Lihat Hasil TKA
                                </a>
                                <button onclick="window.closeModal()" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 font-black text-black text-sm uppercase tracking-wide border-[3px] border-black bg-amber-400 hover:bg-amber-300 transition-colors">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Setup modal content dulu
                    modalContent.innerHTML = content;
                    
                    // Baru setup canvas
                    setTimeout(() => {
                        const canvasContainer = document.getElementById('canvasContainer');
                        if (canvasContainer) {
                            canvasContainer.innerHTML = '<canvas id="lottieCanvas" width="140" height="140" style="max-width: 100%; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));"></canvas>';
                        }
                    }, 10);
                } else {
                    content = `
                        <div class="space-y-4">
                            <!-- Greeting -->
                            <div class="text-center space-y-1">
                                <p class="text-sm text-gray-600 font-bold uppercase tracking-wide">Informasi</p>
                                <h1 class="text-2xl font-black text-black tracking-tight uppercase">Kamu Tidak Eligible </br> <span class="font-bold normal-case text-sm text-gray-600">untuk seleksi SNBP, coba di SNBT ya</span> </h1>
                            </div>
                            
                            <!-- Data Siswa -->
                            <div class="space-y-2 border-y-[2px] border-black py-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-xs">Nama</span>
                                    <span class="text-black font-black">${siswa.nama}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-xs">NIS</span>
                                    <span class="text-black font-black">${siswa.nis}</span>
                                </div>
                            </div>
                            
                            <!-- Quote -->
                            <p class="text-sm text-gray-600 italic text-center">
                                "${randomQuote}"
                            </p>
                            
                            <div class="flex gap-2">
                                <a href="https://s.id/Hasil-TKA" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 font-black text-white text-sm uppercase tracking-wide border-[3px] border-black bg-orange-500 hover:bg-orange-600 transition-colors">
                                    Lihat Hasil TKA
                                </a>
                                <button onclick="window.closeModal()" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 font-black text-white text-sm uppercase tracking-wide border-[3px] border-black bg-gray-700 hover:bg-gray-600 transition-colors">
                                    Mengerti
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Setup modal content
                    modalContent.innerHTML = content;
                    
                    // Setup canvas container untuk not eligible dengan animasi berbeda
                    setTimeout(() => {
                        const canvasContainer = document.getElementById('canvasContainer');
                        if (canvasContainer) {
                            canvasContainer.innerHTML = '<canvas id="lottieCanvas" width="140" height="140" style="max-width: 100%; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));"></canvas>';
                        }
                    }, 10);
                }
                
                // Show modal
                resultModal.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
                resultModal.classList.add('opacity-100', 'visible', 'pointer-events-auto');
                
                // Initialize Lottie animation untuk eligible
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
                
                console.log('✅ Modal opened');
            }
            
            // Display no results
            function displayNoResults() {
                console.log('❌ No results found');
                Swal.fire({
                    title: 'NIS Tidak Ditemukan',
                    text: 'Pastikan NIS yang kamu masukkan sudah benar, lalu coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#f97316',
                    customClass: { popup: 'swal-brutal', confirmButton: 'swal-btn-brutal' }
                });
            }
            
            // Close modal
            window.closeModal = function() {
                console.log('🚪 Closing modal');
                resultModal.classList.add('opacity-0', 'invisible', 'pointer-events-none');
                resultModal.classList.remove('opacity-100', 'visible', 'pointer-events-auto');
                searchInput.value = '';
            }
            
            // Close modal when clicking outside
            resultModal.addEventListener('click', function(e) {
                if (e.target === resultModal) {
                    window.closeModal();
                }
            });
            
            // Event listeners
            searchButton.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            console.log('✅ Event listeners attached');
        });
        </script>

        @include('profile.partials.footer')
    </body>
</html>
