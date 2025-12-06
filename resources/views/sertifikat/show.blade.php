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
                            @else
                                <div class="p-6 text-center text-slate-500 text-sm">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a1 1 0 001 1h3m10 0h3a1 1 0 001-1V7m-4 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m12 0H5"/>
                                    </svg>
                                    Belum ada foto yang diunggah. Anda dapat menambahkan foto melalui tombol edit.
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
<script>
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
