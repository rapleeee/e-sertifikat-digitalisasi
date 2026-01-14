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
                <h1 class="text-2xl font-semibold text-gray-900">Update Status Eligibilitas Siswa</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Ubah status eligibilitas siswa untuk masuk PTN.
                </p>
            </header>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <div class="flex items-start gap-3">
                        <ion-icon name="alert-circle-outline" class="w-5 h-5 flex-shrink-0 mt-0.5"></ion-icon>
                        <div>
                            <p class="font-semibold mb-2">Terdapat kesalahan dalam formulir:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Formulir -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="border-b border-slate-200 px-6 py-4 bg-slate-50">
                            <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                                <ion-icon name="create-outline" class="w-5 h-5 text-orange-500"></ion-icon>
                                Formulir Update Status Eligibilitas
                            </h2>
                        </div>

                        <form action="{{ route('eligibilitas.update', $siswa) }}" method="POST" class="px-6 py-6 space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Info Siswa Section -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                                    <ion-icon name="information-circle-outline" class="w-4 h-4"></ion-icon>
                                    Informasi Siswa
                                </p>
                                <div class="space-y-2 text-sm text-blue-800">
                                    <div class="flex justify-between">
                                        <span class="font-medium">Nama:</span>
                                        <span>{{ $siswa->nama }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium">NIS:</span>
                                        <span>{{ $siswa->nis }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium">Kelas:</span>
                                        <span>{{ $siswa->kelas }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium">Jurusan:</span>
                                        <span>{{ $siswa->jurusan ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Eligibilitas -->
                            <div>
                                <label class="text-sm font-semibold text-slate-700 flex items-center mb-3">
                                    <ion-icon name="checkmark-circle-outline" class="w-4 h-4 mr-2 text-orange-500"></ion-icon>
                                    Status Eligibilitas
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3 p-4 rounded-lg border-2 border-slate-200 cursor-pointer hover:border-orange-300 hover:bg-orange-50 transition @error('eligibilitas') border-red-400 @enderror"
                                           :class="document.querySelector('input[name=eligibilitas][value=eligible]')?.checked ? 'border-orange-500 bg-orange-50' : ''">
                                        <input
                                            type="radio"
                                            name="eligibilitas"
                                            value="eligible"
                                            @checked(old('eligibilitas', $siswa->eligibilitas) === 'eligible')
                                            class="w-4 h-4 text-orange-500"
                                        >
                                        <div class="flex-1">
                                            <p class="font-semibold text-slate-900 flex items-center gap-1">
                                                <ion-icon name="checkmark-outline" class="w-4 h-4 text-green-600"></ion-icon>
                                                Eligible untuk PTN
                                            </p>
                                            <p class="text-sm text-slate-600">Siswa memenuhi kriteria untuk melanjutkan ke Perguruan Tinggi Negeri</p>
                                        </div>
                                    </label>

                                    <label class="flex items-center gap-3 p-4 rounded-lg border-2 border-slate-200 cursor-pointer hover:border-red-300 hover:bg-red-50 transition @error('eligibilitas') border-red-400 @enderror"
                                           :class="document.querySelector('input[name=eligibilitas][value=tidak_eligible]')?.checked ? 'border-red-500 bg-red-50' : ''">
                                        <input
                                            type="radio"
                                            name="eligibilitas"
                                            value="tidak_eligible"
                                            @checked(old('eligibilitas', $siswa->eligibilitas) === 'tidak_eligible')
                                            class="w-4 h-4 text-red-500"
                                        >
                                        <div class="flex-1">
                                            <p class="font-semibold text-slate-900 flex items-center gap-1">
                                                <ion-icon name="close-outline" class="w-4 h-4 text-red-600"></ion-icon>
                                                Tidak Eligible untuk PTN
                                            </p>
                                            <p class="text-sm text-slate-600">Siswa tidak memenuhi kriteria untuk melanjutkan ke Perguruan Tinggi Negeri</p>
                                        </div>
                                    </label>
                                </div>
                                @error('eligibilitas')
                                    <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                        <ion-icon name="alert-circle-outline" class="w-4 h-4"></ion-icon>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Catatan Eligibilitas -->
                            <div>
                                <label class="text-sm font-semibold text-slate-700 flex items-center mb-2">
                                    <ion-icon name="document-text-outline" class="w-4 h-4 mr-2 text-orange-500"></ion-icon>
                                    Catatan Eligibilitas
                                    <span class="text-slate-400 text-xs font-normal ml-1">(Opsional)</span>
                                </label>
                                <textarea
                                    name="catatan_eligibilitas"
                                    rows="4"
                                    placeholder="Tambahkan catatan atau alasan untuk status eligibilitas ini..."
                                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('catatan_eligibilitas') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                                >{{ old('catatan_eligibilitas', $siswa->catatan_eligibilitas) }}</textarea>
                                @error('catatan_eligibilitas')
                                    <p class="text-sm text-red-500 mt-2 flex items-center gap-1">
                                        <ion-icon name="alert-circle-outline" class="w-4 h-4"></ion-icon>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="border-t border-slate-200 pt-6 flex gap-3">
                                <a href="{{ route('eligibilitas.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition flex items-center gap-2">
                                    <ion-icon name="close-outline" class="w-4 h-4"></ion-icon>
                                    Batal
                                </a>
                                <button type="submit" class="px-6 py-2.5 rounded-lg bg-orange-500 text-white font-semibold hover:bg-orange-600 transition flex items-center gap-2">
                                    <ion-icon name="checkmark-outline" class="w-4 h-4"></ion-icon>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
                        <div class="border-b border-slate-200 pb-4">
                            <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2 mb-2">
                                <ion-icon name="help-circle-outline" class="w-5 h-5 text-blue-500"></ion-icon>
                                Panduan
                            </h3>
                        </div>

                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="font-semibold text-slate-700 mb-2 flex items-center gap-2">
                                    <ion-icon name="checkmark-circle-outline" class="w-4 h-4 text-green-600"></ion-icon>
                                    Eligible
                                </p>
                                <p class="text-slate-600">Pilih ini jika siswa memenuhi semua kriteria untuk melanjutkan ke PTN. Biasanya berdasarkan nilai akademik, prestasi, dan kriteria lainnya.</p>
                            </div>

                            <div>
                                <p class="font-semibold text-slate-700 mb-2 flex items-center gap-2">
                                    <ion-icon name="close-circle-outline" class="w-4 h-4 text-red-600"></ion-icon>
                                    Tidak Eligible
                                </p>
                                <p class="text-slate-600">Pilih ini jika siswa tidak memenuhi kriteria yang ditetapkan untuk melanjutkan ke PTN.</p>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-xs font-semibold text-yellow-900 flex items-center gap-2 mb-2">
                                <ion-icon name="warning-outline" class="w-4 h-4"></ion-icon>
                                Catatan
                            </p>
                            <p class="text-xs text-yellow-800">Catatan ini hanya untuk keperluan administratif dan tidak akan ditampilkan kepada siswa. Gunakan catatan untuk menyimpan alasan atau informasi penting.</p>
                        </div>

                        <!-- Current Status -->
                        @if($siswa->eligibilitas)
                            <div class="bg-slate-100 rounded-lg p-3 border border-slate-200">
                                <p class="text-xs font-semibold text-slate-600 mb-2">Status Saat Ini</p>
                                @if($siswa->eligibilitas === 'eligible')
                                    <p class="text-sm font-semibold text-green-600 flex items-center gap-1">
                                        <ion-icon name="checkmark-circle-outline" class="w-4 h-4"></ion-icon>
                                        Eligible
                                    </p>
                                @else
                                    <p class="text-sm font-semibold text-red-600 flex items-center gap-1">
                                        <ion-icon name="close-circle-outline" class="w-4 h-4"></ion-icon>
                                        Tidak Eligible
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="eligibilitas"]');
            const labels = document.querySelectorAll('label:has(input[name="eligibilitas"])');

            function updateSelection() {
                labels.forEach(label => {
                    const radio = label.querySelector('input[name="eligibilitas"]');
                    if (radio.checked) {
                        if (radio.value === 'eligible') {
                            label.classList.add('border-orange-500', 'bg-orange-50');
                            label.classList.remove('border-red-500', 'bg-red-50');
                        } else {
                            label.classList.add('border-red-500', 'bg-red-50');
                            label.classList.remove('border-orange-500', 'bg-orange-50');
                        }
                    } else {
                        label.classList.remove('border-orange-500', 'bg-orange-50', 'border-red-500', 'bg-red-50');
                    }
                });
            }

            radioButtons.forEach(radio => {
                radio.addEventListener('change', updateSelection);
            });

            // Initial state
            updateSelection();
        });
    </script>
</x-app-layout>
