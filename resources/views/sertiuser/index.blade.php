<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Sertifikat - Certisat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
    <body class="bg-slate-50 text-slate-800 antialiased">
        @include('profile.partials.navbar-user')

        <main class="pt-24 pb-16 px-4 sm:px-6 lg:px-10 max-w-6xl mx-auto space-y-10">
            <!-- Search Section -->
            <section>
                <div class="max-w-3xl mx-auto text-center space-y-6">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-semibold text-slate-900">Cari Sertifikat Siswa</h1>
                        <p class="mt-2 text-sm sm:text-base text-slate-600">
                            Masukkan nama atau NIS siswa untuk melihat sertifikat yang sudah diterbitkan oleh sekolah.
                        </p>
                    </div>

                    <!-- Tabs -->
                    <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1">
                        <button
                            id="tabNama"
                            class="px-4 py-2 text-sm font-medium rounded-lg active bg-orange-500 text-white"
                        >
                            Nama siswa
                        </button>
                        <button
                            id="tabNis"
                            class="px-4 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50"
                        >
                            NIS
                        </button>
                    </div>

                    <!-- Search Bar -->
                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 flex items-center gap-3 shadow-sm">
                        <div class="flex items-center w-full gap-2">
                            <span class="text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0014 0z" />
                                </svg>
                            </span>
                            <input
                                id="searchInput"
                                type="text"
                                placeholder="Masukkan nama lengkap siswa"
                                class="w-full border-0 focus:ring-0 text-sm text-slate-800 placeholder-slate-400"
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
                    <div id="loadingIndicator" class="hidden">
                        <div class="flex justify-center items-center gap-3 text-sm text-slate-500 mt-4">
                            <span class="inline-block h-4 w-4 border-2 border-orange-500 border-t-transparent rounded-full animate-spin"></span>
                            <span>Sedang mencari sertifikat...</span>
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

                    <!-- Filter untuk daftar sertifikat (per siswa) -->
                    <div id="certFilterBar" class="hidden flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm">
                        <div class="flex-1">
                            <label for="certFilterInput" class="block text-xs font-semibold text-slate-600 mb-1 text-left">
                                Filter sertifikat berdasarkan judul / jenis
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0014 0z" />
                                    </svg>
                                </span>
                                <input
                                    id="certFilterInput"
                                    type="text"
                                    class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-200 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                    placeholder="Contoh: web programming, BNSP, prestasi..."
                                >
                            </div>
                        </div>
                        <button
                            id="backToStudentsButton"
                            class="hidden inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50"
                        >
                            Kembali ke daftar siswa
                        </button>
                    </div>

                    <div id="resultsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Kartu sertifikat -->
                    </div>

                    <div id="noResults" class="hidden text-center py-10 space-y-3">
                        <p class="text-3xl">🔍</p>
                        <h3 id="noResultsTitle" class="text-base font-semibold text-slate-700">Tidak ada hasil ditemukan</h3>
                        <p id="noResultsText" class="text-xs sm:text-sm text-slate-500">
                            Pastikan nama atau NIS yang kamu masukkan sudah benar, lalu coba lagi.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Modal Sertifikat -->
        <div id="certificateModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4 opacity-0 invisible transition-opacity duration-150">
            <div id="modalDialog" class="max-w-xl w-full bg-white rounded-2xl shadow-md border border-slate-200">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Detail Sertifikat</h3>
                        <button id="closeModal" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
                    </div>
                    <div id="modalContent"></div>
                </div>
            </div>
        </div>

        <script src="https://kit.fontawesome.com/a2d9d5a64f.js" crossorigin="anonymous"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabNama = document.getElementById('tabNama');
            const tabNis = document.getElementById('tabNis');
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const resultsSection = document.getElementById('resultsSection');
            const resultsContainer = document.getElementById('resultsContainer');
            const resultsCount = document.getElementById('resultsCount');
            const noResults = document.getElementById('noResults');
            const certFilterBar = document.getElementById('certFilterBar');
            const certFilterInput = document.getElementById('certFilterInput');
            const backToStudentsButton = document.getElementById('backToStudentsButton');
            const certificateModal = document.getElementById('certificateModal');
            const modalContent = document.getElementById('modalContent');
            const closeModal = document.getElementById('closeModal');
            
            let currentSearchType = 'nama';
            let currentResults = [];
            let currentStudents = [];
            let currentStudentContext = null;
            
            // Setup CSRF token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            function setActiveTab(activeTab, inactiveTab, placeholder, searchType) {
                // Style tab aktif
                activeTab.classList.add('bg-orange-500', 'text-white');
                activeTab.classList.remove('text-slate-600');
                
                // Style tab non-aktif
                inactiveTab.classList.remove('bg-orange-500', 'text-white');
                inactiveTab.classList.add('text-slate-600');
                
                // Ganti placeholder input
                searchInput.setAttribute('placeholder', placeholder);
                searchInput.value = '';
                currentSearchType = searchType;
                
                // Sembunyikan hasil saat ganti tab
                resultsSection.classList.add('hidden');
            }
            
            // Tab event listeners
            tabNama.addEventListener('click', () => {
                setActiveTab(tabNama, tabNis, 'Masukkan nama lengkap siswa', 'nama');
            });
            
            tabNis.addEventListener('click', () => {
                setActiveTab(tabNis, tabNama, 'Masukkan NIS lengkap siswa', 'nis');
            });
            
            // Search function
            function performSearch() {
                const searchTerm = searchInput.value.trim();
                
                if (searchTerm === '') {
                    Swal.fire({
                        title: 'Pencarian Kosong',
                        text: 'Masukkan kata kunci pencarian terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        customClass: { popup: 'rounded-2xl' }
                    });
                    return;
                }
                
                // Show loading
                loadingIndicator.classList.remove('hidden');
                resultsSection.classList.add('hidden');
                
                // Make AJAX request
                fetch('{{ route("sertifikat.search.api") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        type: currentSearchType,
                        term: searchTerm
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading
                    loadingIndicator.classList.add('hidden');
                    
                    if (data.success) {
                        currentResults = Array.isArray(data.data) ? data.data : [];
                        currentStudents = [];
                        currentStudentContext = null;
                        displayResults(currentResults, searchTerm, data.meta || {});
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'Terjadi kesalahan.',
                            icon: 'error',
                            confirmButtonText: 'Mengerti',
                            customClass: { popup: 'rounded-2xl' }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingIndicator.classList.add('hidden');
                    Swal.fire({
                        title: 'Kesalahan Koneksi',
                        text: 'Tidak dapat terhubung ke server. Coba lagi nanti.',
                        icon: 'error',
                        confirmButtonText: 'Mengerti',
                        customClass: { popup: 'rounded-2xl' }
                    });
                });
            }
            
            function displayResults(results, searchTerm, meta = {}) {
                resultsContainer.innerHTML = '';
                if (certFilterBar) {
                    certFilterBar.classList.add('hidden');
                }
                if (certFilterInput) {
                    certFilterInput.value = '';
                }
                if (backToStudentsButton) {
                    backToStudentsButton.classList.add('hidden');
                }

                const siswaList = Array.isArray(meta.siswa_tanpa_sertifikat) ? meta.siswa_tanpa_sertifikat : [];
                
                // Jika tidak ada sertifikat tapi ada siswa, tampilkan kartu siswa tanpa sertifikat
                if (results.length === 0 && siswaList.length > 0) {
                    noResults.classList.add('hidden');
                    resultsContainer.classList.remove('hidden');
                    resultsCount.textContent = 'Ditemukan ' + siswaList.length + ' siswa, namun belum ada sertifikat yang tercatat untuk: "' + searchTerm + '"';

                    const titleEl = document.getElementById('noResultsTitle');
                    const textEl = document.getElementById('noResultsText');
                    if (titleEl) {
                        titleEl.textContent = 'Siswa ditemukan, sertifikat belum tersedia';
                    }
                    if (textEl) {
                        textEl.textContent = 'Data siswa ada di sistem, tetapi sertifikatnya belum diinput oleh sekolah. Sertifikat akan muncul di sini setelah diunggah oleh admin.';
                    }

                    siswaList.forEach((siswa, index) => {
                        setTimeout(() => {
                            const card = createStudentNoCertCard(siswa);
                            card.classList.add('results-enter');
                            resultsContainer.appendChild(card);

                            setTimeout(() => {
                                card.classList.remove('results-enter');
                                card.classList.add('results-enter-active');
                            }, 10);
                        }, index * 100);
                    });

                    resultsSection.classList.remove('hidden');
                    return;
                }
                
                if (results.length === 0) {
                    noResults.classList.remove('hidden');
                    resultsContainer.classList.add('hidden');
                    const titleEl = document.getElementById('noResultsTitle');
                    const textEl = document.getElementById('noResultsText');

                    if (meta.has_siswa_tanpa_sertifikat) {
                        const jumlah = meta.jumlah_siswa_tanpa_sertifikat || 0;
                        resultsCount.textContent = 'Ditemukan ' + jumlah + ' siswa, namun belum ada sertifikat yang tercatat untuk: "' + searchTerm + '"';
                        if (titleEl) {
                            titleEl.textContent = 'Siswa ditemukan, sertifikat belum tersedia';
                        }
                        if (textEl) {
                            textEl.textContent = 'Data siswa ada di sistem, tetapi sertifikatnya belum diinput oleh sekolah. Silakan hubungi admin atau wali kelas jika diperlukan.';
                        }
                    } else {
                        resultsCount.textContent = 'Tidak ada hasil untuk: "' + searchTerm + '"';
                        if (titleEl) {
                            titleEl.textContent = 'Tidak ada hasil ditemukan';
                        }
                        if (textEl) {
                            textEl.textContent = 'Pastikan nama atau NIS yang kamu masukkan sudah benar, lalu coba lagi.';
                        }
                    }
                } else {
                    // Jika cari berdasarkan nama, kelompokkan dulu berdasarkan siswa
                    if (currentSearchType === 'nama') {
                        const byNis = {};
                        results.forEach(cert => {
                            const nis = cert.nis || '-';
                            if (!byNis[nis]) {
                                byNis[nis] = {
                                    nis,
                                    nama: cert.nama_siswa || 'Tidak diketahui',
                                    kelas: cert.kelas || null,
                                    jurusan: cert.jurusan || null,
                                    count: 0,
                                };
                            }
                            byNis[nis].count++;
                        });

                        const students = Object.values(byNis);
                        currentStudents = students;

                        if (students.length > 1) {
                            renderStudentList(students, searchTerm);
                            resultsSection.classList.remove('hidden');
                            return;
                        }

                        const student = students[0];
                        const studentCerts = results.filter(cert => cert.nis === student.nis);
                        renderCertificatesForStudent(student, studentCerts);
                        resultsSection.classList.remove('hidden');
                        return;
                    }

                    // Jika cari berdasarkan NIS, anggap 1 siswa dengan banyak sertifikat
                    if (currentSearchType === 'nis' && results.length > 0) {
                        const first = results[0];
                        const student = {
                            nis: first.nis || '-',
                            nama: first.nama_siswa || 'Tidak diketahui',
                            kelas: first.kelas || null,
                            jurusan: first.jurusan || null,
                            count: results.length,
                        };
                        renderCertificatesForStudent(student, results);
                        resultsSection.classList.remove('hidden');
                        return;
                    }

                    // Fallback: tampilkan semua sertifikat apa adanya
                    noResults.classList.add('hidden');
                    resultsContainer.classList.remove('hidden');
                    resultsCount.textContent = 'Ditemukan ' + results.length + ' sertifikat untuk: "' + searchTerm + '"';
                    
                    results.forEach((cert, index) => {
                        setTimeout(() => {
                            const card = createCertificateCard(cert);
                            card.classList.add('results-enter');
                            resultsContainer.appendChild(card);
                            
                            setTimeout(() => {
                                card.classList.remove('results-enter');
                                card.classList.add('results-enter-active');
                            }, 10);
                        }, index * 100);
                    });
                }
                
                resultsSection.classList.remove('hidden');
            }

            function renderStudentList(students, searchTerm) {
                noResults.classList.add('hidden');
                resultsContainer.classList.remove('hidden');
                resultsContainer.innerHTML = '';
                resultsCount.textContent = 'Ditemukan ' + students.length + ' siswa untuk: "' + searchTerm + '". Pilih salah satu untuk melihat sertifikatnya.';

                students.forEach((student, index) => {
                    setTimeout(() => {
                        const card = createStudentResultCard(student);
                        card.classList.add('results-enter');
                        resultsContainer.appendChild(card);

                        setTimeout(() => {
                            card.classList.remove('results-enter');
                            card.classList.add('results-enter-active');
                        }, 10);
                    }, index * 80);
                });
            }

            function renderCertificatesForStudent(student, certificates, fromStudentsList = false) {
                noResults.classList.add('hidden');
                resultsContainer.classList.remove('hidden');

                currentStudentContext = {
                    nis: student.nis,
                    nama: student.nama,
                    kelas: student.kelas,
                    jurusan: student.jurusan,
                    certificates: certificates.slice(),
                };

                const detailParts = [];
                if (student.kelas) detailParts.push(student.kelas);
                if (student.jurusan) detailParts.push(student.jurusan);
                const detailText = detailParts.length ? ' · ' + detailParts.join(' · ') : '';

                resultsCount.textContent = 'Ditemukan ' + certificates.length + ' sertifikat untuk: ' + student.nama + ' (NIS ' + student.nis + ')' + detailText;

                if (certFilterBar) {
                    certFilterBar.classList.remove('hidden');
                }
                if (certFilterInput) {
                    certFilterInput.value = '';
                }
                if (backToStudentsButton) {
                    const shouldShowBack = fromStudentsList || (currentSearchType === 'nama' && currentStudents.length > 1);
                    backToStudentsButton.classList.toggle('hidden', !shouldShowBack);
                }

                resultsContainer.innerHTML = '';

                certificates.forEach((cert, index) => {
                    setTimeout(() => {
                        const card = createCertificateCard(cert);
                        card.classList.add('results-enter');
                        resultsContainer.appendChild(card);

                        setTimeout(() => {
                            card.classList.remove('results-enter');
                            card.classList.add('results-enter-active');
                        }, 10);
                    }, index * 80);
                });
            }

            function applyCertificateFilter() {
                if (!currentStudentContext) return;

                const term = (certFilterInput?.value || '').trim().toLowerCase();
                const base = currentStudentContext.certificates || [];

                const filtered = term
                    ? base.filter(cert => {
                        const title = (cert.judul_sertifikat || '').toLowerCase();
                        const jenis = (cert.jenis_sertifikat || '').toLowerCase();
                        return title.includes(term) || jenis.includes(term);
                    })
                    : base;

                resultsContainer.innerHTML = '';

                filtered.forEach((cert, index) => {
                    setTimeout(() => {
                        const card = createCertificateCard(cert);
                        card.classList.add('results-enter');
                        resultsContainer.appendChild(card);

                        setTimeout(() => {
                            card.classList.remove('results-enter');
                            card.classList.add('results-enter-active');
                        }, 10);
                    }, index * 60);
                });
            }
            
            function createCertificateCard(cert) {
                const dateText = cert.tanggal_diraih
                    ? new Date(cert.tanggal_diraih).toLocaleDateString('id-ID')
                    : '-';
                const card = document.createElement('div');
                card.className = 'rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow cursor-pointer';
                card.onclick = () => showCertificateDetail(cert.id);
                
                card.innerHTML = `
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-orange-500 text-white text-xs font-semibold">
                                ${cert.jenis_sertifikat}
                            </div>
                            <div class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                                ${dateText}
                            </div>
                        </div>
                        <h3 class="font-semibold text-sm text-slate-900 mb-1 line-clamp-2">${cert.judul_sertifikat}</h3>
                        <p class="text-xs text-slate-500 mb-1">${cert.nama_siswa}</p>
                        <p class="text-[11px] text-slate-400 mb-4">NIS: ${cert.nis}</p>
                        
                        <div class="mt-2 flex items-center text-xs font-medium text-orange-600">
                            <i class="fas fa-eye mr-2"></i>
                            <span>Lihat detail sertifikat</span>
                        </div>
                    </div>
                `;
                
                return card;
            }

            function createStudentResultCard(student) {
                const card = document.createElement('div');
                card.className = 'rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow cursor-pointer';
                card.onclick = () => {
                    const certs = currentResults.filter(c => c.nis === student.nis);
                    renderCertificatesForStudent(student, certs, true);
                };

                const detailParts = [];
                if (student.kelas) detailParts.push(student.kelas);
                if (student.jurusan) detailParts.push(student.jurusan);
                const detailText = detailParts.length ? detailParts.join(' · ') : '-';

                card.innerHTML = `
                    <div class="p-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">${student.nama}</p>
                                <p class="text-xs text-slate-500">NIS: ${student.nis}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                                ${student.count} sertifikat
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500">${detailText}</p>
                        <div class="mt-2 text-xs font-medium text-orange-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Lihat daftar sertifikat</span>
                        </div>
                    </div>
                `;

                return card;
            }

            function createStudentNoCertCard(siswa) {
                const card = document.createElement('div');
                card.className = 'rounded-2xl border border-dashed border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow';

                card.innerHTML = `
                    <div class="p-6 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                                Data siswa ditemukan
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[11px] font-semibold">
                                Belum ada sertifikat
                            </span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base text-slate-900 mb-1">${siswa.nama}</h3>
                            <p class="text-xs text-slate-500 mb-1">NIS: ${siswa.nis}</p>
                            <p class="text-xs text-slate-500">
                                ${siswa.kelas ? 'Kelas: ' + siswa.kelas + ' · ' : ''}
                                ${siswa.jurusan ? 'Jurusan: ' + siswa.jurusan : ''}
                            </p>
                        </div>
                        <p class="mt-2 text-[11px] text-slate-500">
                            Sertifikat untuk siswa ini belum tercatat di sistem. Jika kamu merasa sudah menerima sertifikat,
                            silakan hubungi admin atau wali kelas untuk memastikan datanya sudah diinput.
                        </p>
                    </div>
                `;

                return card;
            }
            
            function showCertificateDetail(certId) {
                fetch(`{{ url('/sertifikat') }}/${certId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayCertificateModal(data.data);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'Mengerti',
                            customClass: { popup: 'rounded-2xl' }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Kesalahan',
                        text: 'Terjadi kesalahan saat mengambil detail sertifikat.',
                        icon: 'error',
                        confirmButtonText: 'Mengerti',
                        customClass: { popup: 'rounded-2xl' }
                    });
                });
            }
            
            function displayCertificateModal(cert) {
                const dateText = cert.tanggal_diraih
                    ? new Date(cert.tanggal_diraih).toLocaleDateString('id-ID')
                    : '-';

                const hasPhoto = !!cert.foto_sertifikat;

                const photoSection = hasPhoto
                    ? `
                        <div class="neumorphism rounded-2xl p-6">
                            <img src="{{ asset('storage') }}/${cert.foto_sertifikat}" 
                                alt="Foto Sertifikat" 
                                class="max-w-full h-auto rounded-xl mx-auto shadow-2xl hover:shadow-blue-500/25 transition-shadow duration-500"
                                style="max-height: 300px;">
                        </div>
                    `
                    : `
                        <div class="neumorphism-inset rounded-2xl p-6 bg-gray-50 flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7l2 12h14l2-12M8 7V4a4 4 0 018 0v3" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Belum ada file sertifikat yang diunggah.</p>
                            <p class="mt-1 text-xs text-gray-500">Hubungi admin sekolah jika kamu membutuhkan salinan file sertifikat.</p>
                        </div>
                    `;

                modalContent.innerHTML = `
                    <div class="space-y-6">
                        <div class="text-center mb-4">
                            <h4 class="text-lg font-semibold text-slate-900 mb-3">${cert.judul_sertifikat}</h4>
                            ${photoSection}
                            <div class="mt-4 flex flex-wrap justify-center gap-3">
                                <a
                                    href="{{ url('/sertifikat') }}/${cert.id}/kartu"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-orange-500 text-white hover:bg-orange-600"
                                >
                                    Kartu & QR / Cetak
                                </a>
                                ${hasPhoto ? `
                                    <a
                                        href="{{ asset('storage') }}/${cert.foto_sertifikat}"
                                        download
                                        class="inline-flex items-center px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50"
                                    >
                                        Unduh gambar sertifikat
                                    </a>
                                ` : ''}
                            </div>
                        </div>
                        
	                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rounded-xl border border-slate-200 p-4 bg-slate-50">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama siswa</label>
                                <p class="text-sm font-medium text-slate-900">${cert.nama}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 p-4 bg-slate-50">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">NIS</label>
                                <p class="text-sm font-medium text-slate-900">${cert.nis}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 p-4 bg-slate-50">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis sertifikat</label>
                                <p class="text-sm font-medium text-slate-900">${cert.jenis_sertifikat}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 p-4 bg-slate-50">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal diraih</label>
                                <p class="text-sm font-medium text-slate-900">${dateText}</p>
                            </div>
                        </div>
                    </div>
                `;
                
	                // Show modal (tanpa animasi lebay)
	                const modal = certificateModal;
	                modal.classList.remove('opacity-0', 'invisible');
	                modal.classList.add('opacity-100', 'visible');
            }
            
            // Event listeners
            searchButton.addEventListener('click', performSearch);
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            if (certFilterInput) {
                certFilterInput.addEventListener('input', applyCertificateFilter);
            }

            if (backToStudentsButton) {
                backToStudentsButton.addEventListener('click', function() {
                    if (currentSearchType === 'nama' && currentStudents.length > 0) {
                        const term = searchInput.value.trim();
                        renderStudentList(currentStudents, term || '');
                    }
                });
            }
            
            closeModal.addEventListener('click', () => {
                closeModalWithAnimation();
            });
            
            certificateModal.addEventListener('click', function(e) {
                if (e.target === certificateModal) {
                    closeModalWithAnimation();
                }
            });
            
            // close modal
	            function closeModalWithAnimation() {
	                const modal = certificateModal;
	                modal.classList.remove('opacity-100', 'visible');
	                modal.classList.add('opacity-0', 'invisible');
            }
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !certificateModal.classList.contains('invisible')) {
                    closeModalWithAnimation();
                }
            });
        });

        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;
            
            setInterval(() => {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.width = particle.style.height = Math.random() * 8 + 4 + 'px';
                particle.style.animationDuration = (Math.random() * 3 + 2) + 's';
                particle.style.animationDelay = Math.random() * 2 + 's';
                particlesContainer.appendChild(particle);
                
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.remove();
                    }
                }, 6000);
            }, 800);
        }
        </script>
    </body>
</html>
