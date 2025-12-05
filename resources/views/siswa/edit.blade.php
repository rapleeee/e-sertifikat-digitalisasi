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
            <div class="flex items-center gap-3 text-sm text-slate-500">
                <a href="{{ route('siswa.index') }}" class="inline-flex items-center hover:text-orange-600 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke daftar siswa
                </a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Edit Data Siswa</span>
            </div>

            <header>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Data Siswa</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Perbarui informasi siswa untuk menjaga data sertifikat tetap konsisten.
                </p>
            </header>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-4 bg-slate-50">
                    <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 9H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Formulir Edit Siswa
                    </h2>
                </div>

                <form action="{{ route('siswa.update', $siswa) }}" method="POST" class="px-6 py-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-slate-700 flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a1 1 0 001 1h3m10 0h3a1 1 0 001-1V7m-4 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m12 0H5" />
                                </svg>
                                NISN
                            </label>
                            <input
                                type="text"
                                name="nisn"
                                value="{{ old('nisn', $siswa->nisn) }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('nisn') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('nisn')
                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                NIS (Nomor Induk Siswa)
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input
                                type="text"
                                name="nis"
                                value="{{ old('nis', $siswa->nis) }}"
                                required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('nis') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('nis')
                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700 flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Nama Lengkap
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input
                                type="text"
                                name="nama"
                                value="{{ old('nama', $siswa->nama) }}"
                                required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('nama') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('nama')
                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-slate-700 flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7 7 0 1118.879 6.196 7 7 0 015.12 17.804z" />
                                </svg>
                                Jenis Kelamin
                            </label>
                            <input
                                type="text"
                                name="jenis_kelamin"
                                value="{{ old('jenis_kelamin', $siswa->jenis_kelamin) }}"
                                placeholder="Laki-laki / Perempuan"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('jenis_kelamin') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('jenis_kelamin')
                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700 flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                Kelas
                            </label>
                            <input
                                type="text"
                                name="kelas"
                                value="{{ old('kelas', $siswa->kelas) }}"
                                placeholder="Contoh: XII RPL 1"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('kelas') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('kelas')
                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700 flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M9 3h6l2 7H7l2-7z" />
                                </svg>
                                Jurusan
                            </label>
                            <input
                                type="text"
                                name="jurusan"
                                value="{{ old('jurusan', $siswa->jurusan) }}"
                                placeholder="Contoh: Rekayasa Perangkat Lunak"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('jurusan') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('jurusan')
                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700 flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                Angkatan
                            </label>
                            <input
                                type="text"
                                name="angkatan"
                                value="{{ old('angkatan', $siswa->angkatan) }}"
                                placeholder="Contoh: 2025"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('angkatan') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('angkatan')
                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700 flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Status
                            </label>
                            <select
                                name="status"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('status') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                                @php
                                    $currentStatus = old('status', $siswa->status ?? 'aktif');
                                @endphp
                                <option value="aktif" {{ $currentStatus === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="lulus" {{ $currentStatus === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="alumni" {{ $currentStatus === 'alumni' ? 'selected' : '' }}>Alumni</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-lg bg-slate-50 border border-slate-200 p-4 text-xs text-slate-600">
                        <p class="font-semibold text-slate-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tips keamanan data
                        </p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Gunakan nama lengkap sesuai identitas resmi.</li>
                            <li>Pastikan NIS unik agar tidak menimpa data siswa lain.</li>
                            <li>Perubahan akan otomatis memutakhirkan sertifikat milik siswa ini.</li>
                        </ul>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-200">
                        <a href="{{ route('siswa.show', $siswa) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>
