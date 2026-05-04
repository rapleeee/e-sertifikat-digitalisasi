@extends('layouts.glass')

@section('title', 'Cek Kelulusan - ' . config('app.name', 'Certisat'))
@section('meta_description', 'Cek pengumuman kelulusan siswa SMK Informatika Pesat secara online berdasarkan NIS atau nama lengkap pada jadwal resmi sekolah.')
@section('meta_keywords', 'cek kelulusan SMK Informatika Pesat, pengumuman kelulusan, hasil kelulusan siswa')
@section('canonical_url', route('kelulusan.index'))
@section('og_title', 'Cek Kelulusan Siswa | SMK Informatika Pesat')
@section('og_description', 'Lihat pengumuman kelulusan resmi SMK Informatika Pesat dengan cepat, aman, dan terpercaya.')
@section('og_url', route('kelulusan.index'))
@section('og_image', asset('images/og-kelulusan.jpg'))

@section('main-class', 'px-4 pb-24 md:pb-8 pt-8 md:pt-24')

@section('content')
<div
    class="max-w-4xl mx-auto py-8 sm:py-12 space-y-6"
    data-open-at="{{ $announcementAt->toIso8601String() }}"
    data-can-open="{{ $canOpen ? 'true' : 'false' }}"
    id="kelulusan-page"
