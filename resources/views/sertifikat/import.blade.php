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
            <header>
                <h1 class="text-2xl font-semibold text-gray-900">Import Sertifikat dari Excel</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Unggah file Excel berisi data sertifikat siswa untuk diproses secara massal.
                </p>
            </header>

            <div class="w-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-6 py-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Unggah File Excel Siswa
                    </h2>

                        {{-- Alert Success --}}
                        @if (session('success'))
                            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-4 text-sm">
                                <span class="font-semibold">{{ session('success') }}</span>
                            </div>
                        @endif

                        {{-- Alert Error --}}
                        @if (session('error'))
                            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 text-sm">
                                <span class="font-semibold">{{ session('error') }}</span>
                            </div>
                        @endif

                        {{-- Form Import --}}
                        <form action="{{ route('sertifikat.import.excel') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div class="space-y-2">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path fill="#185c37" d="M42,4H14c-1.1,0-2,0.9-2,2v36c0,1.1,0.9,2,2,2h28c1.1,0,2-0.9,2-2V6C44,4.9,43.1,4,42,4z" />
                                        <path fill="#21a366" d="M14 4h14v40H14z" />
                                        <path fill="#fff" d="M24.5 29.5h-3.6l-2.5-4.5-2.5 4.5H12l4-7-4-7h3.9l2.5 4.5 2.5-4.5h3.6l-4 7z" />
                                    </svg>
                                    Pilih File Excel
                                </label>

                                <input type="file" name="file" id="file"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50 p-3 shadow-sm"
                                    required>
                                @error('file')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-end pt-4">
                                <a href="{{ route('sertifikat.import.template') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-medium text-xs text-white tracking-wide shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-150 mr-3">
                                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Unduh Template
                                </a>

                                <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5 bg-orange-500 border border-transparent rounded-lg font-semibold text-xs text-white tracking-wide shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 transition-all duration-150">
                                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Import Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        </main>
    </div>
</x-app-layout>
