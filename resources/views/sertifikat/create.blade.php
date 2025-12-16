<x-app-layout>
    <div 
        x-data="certificatePage({
            sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') || 'true'),
            defaultTab: '{{ old("mode", request("mode", "single")) }}',
            initialSingle: @json(old('siswa_id', $prefilledSiswa->id ?? null)),
            initialBulk: @json(old('siswa_ids', [])),
            initialJenis: '{{ old("jenis_sertifikat") }}',
            initialJudul: '{{ old("judul_sertifikat") }}',
            initialTanggal: '{{ old("tanggal_diraih") }}',
        })"
        x-init="
            window.addEventListener('sidebar-toggled', () => {
                state.open = JSON.parse(localStorage.getItem('sidebarOpen'));
            });
        "
        x-bind:class="state.open ? 'ml-64' : ''"
        class="transition-all duration-300"
    >
        <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-10 max-w-7xl mx-auto space-y-6">
            @if(session('error'))
                <div class="rounded-xl bg-red-50 border border-red-200 p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-9 h-9 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-red-800 text-sm">Terjadi Kesalahan</h4>
                            <p class="text-red-700 text-xs sm:text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-9 h-9 bg-amber-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 17a1 1 0 110 2 1 1 0 010-2zm0-12a9 9 0 110 18 9 9 0 010-18z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-amber-900 text-sm">Form belum lengkap</h4>
                            <p class="text-amber-800 text-xs sm:text-sm mb-1">Periksa kembali isian yang diberi tanda merah di bawah.</p>
                            <ul class="mt-1 text-[11px] sm:text-xs text-amber-800 list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Tambah Sertifikat</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Buat sertifikat untuk satu atau beberapa siswa dalam satu halaman.
                    </p>
                    <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] text-slate-600 space-y-1">
                        <p class="font-semibold text-xs text-slate-700">Panduan singkat:</p>
                        <p>• Gunakan <span class="font-semibold">Per Siswa</span> jika isi data sertifikat berbeda-beda per siswa (judul, tanggal, dll.).</p>
                        <p>• Gunakan <span class="font-semibold">Multi Siswa</span> jika satu sertifikat yang sama (judul & tanggal) dibagikan ke banyak siswa.</p>
                        <p>• Satu siswa boleh memiliki banyak sertifikat, dan judul sertifikat boleh sama dengan siswa lain.</p>
                    </div>
                </div>
                <div class="flex flex-col items-end text-sm text-gray-500">
                    <span>Total siswa terdaftar: <span class="font-semibold text-gray-800">{{ number_format($siswas->count()) }}</span></span>
                </div>
            </header>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <nav class="inline-flex rounded-xl bg-white border border-slate-200 overflow-hidden text-sm">
                            <button
                                type="button"
                                class="px-4 py-2 font-medium transition-colors"
                                :class="state.tab === 'single' ? 'bg-orange-500 text-white' : 'text-slate-600 hover:bg-slate-50'"
                                @click="state.tab = 'single'"
                            >
                                Tambah Per Siswa
                            </button>
                            <button
                                type="button"
                                class="px-4 py-2 font-medium transition-colors border-l border-slate-200"
                                :class="state.tab === 'bulk' ? 'bg-orange-500 text-white' : 'text-slate-600 hover:bg-slate-50'"
                                @click="state.tab = 'bulk'"
                            >
                                Tambah Multi Siswa
                            </button>
                        </nav>
                        <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Gunakan mode <span x-text="state.tab === 'single' ? 'per siswa untuk input detail spesifik.' : 'multi siswa untuk mendistribusikan sertifikat yang sama.'"></span>
                        </p>
                    </div>
                </div>

                <div class="p-8 space-y-10">
                    <!-- Per Siswa -->
                    <section x-show="state.tab === 'single'" x-cloak>
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Form Sertifikat Individual</h2>
                        <p class="text-xs text-slate-500 mb-4">
                            Mode ini cocok untuk mengisi sertifikat yang isi detailnya spesifik untuk satu siswa (misal judul, tanggal, atau jenis berbeda).
                        </p>
                        <form action="{{ route('sertifikat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="mode" value="single">
                            <input type="hidden" name="redirect" value="{{ request('redirect', 'detail') }}">

                            <div class="space-y-6">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5">
                                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Langkah 1 · Data Siswa</h3>
                                    <p class="text-sm text-slate-500 mt-1">Cari dan pilih siswa yang akan menerima sertifikat ini.</p>

                                    <div class="mt-4 space-y-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                                Cari nama / NIS siswa
                                            </label>
                                            <div class="relative">
                                                <input
                                                    id="single-search-input"
                                                    type="text"
                                                    placeholder="Ketik nama lengkap atau NIS..."
                                                    class="w-full rounded-xl border border-slate-300 pl-9 pr-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                                >
                                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10A7 7 0 103 10a7 7 0 0014 0z" />
                                                </svg>
                                            </div>
                                        </div>
