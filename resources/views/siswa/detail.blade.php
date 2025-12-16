<x-app-layout>
    <div x-data="{ 
            open: JSON.parse(localStorage.getItem('sidebarOpen') || 'true'),
        }"
        x-init="
            window.addEventListener('sidebar-toggled', () => {
                open = JSON.parse(localStorage.getItem('sidebarOpen'));
            });
        "
        :class="open ? 'ml-64' : ''"
        class="transition-all duration-300">

        <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
            <a href="{{ route('siswa.index') }}" class="inline-flex items-center text-gray-500 hover:text-orange-600 group transition-colors duration-200 text-sm">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke daftar siswa
            </a>

            <!-- siswa card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                    <div class="space-y-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $siswa->nama }}</h1>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1 text-sm text-gray-600">
                            <div>
                                <span class="font-semibold">NISN:</span>
                                <span class="ml-1">{{ $siswa->nisn ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="font-semibold">NIS:</span>
                                <span class="ml-1">{{ $siswa->nis }}</span>
                            </div>
                            <div>
                                <span class="font-semibold">Jenis Kelamin:</span>
                                <span class="ml-1">{{ $siswa->jenis_kelamin ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="font-semibold">Kelas:</span>
                                <span class="ml-1">{{ $siswa->kelas ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="font-semibold">Jurusan:</span>
                                <span class="ml-1">{{ $siswa->jurusan ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="font-semibold">Angkatan:</span>
                                <span class="ml-1">{{ $siswa->angkatan ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="font-semibold">Status:</span>
                                <span class="ml-1 capitalize">{{ $siswa->status ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total sertifikat</p>
                        <p class="text-2xl font-semibold text-orange-600">{{ $siswa->sertifikats->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Sertifikat -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Sertifikat</h2>
                    <a href="{{ route('sertifikat.create', ['siswa_id' => $siswa->id, 'mode' => 'single']) }}" class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg text-sm hover:bg-orange-600 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Sertifikat
                    </a>
                </div>

                @if($siswa->sertifikats->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($siswa->sertifikats as $sertifikat)
                    @php
                        $isPdf = str_ends_with(strtolower($sertifikat->foto_sertifikat), '.pdf');
                    @endphp
                    <div class="group relative bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:border-orange-400 transition-all duration-200 hover:shadow-md">
                        @if($isPdf)
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200 flex items-center justify-center h-40">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm0 2h12v10H4V5z"></path>
                                    </svg>
                                    <p class="text-xs text-gray-600 font-medium">File PDF</p>
                                </div>
                            </div>
                        @else
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="{{ Storage::url($sertifikat->foto_sertifikat) }}"
                                    alt="Sertifikat"
                                    class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="p-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ 
                                        $sertifikat->jenis_sertifikat === 'Kompetensi' ? 'bg-green-100 text-green-800' : 
                                        ($sertifikat->jenis_sertifikat === 'Prestasi' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') 
                                    }} mb-2">
                                {{ $sertifikat->jenis_sertifikat }}
                            </span>
                            <h3 class="font-medium text-gray-900 mb-1">{{ $sertifikat->judul_sertifikat }}</h3>
                            <p class="text-sm text-gray-500">
                                Diraih pada: {{ \Carbon\Carbon::parse($sertifikat->tanggal_diraih)->format('d M Y') }}
                            </p>
                        </div>
                        <!-- Hover -->
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center space-x-2">
                            <!-- Lihat / Preview -->
                            <a href="{{ Storage::url($sertifikat->foto_sertifikat) }}" target="_blank"
                                class="p-2 bg-white rounded-full hover:bg-blue-50 transition-colors duration-200">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            <!-- Edit -->
                            <a href="{{ route('sertifikat.edit', ['sertifikat' => $sertifikat->id, 'redirect' => 'detail']) }}"
                                class="p-2 bg-white rounded-full hover:bg-slate-50 transition-colors duration-200">
                                <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <!-- Hapus  -->
                            <button onclick="confirmDelete('{{ $sertifikat->id }}', '{{ $sertifikat->judul_sertifikat }}')"
                                class="p-2 bg-white rounded-full hover:bg-red-50 transition-colors duration-200">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>

                            <!-- Form tersembunyi untuk delete -->
                            <form id="delete-form-{{ $sertifikat->id }}" action="{{ route('sertifikat.destroy', $sertifikat->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>

                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada sertifikat</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai tambahkan sertifikat untuk siswa ini.</p>
                </div>
                @endif
            </div>

            @if(isset($logs) && $logs->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Perubahan Data</h2>
                    <div class="space-y-3 text-sm text-gray-600">
                        @foreach($logs as $log)
                            <div class="flex items-start justify-between gap-3 border-b border-dashed border-slate-100 pb-2 last:border-0 last:pb-0">
                                <div>
                                    <p class="font-medium text-gray-800 capitalize">
                                        {{ $log->description }}
                                        <span class="text-xs text-gray-400">
                                            @if($log->user)
                                                oleh {{ $log->user->name }}
                                            @else
                                                (sistem)
                                            @endif
                                        </span>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="text-xs text-right text-gray-400">
                                    ID log: {{ $log->id }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>
    </div>

    <script>
        function confirmDelete(sertifikatId, judulSertifikat) {
            Swal.fire({
                title: 'Hapus Sertifikat?',
                text: `Apakah Anda yakin ingin menghapus sertifikat "${judulSertifikat}"? `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2',
                    cancelButton: 'rounded-xl px-6 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${sertifikatId}`).submit();
                    
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang menghapus sertifikat',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    });
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2'
                }
            });
        @endif
    </script>
</x-app-layout>
