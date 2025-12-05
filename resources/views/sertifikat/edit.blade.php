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
                <a href="{{ route('sertifikat.show', $sertifikat->id) }}" class="inline-flex items-center hover:text-orange-600 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke detail sertifikat
                </a>
            </div>

            <header>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Sertifikat</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Perbarui informasi sertifikat dan lampiran foto jika diperlukan.
                </p>
            </header>

            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Sertifikat</h2>
                            <p class="text-gray-600 text-sm mt-1">Lengkapi form berikut untuk memperbarui data sertifikat.</p>
                        </div>
                        <div class="hidden sm:block">
                            <div class="flex items-center text-xs text-gray-500">
                                <span>Field bertanda <span class="text-red-500">*</span> wajib diisi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('sertifikat.update', $sertifikat->id) }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirect" value="{{ request('redirect', 'detail') }}">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            {{-- Pilih Siswa --}}
                            <div class="group">
                                <label for="siswa_id" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-graduate text-orange-500 mr-2"></i>
                                    Data Siswa
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <select id="siswa_id" name="siswa_id"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all duration-200 appearance-none bg-white @error('siswa_id') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                                            required>
                                        <option value="">Pilih Siswa</option>
                                        @foreach($siswas as $siswa)
                                            <option value="{{ $siswa->id }}" {{ (int) old('siswa_id', $sertifikat->siswa?->id) === $siswa->id ? 'selected' : '' }}>
                                                {{ $siswa->nama }} ({{ $siswa->nis }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                @error('siswa_id')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Jenis Sertifikat --}}
                            <div class="group">
                                <label for="jenis_sertifikat" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tag text-orange-500 mr-2"></i>
                                    Jenis Sertifikat 
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <select id="jenis_sertifikat" name="jenis_sertifikat" 
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all duration-200 appearance-none bg-white @error('jenis_sertifikat') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                                            required>
                                        <option value="">Pilih Jenis Sertifikat</option>
                                        @foreach(['Kompetensi', 'BNSP', 'Prestasi', 'Internasional'] as $jenis)
                                            <option value="{{ $jenis }}" {{ old('jenis_sertifikat', $sertifikat->jenis_sertifikat) === $jenis ? 'selected' : '' }}>
                                                {{ $jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                @error('jenis_sertifikat')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-6">
                            {{-- Judul Sertifikat --}}
                            <div class="group">
                                <label for="judul_sertifikat" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-certificate text-orange-500 mr-2"></i>
                                    Judul Sertifikat
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" id="judul_sertifikat" name="judul_sertifikat"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all duration-200 @error('judul_sertifikat') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                                       value="{{ old('judul_sertifikat', $sertifikat->judul_sertifikat) }}"
                                       placeholder="Misal: Juara 1 Lomba UI/UX Nasional">
                                @error('judul_sertifikat')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Penyelenggara --}}
                            <div class="group">
                                <label for="penyelenggara" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-building text-orange-500 mr-2"></i>
                                    Penyelenggara
                                </label>
                                <input type="text" id="penyelenggara" name="penyelenggara"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all duration-200 @error('penyelenggara') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                                       value="{{ old('penyelenggara', $sertifikat->penyelenggara) }}"
                                       placeholder="Nama lembaga / penyelenggara">
                                @error('penyelenggara')
                                    <p class="text-red-500 text-xs mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Tanggal Diraih --}}
                        <div class="group">
                            <label for="tanggal_diraih" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-orange-500 mr-2"></i>
                                Tanggal Diraih
                            </label>
                            <input
                                type="date"
                                id="tanggal_diraih"
                                name="tanggal_diraih"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all duration-200 @error('tanggal_diraih') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                                value="{{ old('tanggal_diraih', $sertifikat->tanggal_diraih ? \Carbon\Carbon::parse($sertifikat->tanggal_diraih)->format('Y-m-d') : '') }}"
                            >
                            @error('tanggal_diraih')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="group lg:col-span-2">
                            <label for="deskripsi" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left text-orange-500 mr-2"></i>
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all duration-200 @error('deskripsi') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                                      placeholder="Tambahkan keterangan singkat mengenai sertifikat ini">{{ old('deskripsi', $sertifikat->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                            <p class="text-gray-600 text-xs mt-1">
                                Deskripsi bersifat opsional, namun dapat membantu penjelasan saat melihat riwayat sertifikat.
                            </p>
                        </div>
                    </div>

                    {{-- Foto Sertifikat --}}
                    <div class="space-y-4">
                        <label class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-image text-orange-500 mr-2"></i>
                            Foto Sertifikat 
                            <span class="text-gray-500 ml-2 text-xs font-normal">(Opsional)</span>
                        </label>
                        
                        {{-- Display Foto --}}
                        @if($sertifikat->foto_sertifikat && Storage::disk('public')->exists($sertifikat->foto_sertifikat))
                            <div class="mb-4 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                <div class="text-center">
                                    <p class="text-sm font-medium text-gray-600 mb-3 flex items-center justify-center">
                                        <i class="fas fa-image mr-2"></i>Foto Saat Ini
                                    </p>
                                    <div class="inline-block p-2 bg-white rounded-lg shadow-sm">
                                        <img src="{{ Storage::url($sertifikat->foto_sertifikat) }}" 
                                                alt="Current certificate photo" 
                                                class="h-40 w-auto object-cover rounded-lg border border-gray-200 shadow-sm">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="relative">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-400 transition-colors duration-200 @error('foto_sertifikat') border-red-400 @enderror">
                                <input type="file" 
                                        id="foto_sertifikat" 
                                        name="foto_sertifikat" 
                                        accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                <div class="space-y-3">
                                    <div class="mx-auto w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-cloud-upload-alt text-orange-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium text-sm">Klik untuk unggah foto baru</p>
                                        <p class="text-gray-500 text-xs mt-1">atau drag & drop file ke sini</p>
                                    </div>
                                    <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-file-image mr-1"></i>JPG, PNG
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-weight-hanging mr-1"></i>Max 2MB
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @error('foto_sertifikat')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror

                        @if($sertifikat->foto_sertifikat)
                            <label class="mt-2 inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-xs text-gray-600 shadow-sm hover:bg-gray-200 transition">
                                <input type="checkbox" name="hapus_foto" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-400 mr-2">
                                Hapus foto saat ini
                            </label>
                            @error('hapus_foto')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500">
                            Perubahan akan langsung mempengaruhi tampilan sertifikat pada halaman siswa dan laporan terkait.
                        </p>
                        <div class="flex gap-3">
                            <a href="{{ route('sertifikat.show', $sertifikat->id) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>