>
    <section class="text-center space-y-5">
        <div class="inline-flex items-center gap-2 bg-blue-300 nb-border-2 nb-shadow-sm rounded-full px-4 py-1.5 text-xs font-bold text-[#1a1a2e] tracking-wide uppercase">
            SMK Informatika Pesat
        </div>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-[#1a1a2e] leading-[1.1] tracking-tight">
            Cek
            <span class="bg-yellow-300 px-2 nb-border-2 inline-block -rotate-1">Kelulusan</span>
        </h1>
        <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto leading-relaxed">
            Pengumuman kelulusan dapat diakses mulai
            <span class="font-bold text-[#1a1a2e]">{{ $announcementAt->translatedFormat('d F Y, H:i') }} WIB</span>.
        </p>
    </section>

    <section id="countdownSection" class="{{ $canOpen ? 'hidden' : '' }}">
        <div class="nb-card rounded-2xl p-5 sm:p-6 bg-yellow-100">
            <p class="text-center text-xs font-bold uppercase tracking-widest text-gray-500 mb-4">Dibuka dalam</p>
            <div class="grid grid-cols-4 gap-2 sm:gap-4">
                <div class="bg-white nb-border-2 rounded-xl p-3 text-center">
                    <p id="days" class="text-2xl sm:text-4xl font-bold">00</p>
                    <p class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase">Hari</p>
                </div>
                <div class="bg-white nb-border-2 rounded-xl p-3 text-center">
                    <p id="hours" class="text-2xl sm:text-4xl font-bold">00</p>
                    <p class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase">Jam</p>
                </div>
                <div class="bg-white nb-border-2 rounded-xl p-3 text-center">
                    <p id="minutes" class="text-2xl sm:text-4xl font-bold">00</p>
                    <p class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase">Menit</p>
                </div>
                <div class="bg-white nb-border-2 rounded-xl p-3 text-center">
                    <p id="seconds" class="text-2xl sm:text-4xl font-bold">00</p>
                    <p class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase">Detik</p>
                </div>
            </div>
        </div>
    </section>

    <section id="searchSection" class="{{ $canOpen ? '' : 'hidden' }}">
        <div class="nb-card rounded-2xl p-5 sm:p-6 space-y-4">
            <div class="grid grid-cols-2 gap-2">
                <button type="button" data-search-type="nis" class="search-type-btn nb-border-2 rounded-xl px-4 py-2.5 text-sm font-bold bg-yellow-300">
                    NIS
                </button>
                <button type="button" data-search-type="nama" class="search-type-btn nb-border-2 rounded-xl px-4 py-2.5 text-sm font-bold bg-white">
                    Nama Lengkap
                </button>
            </div>

            <div class="flex items-center gap-3 bg-[#fefbf4] nb-border-2 rounded-xl px-4 py-3.5 focus-within:border-blue-500 transition-all">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0114 0z" />
                </svg>
                <input
                    id="graduationSearchInput"
                    type="text"
                    placeholder="Masukkan NIS siswa..."
                    class="w-full bg-transparent border-0 focus:ring-0 text-sm text-[#1a1a2e] placeholder-gray-400 font-medium p-0"
                >
            </div>

            <button id="graduationSearchButton" type="button" class="nb-btn w-full py-3.5 rounded-xl bg-blue-500 text-white text-sm font-bold tracking-wide">
                Cek Kelulusan
            </button>

            <div id="graduationLoading" class="hidden">
                <div class="flex justify-center items-center gap-3 py-2 text-sm text-[#1a1a2e] font-medium">
                    <span class="inline-block h-5 w-5 border-2 border-[#1a1a2e] border-t-transparent rounded-full animate-spin"></span>
                    <span>Sedang mencari data...</span>
                </div>
            </div>
        </div>

        <div id="multipleResults" class="hidden mt-5 nb-card rounded-2xl p-4 space-y-3"></div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const page = document.getElementById('kelulusan-page');
        const openAt = new Date(page.dataset.openAt).getTime();
        const countdownSection = document.getElementById('countdownSection');
        const searchSection = document.getElementById('searchSection');
        const searchInput = document.getElementById('graduationSearchInput');
        const searchButton = document.getElementById('graduationSearchButton');
        const loading = document.getElementById('graduationLoading');
        const multipleResults = document.getElementById('multipleResults');
        const typeButtons = Array.from(document.querySelectorAll('.search-type-btn'));
        const graduationNote = @json($graduationNote);
        let searchType = 'nis';

        const setText = (id, value) => {
            const element = document.getElementById(id);
            if (element) element.textContent = String(value).padStart(2, '0');
        };

        function unlockSearch() {
            countdownSection.classList.add('hidden');
            searchSection.classList.remove('hidden');
        }

        function tickCountdown() {
            const distance = openAt - Date.now();

            if (distance <= 0) {
                unlockSearch();
                return;
            }

            setText('days', Math.floor(distance / (1000 * 60 * 60 * 24)));
            setText('hours', Math.floor((distance / (1000 * 60 * 60)) % 24));
            setText('minutes', Math.floor((distance / (1000 * 60)) % 60));
            setText('seconds', Math.floor((distance / 1000) % 60));
        }

        if (!countdownSection.classList.contains('hidden')) {
            tickCountdown();
            setInterval(tickCountdown, 1000);
        }

        typeButtons.forEach(button => {
            button.addEventListener('click', function () {
                searchType = button.dataset.searchType;
                typeButtons.forEach(item => {
                    item.classList.toggle('bg-yellow-300', item === button);
                    item.classList.toggle('bg-white', item !== button);
                });
                searchInput.value = '';
                searchInput.placeholder = searchType === 'nis' ? 'Masukkan NIS siswa...' : 'Masukkan nama lengkap siswa...';
                searchInput.focus();
            });
        });

        function resultHtml(siswa) {
            const jurusan = siswa.jurusan || '-';
            const formattedNote = escapeHtml(graduationNote).replace(/\n/g, '<br>');
            const isDeferred = siswa.status === 'tunda_lulus';
            const deferredNote = escapeHtml(
                siswa.keterangan || `Kelulusan Anda ${siswa.nama} ditunda. Silakan datang ke sekolah menyelesaikan Ujian Pesat Method.`
            );

            if (isDeferred) {
                return `
                    <div class="space-y-4 text-left">
                        <div class="text-center space-y-2">
                            <span class="inline-flex items-center gap-1.5 bg-amber-300 text-[#1a1a2e] text-xs font-bold px-3 py-1 rounded-full" style="border: 2px solid #1a1a2e;">
                                KELULUSAN DITUNDA
                            </span>
                            <h2 class="text-xl sm:text-2xl font-bold text-[#1a1a2e] leading-tight">Perhatian, ${escapeHtml(siswa.nama)}</h2>
                        </div>
                        <div class="space-y-2 bg-[#fefbf4] rounded-xl p-4" style="border: 2px solid #1a1a2e;">
                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-gray-500 font-medium text-xs">Nama Lengkap</span>
                                <span class="text-[#1a1a2e] font-bold text-right">${escapeHtml(siswa.nama)}</span>
                            </div>
                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-gray-500 font-medium text-xs">NIS</span>
                                <span class="text-[#1a1a2e] font-bold">${escapeHtml(siswa.nis)}</span>
                            </div>
                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-gray-500 font-medium text-xs">Jurusan</span>
                                <span class="text-[#1a1a2e] font-bold text-right">${escapeHtml(jurusan)}</span>
                            </div>
                        </div>
                        <div class="bg-amber-100 rounded-xl p-4 text-sm text-[#1a1a2e] leading-relaxed" style="border: 2px solid #1a1a2e;">
                            ${deferredNote}
                        </div>
                    </div>
                `;
            }

            return `
                <div class="space-y-4 text-left">
                    <div class="text-center space-y-2">
                        <span class="inline-flex items-center gap-1.5 bg-green-300 text-[#1a1a2e] text-xs font-bold px-3 py-1 rounded-full" style="border: 2px solid #1a1a2e;">
                            LULUS
                        </span>
                        <h2 class="text-xl sm:text-2xl font-bold text-[#1a1a2e] leading-tight">Selamat atas kelulusannya, ${escapeHtml(siswa.nama)}!</h2>
                    </div>
                    <div class="space-y-2 bg-[#fefbf4] rounded-xl p-4" style="border: 2px solid #1a1a2e;">
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-gray-500 font-medium text-xs">Nama Lengkap</span>
                            <span class="text-[#1a1a2e] font-bold text-right">${escapeHtml(siswa.nama)}</span>
                        </div>
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-gray-500 font-medium text-xs">NIS</span>
                            <span class="text-[#1a1a2e] font-bold">${escapeHtml(siswa.nis)}</span>
                        </div>
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-gray-500 font-medium text-xs">Jurusan</span>
                            <span class="text-[#1a1a2e] font-bold text-right">${escapeHtml(jurusan)}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed text-center">
                        Dinyatakan <strong>lulus</strong> dari SMK Informatika Pesat.
                    </p>
                    <div class="bg-yellow-100 rounded-xl p-4 text-xs sm:text-sm text-[#1a1a2e] leading-relaxed" style="border: 2px solid #1a1a2e;">
                        <strong>Catatan:</strong> ${formattedNote}
                    </div>
                </div>
            `;
        }

        function showResult(siswa) {
            Swal.fire({
                html: resultHtml(siswa),
                width: 520,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#3b82f6',
                customClass: { popup: 'swal-nb', confirmButton: 'swal-btn-nb' }
            });
        }

        function renderMultiple(results) {
            multipleResults.innerHTML = `
                <p class="text-sm font-bold text-[#1a1a2e]">Ditemukan ${results.length} siswa. Pilih nama yang sesuai.</p>
                <div class="space-y-2">
                    ${results.map(siswa => `
                        <button type="button" data-id="${siswa.id}" class="graduation-result-btn w-full text-left bg-[#fefbf4] nb-border-2 rounded-xl p-3 hover:bg-yellow-100 transition">
                            <span class="block text-sm font-bold text-[#1a1a2e]">${escapeHtml(siswa.nama)}</span>
                            <span class="block text-xs text-gray-500">NIS ${escapeHtml(siswa.nis)} · ${escapeHtml(siswa.jurusan || '-')}</span>
                            <span class="inline-flex mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold ${siswa.status === 'tunda_lulus' ? 'bg-amber-200 text-amber-800' : 'bg-green-200 text-green-800'}">
                                ${siswa.status === 'tunda_lulus' ? 'KELULUSAN DITUNDA' : 'LULUS'}
                            </span>
                        </button>
                    `).join('')}
                </div>
            `;

            multipleResults.classList.remove('hidden');
            multipleResults.querySelectorAll('.graduation-result-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const siswa = results.find(item => String(item.id) === button.dataset.id);
                    if (siswa) showResult(siswa);
                });
            });
        }

        async function performSearch() {
            const query = searchInput.value.trim();
            multipleResults.classList.add('hidden');

            if (!query) {
                Swal.fire({
                    title: 'Pencarian Kosong',
                    text: searchType === 'nis' ? 'Masukkan NIS siswa terlebih dahulu.' : 'Masukkan nama lengkap siswa terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'swal-nb', confirmButton: 'swal-btn-nb' }
                });
                return;
            }

            loading.classList.remove('hidden');

            try {
                const response = await fetch(`{{ route('kelulusan.search.api') }}?type=${encodeURIComponent(searchType)}&query=${encodeURIComponent(query)}`, {
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();
                loading.classList.add('hidden');

                if (!response.ok) {
                    Swal.fire({
                        title: 'Belum Dibuka',
                        text: data.message || 'Pengumuman kelulusan belum dibuka.',
                        icon: 'info',
                        confirmButtonColor: '#3b82f6',
                        customClass: { popup: 'swal-nb', confirmButton: 'swal-btn-nb' }
                    });
                    return;
                }

                if (!data.results || data.results.length === 0) {
                    Swal.fire({
                        title: 'Data Tidak Ditemukan',
                        text: 'Pastikan NIS atau nama lengkap yang dimasukkan sudah benar.',
                        icon: 'error',
                        confirmButtonColor: '#3b82f6',
                        customClass: { popup: 'swal-nb', confirmButton: 'swal-btn-nb' }
                    });
                    return;
                }

                if (data.results.length === 1) {
                    showResult(data.results[0]);
                    return;
                }

                renderMultiple(data.results);
            } catch (error) {
                loading.classList.add('hidden');
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mencari data.',
                    icon: 'error',
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'swal-nb', confirmButton: 'swal-btn-nb' }
                });
            }
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function (char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;',
                }[char];
            });
        }

        searchButton.addEventListener('click', performSearch);
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        });
    });
</script>
@endpush
@endsection
