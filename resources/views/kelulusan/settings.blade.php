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
        <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-10 max-w-full mx-auto space-y-6">
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Pengaturan Kelulusan</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Atur waktu buka halaman cek kelulusan dan pantau jumlah siswa yang sudah diluluskan.
                    </p>
                </div>
                <a href="{{ route('kelulusan.index') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    <ion-icon name="open-outline" class="w-4 h-4"></ion-icon>
                    Lihat Halaman Publik
                </a>
            </header>

            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <div class="flex items-center gap-2">
                        <ion-icon name="checkmark-circle-outline" class="w-4 h-4 flex-shrink-0"></ion-icon>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <div class="font-semibold">Periksa kembali input pengaturan.</div>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm text-slate-600 font-medium">Waktu Buka</p>
                    <p class="text-xl font-bold text-slate-900 mt-2">{{ $announcementAt->translatedFormat('d F Y') }}</p>
                    <p class="text-sm text-slate-500 mt-1">{{ $announcementAt->format('H:i') }} WIB</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm text-slate-600 font-medium">Siswa Kelas XII</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalKelas12 }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm text-slate-600 font-medium">Sudah Diluluskan</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalLulus }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-lg font-semibold text-slate-800">Jadwal Pengumuman</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Sebelum waktu ini tercapai, halaman publik hanya menampilkan countdown.
                    </p>
                </div>

                <form action="{{ route('kelulusan.settings.update') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="announcement_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Buka</label>
                            <input
                                type="date"
                                id="announcement_date"
                                name="announcement_date"
                                value="{{ old('announcement_date', $announcementAt->format('Y-m-d')) }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                required
                            >
                        </div>
                        <div>
                            <label for="announcement_time" class="block text-sm font-medium text-slate-700 mb-1">Jam Buka</label>
                            <input
                                type="time"
                                id="announcement_time"
                                name="announcement_time"
                                value="{{ old('announcement_time', $announcementAt->format('H:i')) }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                required
                            >
                        </div>
                    </div>

                    <div>
                        <label for="graduation_note" class="block text-sm font-medium text-slate-700 mb-1">Catatan Pengambilan SKL</label>
                        <textarea
                            id="graduation_note"
                            name="graduation_note"
                            rows="4"
                            maxlength="1000"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required
                        >{{ old('graduation_note', $graduationNote) }}</textarea>
                        <p class="mt-1 text-xs text-slate-500">
                            Catatan ini akan tampil di popup hasil cek kelulusan siswa.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                        <p class="text-xs text-slate-500">
                            Default awal fitur ini adalah 5 Mei 2026 pukul 10:00 WIB dengan catatan pengambilan SKL tanggal 8 Mei 2026.
                        </p>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">
                            <ion-icon name="save-outline" class="w-4 h-4"></ion-icon>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>
