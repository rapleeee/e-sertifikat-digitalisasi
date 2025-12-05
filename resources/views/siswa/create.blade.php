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
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Tambah Siswa</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Isi data siswa secara lengkap untuk menghubungkan sertifikat dengan benar.
                    </p>
                </div>
                <a href="{{ route('siswa.index') }}" class="hidden sm:inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke daftar siswa
                </a>
            </div>

            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-6">
                <form action="{{ route('siswa.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                NISN
                            </label>
                            <input
                                type="text"
                                name="nisn"
                                value="{{ old('nisn') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('nisn') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('nisn')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                NIS (Nomor Induk Siswa)
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="nis"
                                value="{{ old('nis') }}"
                                required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('nis') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('nis')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="nama"
                                value="{{ old('nama') }}"
                                required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('nama') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('nama')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Kelamin
                            </label>
                            <input
                                type="text"
                                name="jenis_kelamin"
                                value="{{ old('jenis_kelamin') }}"
                                placeholder="Laki-laki / Perempuan"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('jenis_kelamin') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('jenis_kelamin')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kelas
                            </label>
                            <input
                                type="text"
                                name="kelas"
                                value="{{ old('kelas') }}"
                                placeholder="Contoh: XII RPL 1"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('kelas') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('kelas')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jurusan
                            </label>
                            <input
                                type="text"
                                name="jurusan"
                                value="{{ old('jurusan') }}"
                                placeholder="Contoh: Rekayasa Perangkat Lunak"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('jurusan') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('jurusan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Angkatan
                            </label>
                            <input
                                type="text"
                                name="angkatan"
                                value="{{ old('angkatan') }}"
                                placeholder="Contoh: 2025"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('angkatan') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('angkatan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status
                            </label>
                            <select
                                name="status"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('status') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                                <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="lulus" {{ old('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="alumni" {{ old('status') === 'alumni' ? 'selected' : '' }}>Alumni</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-lg bg-slate-50 border border-slate-200 px-4 py-3 text-xs text-slate-600">
                        <p class="font-semibold text-slate-700 mb-1">Catatan pengisian</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>Pastikan NIS unik dan sesuai dengan data resmi sekolah.</li>
                            <li>Jika tersedia, isi juga NISN, jenis kelamin, kelas, jurusan, angkatan, dan status.</li>
                            <li>Gunakan nama lengkap siswa sesuai dokumen identitas.</li>
                        </ul>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-2">
                        <a href="{{ route('siswa.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 transition">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>
