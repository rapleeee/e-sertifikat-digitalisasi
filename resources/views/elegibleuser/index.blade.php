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
    
</head>
    <body class="bg-slate-50 text-slate-800 antialiased">
        @include('profile.partials.navbar-user')

        <main class="pt-24 pb-16 px-4 sm:px-6 lg:px-10 max-w-6xl mx-auto space-y-10">
            <!-- Search Section -->
            <section>
                <div class="max-w-3xl mx-auto text-center space-y-6">
                    <div>
                        <!-- Canvas element where the animation will be rendered -->

                        <h1 class="text-3xl sm:text-4xl font-semibold text-slate-900">Cek Status Eligible PTN</h1>
                        <p class="mt-2 text-sm sm:text-base text-slate-600">
                            Masukkan NIS siswa untuk melihat status kelulusan dan kelayakan untuk masuk PTN yang telah ditetapkan oleh sekolah.
                        </p>
                    </div>

                    <!-- Search Bar -->
                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 flex items-center gap-3 shadow-sm">
                        <div class="flex items-center w-full gap-2">
                            <span class="text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input
                                id="searchInput"
                                type="text"
                                placeholder="Masukkan NIS siswa"
                                class="w-full border-0 focus:ring-0 text-sm text-slate-800 placeholder-slate-400"
                                inputmode="numeric"
                            >
                        </div>
                        <button
                            id="searchButton"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-lg bg-orange-500 text-white hover:bg-orange-600"
                        >
                            Cari
                        </button>
                    </div>

                    <!-- Loading -->
                    <div id="loadingIndicator" class="hidden mt-4">
                        <div class="flex justify-center items-center gap-3 text-sm text-slate-500">
                            <span class="inline-block h-4 w-4 border-2 border-orange-500 border-t-transparent rounded-full animate-spin"></span>
                            <span>Sedang mencari siswa...</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Hasil -->
            <section id="resultsSection" class="hidden">
                <div class="space-y-6">
                    <div id="resultsHeader" class="text-center space-y-2">
                        <h2 class="text-xl font-semibold text-slate-900">Hasil pencarian</h2>
                        <p id="resultsCount" class="text-sm text-slate-500"></p>
                    </div>

                    <div id="noResults" class="hidden text-center py-10 space-y-3">
                        <p class="text-3xl">🔍</p>
                        <h3 id="noResultsTitle" class="text-base font-semibold text-slate-700">NIS tidak ditemukan</h3>
                        <p id="noResultsText" class="text-xs sm:text-sm text-slate-500">
                            Pastikan NIS yang kamu masukkan sudah benar, lalu coba lagi.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Modal Result -->
        <div id="resultModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4 opacity-0 invisible transition-opacity transition-visibility duration-150 pointer-events-none">
            <div class="relative">
                <!-- Canvas positioned half outside -->
                <div id="canvasContainer" class="absolute left-1/2 -translate-x-1/2 -top-20 z-10"></div>
                <!-- Card content -->
                <div id="modalDialog" class="max-w-md w-full bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden" style="margin-top: 60px;">
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
                        confirmButtonColor: '#ea580c'
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
                        confirmButtonColor: '#ea580c'
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
                                <p class="text-sm text-slate-600">Selamat</p>
                                <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Kamu Eligible untuk <br/> <span class="text-sm font-normal text-slate-600">Seleksi SNBP</span></h1>
                            </div>
                            
                            <!-- Data Siswa - Minimal -->
                            <div class="space-y-2 border-y border-slate-100 py-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Nama</span>
                                    <span class="text-slate-900 font-medium">${siswa.nama}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">NIS</span>
                                    <span class="text-slate-900 font-medium">${siswa.nis}</span>
                                </div>
                            </div>
                            
                            <!-- Quote -->
                            <p class="text-sm text-slate-600 italic text-center">
                                "${randomQuote}"
                            </p>
                            
                            <div class="flex gap-2">
                                <a href="https://s.id/Hasil-TKA" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition text-sm">
                                    Lihat Hasil TKA
                                </a>
                                <button onclick="window.closeModal()" class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white font-semibold rounded-lg transition text-sm" style="background-color: #CDB885;" onmouseover="this.style.backgroundColor='#b8a86f'" onmouseout="this.style.backgroundColor='#CDB885'">
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
                                <p class="text-sm text-slate-600">Informasi</p>
                                <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Kamu Tidak Eligible </br> <span class="font-normal text-sm">untuk seleksi SNBP, coba di SNBT ya 👋🏼</span> </h1>
                            </div>
                            
                            <!-- Data Siswa - Minimal -->
                            <div class="space-y-2 border-y border-slate-100 py-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Nama</span>
                                    <span class="text-slate-900 font-medium">${siswa.nama}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">NIS</span>
                                    <span class="text-slate-900 font-medium">${siswa.nis}</span>
                                </div>
                            </div>
                            
                            <!-- Quote -->
                            <p class="text-sm text-slate-600 italic text-center">
                                "${randomQuote}"
                            </p>
                            
                            <div class="flex gap-2">
                                <a href="https://s.id/Hasil-TKA" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition text-sm">
                                    Lihat Hasil TKA
                                </a>
                                <button onclick="window.closeModal()" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white font-semibold rounded-lg transition text-sm">
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
                    confirmButtonColor: '#ea580c'
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
    </body>
</html>
