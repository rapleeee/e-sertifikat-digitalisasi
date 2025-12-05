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
        <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-10 max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                    
                <!-- Header -->
                <div class="p-6 sm:px-8 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">
                        Pratinjau Data Siswa yang Diimpor
                    </h2>
                    <p class="text-sm text-gray-500">
                        Periksa kembali data siswa (NISN, NIS, Nama, Jenis Kelamin, Kelas, dan Jurusan) sebelum disimpan ke sistem.
                    </p>
                </div>

                <div class="p-6 sm:p-8">
                    <!-- Success Alert -->
                    @if (session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-4 text-sm" role="alert">
                            <span class="font-semibold">
                                {{ session('success') }}
                            </span>
                        </div>
                    @endif

                    <!-- Error Alert -->
                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 text-sm" role="alert">
                            <span class="font-semibold">
                                {{ session('error') }}
                            </span>
                        </div>
                    @endif

                    <!-- Table Preview -->
                    @if (session('imported_sertifikats'))
                        @php
                            $failures = collect(session('import_failures', []));
                            $failureByRow = $failures
                                ->groupBy('row')
                                ->map(function ($items) {
                                    return collect($items)->pluck('errors')->flatten()->all();
                                });
                        @endphp
                        <form action="{{ route('sertifikat.import.confirm') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="overflow-x-auto mb-6 border border-gray-200 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                NISN
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                NIS
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                Nama Siswa
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                Jenis Kelamin
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                Kelas
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                Jurusan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach (session('imported_sertifikats') as $index => $sertifikat)
                                            @php
                                                $rowNumber = $index + 2; // karena heading di baris 1
                                                $rowErrors = $failureByRow->get($rowNumber, []);
                                            @endphp
                                            <tr class="transition {{ $rowErrors ? 'bg-red-50' : 'hover:bg-slate-50' }}">
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    <x-text-input 
                                                        type="text" 
                                                        name="sertifikats[{{ $index }}][nisn]" 
                                                        value="{{ old('sertifikats.' . $index . '.nisn', $sertifikat['nisn'] ?? '') }}" 
                                                        class="w-full"
                                                    />
                                                </td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    <x-text-input 
                                                        type="text" 
                                                        name="sertifikats[{{ $index }}][nis]" 
                                                        value="{{ old('sertifikats.' . $index . '.nis', $sertifikat['nis']) }}" 
                                                        class="w-full"
                                                    />
                                                </td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    <x-text-input 
                                                        type="text" 
                                                        name="sertifikats[{{ $index }}][nama]" 
                                                        value="{{ old('sertifikats.' . $index . '.nama', $sertifikat['nama']) }}" 
                                                        class="w-full"
                                                    />
                                                </td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    <x-text-input 
                                                        type="text" 
                                                        name="sertifikats[{{ $index }}][jenis_kelamin]" 
                                                        value="{{ old('sertifikats.' . $index . '.jenis_kelamin', $sertifikat['jenis_kelamin'] ?? '') }}" 
                                                        class="w-full"
                                                    />
                                                </td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    <x-text-input 
                                                        type="text" 
                                                        name="sertifikats[{{ $index }}][kelas]" 
                                                        value="{{ old('sertifikats.' . $index . '.kelas', $sertifikat['kelas'] ?? '') }}" 
                                                        class="w-full"
                                                    />
                                                </td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    <x-text-input 
                                                        type="text" 
                                                        name="sertifikats[{{ $index }}][jurusan]" 
                                                        value="{{ old('sertifikats.' . $index . '.jurusan', $sertifikat['jurusan'] ?? '') }}" 
                                                        class="w-full"
                                                    />
                                                </td>
                                            </tr>
                                            @if (!empty($rowErrors))
                                                <tr class="bg-red-50/60">
                                                    <td colspan="6" class="px-6 pb-3 text-xs text-red-600">
                                                        <ul class="list-disc list-inside space-y-0.5">
                                                            @foreach ($rowErrors as $error)
                                                                <li>Baris {{ $rowNumber }}: {{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end gap-3">
                                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-orange-500 border border-transparent rounded-lg font-semibold text-xs text-white tracking-wide hover:bg-orange-600 focus:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 transition">
                                    Konfirmasi Impor
                                </button>
                                <a href="{{ route('sertifikat.import.form') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white tracking-wide hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                                    Batal
                                </a>
                            </div>
                        </form>
                    @else
                        <!-- Empty State -->
                        <p class="text-center text-gray-600 text-sm py-6">
                            Tidak ada data sertifikat yang diimpor untuk pratinjau.
                        </p>
                        <div class="flex justify-center mt-4">
                            <a href="{{ route('sertifikat.import.form') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white tracking-wide hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                                Kembali ke halaman impor
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
