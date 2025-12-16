<x-app-layout>
    <div 
        x-data="{
            open: JSON.parse(localStorage.getItem('sidebarOpen') || 'true'),
        }"
        x-init="
            window.addEventListener('sidebar-toggled', () => {
                open = JSON.parse(localStorage.getItem('sidebarOpen'));
            });
        "
        :class="open ? 'ml-64' : ''"
        class="transition-all duration-300"
    >
        <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-10 max-w-7xl mx-auto space-y-6">
            @php
                $backLink = $sertifikat->siswa ? route('siswa.show', $sertifikat->siswa) : route('dashboard');
            @endphp
            <div class="flex items-center gap-3 text-sm text-slate-500">
                <a href="{{ $backLink }}" class="inline-flex items-center hover:text-orange-600 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Detail Sertifikat</span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-200 bg-slate-50">
                    <p class="uppercase tracking-[0.25em] text-xs font-semibold text-slate-500 mb-2">Sertifikat</p>
                    <h1 class="text-2xl md:text-3xl font-semibold text-slate-900">{{ $sertifikat->judul_sertifikat }}</h1>
                    <div class="mt-4 flex flex-wrap items-center gap-3 text-xs sm:text-sm">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm text-slate-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $sertifikat->siswa->nama ?? 'Siswa tidak ditemukan' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm text-slate-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6a9 9 0 100 18 9 9 0 000-18z"/>
                            </svg>
                            NIS: {{ $sertifikat->siswa->nis ?? '-' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm text-slate-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-9 4h4"/>
                            </svg>
                            Diraih: {{ $sertifikat->tanggal_diraih ? \Carbon\Carbon::parse($sertifikat->tanggal_diraih)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                </div>

                <div class="px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="rounded-2xl border border-slate-200 p-6 bg-slate-50">
                            <h2 class="text-base font-semibold text-slate-700 mb-3">Informasi Sertifikat</h2>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-slate-600">
                                <div>
                                    <dt class="font-medium text-slate-500 uppercase tracking-wider text-xs">Jenis</dt>
                                    <dd class="mt-1 text-slate-800 font-semibold">{{ $sertifikat->jenis_sertifikat }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-slate-500 uppercase tracking-wider text-xs">Tanggal Input</dt>
                                    <dd class="mt-1">{{ $sertifikat->created_at?->format('d M Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-slate-500 uppercase tracking-wider text-xs">Terakhir Diperbarui</dt>
                                    <dd class="mt-1">{{ $sertifikat->updated_at?->format('d M Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-slate-500 uppercase tracking-wider text-xs">ID Sertifikat</dt>
                                    <dd class="mt-1">#{{ $sertifikat->id }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-6 bg-white">
                            <h2 class="text-base font-semibold text-slate-700 mb-3">Aksi Cepat</h2>
                            <div class="flex flex-wrap items-center gap-3">
                                <a href="{{ route('sertifikat.card', $sertifikat) }}" target="_blank" class="inline-flex items-center px-4 py-2 rounded-xl bg-orange-500 text-white hover:bg-orange-600 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Kartu / Cetak Sertifikat
                                </a>
                                <a href="{{ route('sertifikat.edit', ['sertifikat' => $sertifikat->id, 'redirect' => 'detail']) }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-6-9l6 6m0 0L13 5m6 6v2"/>
                                    </svg>
                                    Edit Sertifikat
                                </a>
                                <a href="{{ $sertifikat->foto_sertifikat ? Storage::url($sertifikat->foto_sertifikat) : '#' }}" target="_blank" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition {{ $sertifikat->foto_sertifikat ? '' : 'pointer-events-none opacity-50' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m0 0l-4-4m4 4l4-4M20 12a8 8 0 10-16 0 8 8 0 0016 0z"/>
                                    </svg>
                                    Lihat / Unduh Foto
                                </a>
                                <form action="{{ route('sertifikat.destroy', $sertifikat->id) }}" method="POST" class="inline" data-swal-confirm="Hapus sertifikat ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl bg-rose-500 text-white hover:bg-rose-600 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus Sertifikat
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50">
                            <div class="px-4 py-3 border-b border-slate-200 bg-white flex items-center justify-between">
                                <h2 class="text-sm font-semibold text-slate-700">Lampiran Sertifikat</h2>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $sertifikat->foto_sertifikat ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                                    {{ $sertifikat->foto_sertifikat ? 'Tersedia' : 'Belum diunggah' }}
                                </span>
                            </div>
                            @if($sertifikat->foto_sertifikat)
                                @php
                                    $isPdf = str_ends_with(strtolower($sertifikat->foto_sertifikat), '.pdf');
                                @endphp
                                
                                @if($isPdf)
                                    <div class="bg-white p-4">
                                        <div id="pdf-viewer" class="border border-slate-200 rounded-lg bg-gray-100 flex items-center justify-center" style="height: 500px; overflow: auto;">
                                            <canvas id="pdf-canvas" style="display: block; margin: auto;"></canvas>
                                        </div>
                                        <div class="flex items-center justify-between mt-3">
                                            <p class="text-xs text-slate-500">
                                                Gulir mouse atau geser untuk navigasi halaman PDF
                                            </p>
                                            <p class="text-xs text-slate-500 font-medium">
                                                Halaman: <span id="page-number">1</span>
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="aspect-w-4 aspect-h-3 bg-white relative overflow-hidden">
                                        <img src="{{ Storage::url($sertifikat->foto_sertifikat) }}"
                                             alt="Foto sertifikat"
                                             class="w-full h-full object-cover">
                                        <div class="absolute bottom-3 right-3 bg-white/95 rounded-xl shadow-md p-2 flex flex-col items-center gap-1">
                                            <div id="qr-overlay" class="w-16 h-16"></div>
                                            <p class="text-[10px] text-slate-500 text-center leading-tight">
                                                QR verifikasi sertifikat
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="p-6 text-center text-slate-500 text-sm">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a1 1 0 001 1h3m10 0h3a1 1 0 001-1V7m-4 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m12 0H5"/>
                                    </svg>
                                    Belum ada file yang diunggah. Anda dapat menambahkan file melalui tombol edit.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // PDF Viewer Setup
    (function () {
        const pdfViewer = document.getElementById('pdf-viewer');
        const canvas = document.getElementById('pdf-canvas');
        
        if (!pdfViewer || !canvas) return;
        
        const pdfUrl = '{{ $sertifikat->foto_sertifikat ? Storage::url($sertifikat->foto_sertifikat) : "null" }}';
        if (pdfUrl === 'null' || !pdfUrl.toLowerCase().includes('.pdf')) return;
        
        // Setup PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        
        let pdfDoc = null;
        let currentPage = 1;
        let isRendering = false;
        
        const loadAndRenderPDF = async () => {
            try {
                console.log('Loading PDF from:', pdfUrl);
                // Use getDocument with corsEnabled option
                const pdf = await pdfjsLib.getDocument({
                    url: pdfUrl,
                    withCredentials: false
                }).promise;
                
                pdfDoc = pdf;
                console.log('PDF loaded successfully. Total pages:', pdfDoc.numPages);
                renderPage(currentPage);
            } catch (error) {
                console.error('Error loading PDF:', error);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'p-6 text-center text-red-500 text-sm';
                errorDiv.textContent = 'Gagal memuat PDF. Coba refresh halaman.';
                canvas.parentElement.replaceChild(errorDiv, canvas);
            }
        };
        
        const renderPage = async (pageNum) => {
            if (!pdfDoc || isRendering) return;
            if (pageNum > pdfDoc.numPages || pageNum < 1) return;
            
            isRendering = true;
            
            try {
                const page = await pdfDoc.getPage(pageNum);
                const scale = 1.5;
                const viewport = page.getViewport({ scale: scale });
                
                // Set canvas size
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                
                // Clear canvas
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                
                // Render page
                await page.render({
                    canvasContext: ctx,
                    viewport: viewport
                }).promise;
                
                currentPage = pageNum;
                
                // Update page number display
                const pageNumberEl = document.getElementById('page-number');
                if (pageNumberEl) {
                    pageNumberEl.textContent = pageNum + ' / ' + pdfDoc.numPages;
                }
                
                console.log('Rendered page:', pageNum, 'of', pdfDoc.numPages);
            } catch (error) {
                console.error('Error rendering page:', error);
            } finally {
                isRendering = false;
            }
        };
        
        // Mouse wheel scroll untuk navigate pages
        pdfViewer.addEventListener('wheel', (e) => {
            if (!pdfDoc) return;
            
            if (e.deltaY > 0 && currentPage < pdfDoc.numPages) {
                e.preventDefault();
                renderPage(currentPage + 1);
            } else if (e.deltaY < 0 && currentPage > 1) {
                e.preventDefault();
                renderPage(currentPage - 1);
            }
        });
        
        // Touch events untuk mobile
        let touchStart = 0;
        pdfViewer.addEventListener('touchstart', (e) => {
            touchStart = e.touches[0].clientY;
        });
        
        pdfViewer.addEventListener('touchend', (e) => {
            if (!pdfDoc) return;
            
            const touchEnd = e.changedTouches[0].clientY;
            const diff = touchStart - touchEnd;
            
            if (diff > 50 && currentPage < pdfDoc.numPages) {
                renderPage(currentPage + 1);
            } else if (diff < -50 && currentPage > 1) {
                renderPage(currentPage - 1);
            }
        });
        
        // Load PDF
        loadAndRenderPDF();
    })();
    
    // QR Code Generator
    (function () {
        var el = document.getElementById('qr-overlay');
        if (!el || typeof QRCode === 'undefined') {
            return;
        }

        var url = @json(route('sertifikat.card', $sertifikat));

        new QRCode(el, {
            text: url,
            width: 64,
            height: 64,
            colorDark: "#111827",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    })();
</script>