<div class=" gap-4 text-xs">
                                        <div class="flex flex-col md:flex-row md:items-center gap-3">
                                            <div class="md:w-1/2">
                                                <label class="block text-xs font-semibold text-slate-600 mb-1">Filter Kelas</label>
                                                <select
                                                    id="single-filter-kelas"
                                                    class="w-full rounded-lg border border-slate-300 px-2 py-2 text-xs focus:border-orange-500 focus:ring-1 focus:ring-orange-200"
                                                >
                                                    <option value="">Semua kelas</option>
                                                    @foreach($kelasOptions as $kelas)
                                                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="md:w-1/2">
                                                <label class="block text-xs font-semibold text-slate-600 mb-1">Filter Jurusan</label>
                                                <select
                                                    id="single-filter-jurusan"
                                                    class="w-full rounded-lg border border-slate-300 px-2 py-2 text-xs focus:border-orange-500 focus:ring-1 focus:ring-orange-200"
                                                >
                                                    <option value="">Semua jurusan</option>
                                                    @foreach($jurusanOptions as $jurusan)
                                                        <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
</div>
                                       

                                        <div class="mt-1 max-h-72 overflow-y-auto rounded-2xl border border-slate-200 bg-white">
                                            @foreach($siswas as $siswa)
                                                <label
                                                    class="flex items-start gap-3 px-4 py-2.5 text-xs hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-0"
                                                    data-single-item="siswa"
                                                    data-kelas="{{ $siswa->kelas ?? '' }}"
                                                    data-jurusan="{{ $siswa->jurusan ?? '' }}"
                                                    data-nama="{{ $siswa->nama }}"
                                                    data-nis="{{ $siswa->nis }}"
                                                >
                                                    <input
                                                        type="radio"
                                                        name="siswa_id"
                                                        value="{{ $siswa->id }}"
                                                        x-model="state.singleSelected"
                                                        @checked((int) old('siswa_id', $prefilledSiswa->id ?? null) === $siswa->id)
                                                        class="mt-0.5 rounded-full border-slate-300 text-orange-500 focus:ring-orange-400"
                                                        required
                                                    >
                                                    <div class="flex-1">
                                                        <p class="font-semibold text-slate-800 text-sm">{{ $siswa->nama }}</p>
                                                        <p class="text-[11px] text-slate-500">
                                                            NIS: {{ $siswa->nis }}
                                                            @if($siswa->kelas || $siswa->jurusan)
                                                                · {{ $siswa->kelas ?? '-' }}{{ $siswa->jurusan ? ' · '.$siswa->jurusan : '' }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                        @error('siswa_id')
                                            <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-slate-200 p-5 bg-white">
                                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Langkah 2 · Detail Sertifikat</h3>
                                    <p class="text-sm text-slate-500 mt-1">Lengkapi informasi yang akan tercantum pada sertifikat.</p>
                                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="flex items-center text-sm font-semibold text-slate-700 mb-2">
                                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Jenis Sertifikat
                                                <span class="text-red-500 ml-1">*</span>
                                            </label>
                                            <select
                                                name="jenis_sertifikat"
                                                x-model="state.jenis"
                                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 bg-white px-4 py-3 shadow-sm"
                                                required
                                            >
                                                <option value="" disabled {{ old('jenis_sertifikat') ? '' : 'selected' }}>Pilih jenis sertifikat</option>
                                                @foreach(['Kompetensi', 'BNSP', 'Prestasi', 'Internasional'] as $jenis)
                                                    <option value="{{ $jenis }}" @selected(old('jenis_sertifikat') === $jenis)>{{ $jenis }}</option>
                                                @endforeach
                                            </select>
                                            @error('jenis_sertifikat')
                                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="flex items-center text-sm font-semibold text-slate-700 mb-2">
                                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-9 4h4"/>
                                                </svg>
                                                Tanggal Diraih
                                                <span class="text-red-500 ml-1">*</span>
                                            </label>
                                            <input
                                                type="date"
                                                name="tanggal_diraih"
                                                x-model="state.tanggal"
                                                value="{{ old('tanggal_diraih') }}"
                                                max="{{ now()->toDateString() }}"
                                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 bg-white px-4 py-3 shadow-sm"
                                                required
                                            >
                                            @error('tanggal_diraih')
                                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="flex items-center text-sm font-semibold text-slate-700 mb-2">
                                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.79-3 4s1.343 4 3 4 3-1.79 3-4-1.343-4-3-4z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Judul Sertifikat
                                                <span class="text-red-500 ml-1">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                name="judul_sertifikat"
                                                x-model="state.judul"
                                                value="{{ old('judul_sertifikat') }}"
                                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 bg-white px-4 py-3 shadow-sm"
                                                placeholder="Misal: Juara 1 Lomba UI/UX Nasional"
                                                required
                                            >
                                            @error('judul_sertifikat')
                                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-indigo-400 transition">
                                    <input
                                        type="file"
                                        name="foto_sertifikat"
                                        accept="image/jpeg,image/jpg,image/png,application/pdf"
                                        x-ref="singleFileInput"
                                        class="absolute inset-0 opacity-0 cursor-pointer"
                                        @change="handleSingleFile($event)"
                                    >
                                    <div class="space-y-2">
                                        <svg class="w-10 h-10 mx-auto text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m0-2 2-2m-6-7h.01M5 7h.01M12 7h.01M19 7h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm font-semibold text-slate-600">Seret & lepas atau klik untuk pilih</p>
                                        <p class="text-xs text-slate-400">Format JPG/PNG/PDF, maksimal 10MB</p>
                                    </div>
                                </div>
                                <div class="mt-3" x-show="singlePreviewUrl" x-cloak>
                                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center gap-4">
                                        <div class="relative">
                                            <img :src="singlePreviewUrl" alt="Preview Sertifikat" class="h-28 w-44 object-cover rounded-xl border border-slate-200 shadow-sm">
                                        </div>
                                        <div class="flex-1 space-y-1 text-left">
                                            <p class="text-sm font-semibold text-slate-700" x-text="singleFileName"></p>
                                            <p class="text-xs text-slate-500">Ukuran berkas: <span x-text="singleFileSize"></span></p>
                                            <div class="flex flex-wrap gap-2 pt-1">
                                                <button type="button" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition" @click="previewSingleImage">Lihat Lebih Besar</button>
                                                <button type="button" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-rose-500 text-white hover:bg-rose-600 transition" @click="clearSingleFile">Ganti Foto</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    @error('foto_sertifikat')
                                        <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-slate-200 pt-6"
                                x-show="state.tab === 'single'"
                                x-cloak
                            >
                                <p class="text-xs text-slate-500">
                                    Setelah disimpan, Anda akan kembali ke halaman detail siswa.
                                </p>
                                <div class="flex gap-3">
                                    <a href="{{ url()->previous() }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium">
                                        Batal
                                    </a>
                                    <button
                                        type="submit"
                                        :disabled="!canSubmitSingle()"
                                        :class="canSubmitSingle()
                                            ? 'px-5 py-2.5 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600'
                                            : 'px-5 py-2.5 rounded-lg bg-orange-300 text-white text-sm font-semibold shadow-sm cursor-not-allowed opacity-70'"
                                    >
                                        Simpan Sertifikat
                                    </button>
                                </div>
                            </div>
                        </form>
                    </section>

                    <!-- Multi Siswa -->
                    <section x-show="state.tab === 'bulk'" x-cloak x-data="{ total: state.bulkSelected }">
                        <h2 class="text-xl font-semibold text-slate-800 mb-3">Form Sertifikat Multi Siswa</h2>
                        <p class="text-xs text-slate-500 mb-4">
                            Mode ini membuat <span class="font-semibold">sertifikat dengan detail yang sama</span> (jenis, judul, tanggal)
                            ke beberapa siswa sekaligus. Cocok untuk satu event yang diikuti banyak siswa.
                        </p>
                        <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[11px] text-slate-600">
                            <p class="font-semibold text-xs text-slate-700 mb-1">Panduan penggunaan:</p>
                            <p>• Gunakan <span class="font-semibold">Multi Siswa</span> ketika teks sertifikat sama untuk banyak siswa.</p>
                            <p>• Jika ingin upload banyak file sesuai NIS (satu file per siswa), gunakan menu
                                <a href="{{ route('sertifikat.upload') }}" class="font-semibold text-orange-600 hover:underline">Upload Foto Sertifikat</a>.
                            </p>
                        </div>

                        <form action="{{ route('sertifikat.bulk-store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="mode" value="bulk">

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Pilih siswa -->
                                <div class="lg:col-span-2">
                                    <label class="flex items-center text-sm font-semibold text-slate-700 mb-2">
                                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Pilih Beberapa Siswa
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <p class="text-xs text-slate-500">
                                                Centang siswa yang akan menerima sertifikat. Gunakan filter kelas / jurusan untuk mempersempit daftar.
                                            </p>
                                            <span class="text-xs font-semibold text-indigo-500">
                                                Dipilih: <span x-text="state.bulkSelected.length"></span> siswa
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                            <div>
                                                <label class="block mb-1 text-slate-600">Filter Kelas</label>
                                                <select
                                                    id="bulk-filter-kelas"
                                                    class="w-full rounded-lg border border-slate-300 px-2 py-1.5 focus:border-orange-500 focus:ring-1 focus:ring-orange-200 text-xs"
                                                >
                                                    <option value="">Semua kelas</option>
                                                    @foreach($kelasOptions as $kelas)
                                                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-slate-600">Filter Jurusan</label>
                                                <select
                                                    id="bulk-filter-jurusan"
                                                    class="w-full rounded-lg border border-slate-300 px-2 py-1.5 focus:border-orange-500 focus:ring-1 focus:ring-orange-200 text-xs"
                                                >
                                                    <option value="">Semua jurusan</option>
                                                    @foreach($jurusanOptions as $jurusan)
                                                        <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-2 text-xs text-slate-500">
                                            <label class="inline-flex items-center gap-1">
                                                <input
                                                    type="checkbox"
                                                    id="bulk-select-all"
                                                    class="rounded border-slate-300 text-orange-500 focus:ring-orange-400"
                                                    @change="toggleBulkSelectAll($event)"
                                                >
                                                <span>Pilih semua siswa yang tampil</span>
                                            </label>
                                        </div>

                                        <div class="mt-3 max-h-80 overflow-y-auto bg-white border border-slate-200 rounded-xl divide-y divide-slate-100">
                                            @foreach($siswas as $siswa)
                                                <label
                                                    class="flex items-start gap-3 px-3 py-2.5 text-xs hover:bg-slate-50 cursor-pointer"
                                                    data-bulk-item="siswa"
                                                    data-kelas="{{ $siswa->kelas ?? '' }}"
                                                    data-jurusan="{{ $siswa->jurusan ?? '' }}"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        name="siswa_ids[]"
                                                        value="{{ $siswa->id }}"
                                                        x-model="state.bulkSelected"
                                                        class="mt-0.5 rounded border-slate-300 text-orange-500 focus:ring-orange-400"
                                                    >
                                                    <div class="flex-1">
                                                        <p class="font-semibold text-slate-800 text-sm">{{ $siswa->nama }}</p>
                                                        <p class="text-[11px] text-slate-500">
                                                            NIS: {{ $siswa->nis }}
                                                            @if($siswa->kelas || $siswa->jurusan)
                                                                · {{ $siswa->kelas ?? '-' }}{{ $siswa->jurusan ? ' · '.$siswa->jurusan : '' }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                        @error('siswa_ids')
                                            <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Data sertifikat + lampiran -->
                                <div class="space-y-5">
                                    <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                                Jenis Sertifikat
                                                <span class="text-red-500 ml-1">*</span>
                                            </label>
                                            <select
                                                name="jenis_sertifikat"
                                                x-model="state.jenis"
                                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 bg-white px-4 py-3 shadow-sm"
                                                required
                                            >
                                            <option value="" disabled {{ old('jenis_sertifikat') ? '' : 'selected' }}>Pilih jenis</option>
                                            @foreach(['Kompetensi', 'BNSP', 'Prestasi', 'Internasional'] as $jenis)
                                                <option value="{{ $jenis }}" @selected(old('jenis_sertifikat') === $jenis)>{{ $jenis }}</option>
                                            @endforeach
                                        </select>
                                        @error('jenis_sertifikat')
                                            <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                                Judul Sertifikat
                                                <span class="text-red-500 ml-1">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                name="judul_sertifikat"
                                                x-model="state.judul"
                                                value="{{ old('judul_sertifikat') }}"
                                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 bg-white px-4 py-3 shadow-sm"
                                                placeholder="Misal: Sertifikat Pelatihan AWS Academy"
                                                required
                                            >
                                        @error('judul_sertifikat')
                                            <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                                Tanggal Diraih
                                                <span class="text-red-500 ml-1">*</span>
                                            </label>
                                            <input
                                                type="date"
                                                name="tanggal_diraih"
                                                x-model="state.tanggal"
                                                value="{{ old('tanggal_diraih') }}"
                                                max="{{ now()->toDateString() }}"
                                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 bg-white px-4 py-3 shadow-sm"
                                                required
                                            >
                                        @error('tanggal_diraih')
                                            <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                   
                                </div>
                            </div>

                             <div class="space-y-3">
                                        <label class="block text-sm font-medium text-slate-700 mb-1">
                                            File Sertifikat (opsional)
                                        </label>
                                        <p class="text-xs text-slate-500 mb-1">
                                            Jika diisi, <span class="font-semibold">satu file sertifikat yang sama</span> akan dipakai untuk semua siswa terpilih.
                                            Untuk file berbeda per siswa, gunakan halaman
                                            <a href="{{ route('sertifikat.upload') }}" class="font-semibold text-orange-600 hover:underline">Upload Foto Sertifikat</a>.
                                        </p>
                                        <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-orange-400 transition">
                                            <input
                                                type="file"
                                                name="foto_sertifikat"
                                                accept="image/jpeg,image/jpg,image/png,application/pdf"
                                                class="absolute inset-0 opacity-0 cursor-pointer"
                                            >
                                            <div class="space-y-2">
                                                <svg class="w-8 h-8 mx-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m0-2 2-2m-6-7h.01M5 7h.01M12 7h.01M19 7h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="text-xs text-slate-600">Klik untuk pilih file sertifikat (JPG/PNG/PDF, max 10MB)</p>
                                            </div>
                                        </div>
                                        @error('foto_sertifikat')
                                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-xs text-indigo-700">
                                        <p class="font-semibold mb-2">Ringkasan:</p>
                                        <ul class="space-y-1 list-disc list-inside">
                                            <li>Satu set data sertifikat akan dibuat untuk setiap siswa yang dipilih.</li>
                                            <li>File (jika ada) akan digunakan bersama untuk semua siswa tersebut.</li>
                                            <li>Untuk upload banyak file berdasarkan NIS, gunakan menu Upload Foto Sertifikat.</li>
                                        </ul>
                                    </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-slate-200 pt-6">
                                <p class="text-sm text-slate-500">
                                    Total siswa dipilih:
                                    <span class="font-semibold text-orange-600" x-text="state.bulkSelected.length"></span>
                                </p>
                                <div class="flex gap-3">
                                    <a href="{{ url()->previous() }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium">
                                        Batal
                                    </a>
                                    <button
                                        type="submit"
                                        :disabled="!canSubmitBulk()"
                                        :class="canSubmitBulk()
                                            ? 'px-5 py-2.5 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600'
                                            : 'px-5 py-2.5 rounded-lg bg-orange-300 text-white text-sm font-semibold shadow-sm cursor-not-allowed opacity-70'"
                                    >
                                        Tambahkan Sertifikat
                                    </button>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <script>
        function certificatePage(config) {
            return {
                state: {
                    open: config.sidebarOpen ?? true,
                    tab: (['single', 'bulk'].includes(config.defaultTab) ? config.defaultTab : 'single'),
                    singleSelected: config.initialSingle || null,
                    bulkSelected: config.initialBulk || [],
                    jenis: config.initialJenis || '',
                    judul: config.initialJudul || '',
                    tanggal: config.initialTanggal || '',
                },
                singlePreviewUrl: null,
                singleFileName: '',
                singleFileSize: '',
                singleMaxSize: 2 * 1024 * 1024,
                canSubmitSingle() {
                    return !!(this.state.singleSelected && this.state.jenis && this.state.judul && this.state.tanggal);
                },
                canSubmitBulk() {
                    const selectedCount = (this.state.bulkSelected || []).length;
                    return !!(selectedCount > 0 && this.state.jenis && this.state.judul && this.state.tanggal);
                },
                handleSingleFile(event) {
                    const input = event.target;
                    const file = input.files && input.files[0] ? input.files[0] : null;

                    if (!file) {
                        this.clearSingleFile();
                        return;
                    }

                    if (file.size > this.singleMaxSize) {
                        Swal.fire({
                            title: 'Ukuran File Terlalu Besar',
                            text: `${file.name} melebihi batas 2MB dan tidak dapat diunggah.`,
                            icon: 'error',
                            confirmButtonText: 'Mengerti',
                            customClass: { popup: 'rounded-2xl' }
                        });
                        this.clearSingleFile();
                        return;
                    }

                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            title: 'Format Tidak Didukung',
                            text: 'Gunakan format gambar JPG, PNG, atau GIF.',
                            icon: 'error',
                            confirmButtonText: 'Mengerti',
                            customClass: { popup: 'rounded-2xl' }
                        });
                        this.clearSingleFile();
                        return;
                    }

                    if (this.singlePreviewUrl) {
                        URL.revokeObjectURL(this.singlePreviewUrl);
                    }

                    this.singlePreviewUrl = URL.createObjectURL(file);
                    this.singleFileName = file.name;
                    this.singleFileSize = this.formatBytes(file.size);
                },
                clearSingleFile() {
                    if (this.singlePreviewUrl) {
                        URL.revokeObjectURL(this.singlePreviewUrl);
                    }

                    this.singlePreviewUrl = null;
                    this.singleFileName = '';
                    this.singleFileSize = '';

                    if (this.$refs.singleFileInput) {
                        this.$refs.singleFileInput.value = '';
                    }
                },
                previewSingleImage() {
                    if (!this.singlePreviewUrl) {
                        return;
                    }

                    Swal.fire({
                        title: this.singleFileName || 'Preview Sertifikat',
                        imageUrl: this.singlePreviewUrl,
                        imageAlt: this.singleFileName || 'Preview Sertifikat',
                        confirmButtonText: 'Tutup',
                        customClass: { popup: 'rounded-2xl' }
                    });
                },
                formatBytes(bytes) {
                    if (!bytes) {
                        return '0 B';
                    }

                    const units = ['B', 'KB', 'MB'];
                    const exponent = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
                    const value = bytes / Math.pow(1024, exponent);
                    return `${value.toFixed(exponent === 0 ? 0 : 2)} ${units[exponent]}`;
                },
                toggleBulkSelectAll(event) {
                    const checked = event.target.checked;
                    const items = document.querySelectorAll('[data-bulk-item="siswa"]');
                    const selected = new Set((this.state.bulkSelected || []).map(String));

                    items.forEach((row) => {
                        const style = row.style.display;
                        const isVisible = style === '' || style === 'block' || style === 'flex' || style === 'grid';
                        if (!isVisible) {
                            return;
                        }

                        const checkbox = row.querySelector('input[type="checkbox"][name="siswa_ids[]"]');
                        if (!checkbox) {
                            return;
                        }

                        const id = String(checkbox.value);

                        if (checked) {
                            checkbox.checked = true;
                            selected.add(id);
                        } else {
                            checkbox.checked = false;
                            selected.delete(id);
                        }
                    });

                    this.state.bulkSelected = Array.from(selected);
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            const kelasFilter = document.getElementById('bulk-filter-kelas');
            const jurusanFilter = document.getElementById('bulk-filter-jurusan');
            const singleSearch = document.getElementById('single-search-input');
            const singleKelas = document.getElementById('single-filter-kelas');
            const singleJurusan = document.getElementById('single-filter-jurusan');

            function applyBulkFilter() {
                const kelas = kelasFilter?.value || '';
                const jurusan = jurusanFilter?.value || '';
                const items = document.querySelectorAll('[data-bulk-item="siswa"]');

                items.forEach((el) => {
                    const elKelas = el.getAttribute('data-kelas') || '';
                    const elJurusan = el.getAttribute('data-jurusan') || '';
                    const matchKelas = !kelas || elKelas === kelas;
                    const matchJurusan = !jurusan || elJurusan === jurusan;
                    el.style.display = matchKelas && matchJurusan ? '' : 'none';
                });
            }

            kelasFilter?.addEventListener('change', applyBulkFilter);
            jurusanFilter?.addEventListener('change', applyBulkFilter);

            function applySingleFilter() {
                const term = (singleSearch?.value || '').toLowerCase();
                const kelas = singleKelas?.value || '';
                const jurusan = singleJurusan?.value || '';

                const rows = document.querySelectorAll('[data-single-item="siswa"]');

                rows.forEach((row) => {
                    const rowKelas = row.getAttribute('data-kelas') || '';
                    const rowJurusan = row.getAttribute('data-jurusan') || '';
                    const rowNama = (row.getAttribute('data-nama') || '').toLowerCase();
                    const rowNis = (row.getAttribute('data-nis') || '').toLowerCase();

                    const matchKelas = !kelas || rowKelas === kelas;
                    const matchJurusan = !jurusan || rowJurusan === jurusan;
                    const matchTerm = !term || rowNama.includes(term) || rowNis.includes(term);

                    row.style.display = matchKelas && matchJurusan && matchTerm ? '' : 'none';
                });
            }

            singleSearch?.addEventListener('input', applySingleFilter);
            singleKelas?.addEventListener('change', applySingleFilter);
            singleJurusan?.addEventListener('change', applySingleFilter);
        });
    </script>
</x-app-layout>
