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
        <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-10 max-w-6xl mx-auto space-y-6">
            <!-- Header -->
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Data Siswa</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Kelola dan cari data siswa yang terhubung dengan sertifikat.
                    </p>
                </div>
                <a href="{{ route('siswa.create') }}"
                   class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Siswa
                </a>
            </header>

            @if(session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.455 5.455L4 4m16 0l-1.455 1.455M4 20l1.455-1.455M20 20l-1.455-1.455"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 space-y-3">
                    <form action="{{ route('siswa.index') }}" method="GET" class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="relative flex-1">
                                <input
                                    type="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Cari siswa berdasarkan nama atau NIS..."
                                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 pl-11 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                >
                                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M9.5 17a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/>
                                </svg>
                            </div>
                        <div class="inline-flex items-center gap-2">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 transition">
                                Cari
                            </button>
                            @if($search !== '' || $filterJenisKelamin || $filterKelas || $filterJurusan || $filterAngkatan || $filterStatus)
                                <a href="{{ route('siswa.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">
                                    Reset
                                </a>
                            @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Filter Jenis Kelamin</label>
                                <select
                                    name="jenis_kelamin"
                                    class="w-full rounded-xl border border-slate-300 px-3 pr-8 py-2 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                >
                                    <option value="">Semua</option>
                                    @foreach($jenisKelaminOptions as $jk)
                                        <option value="{{ $jk }}" {{ $filterJenisKelamin === $jk ? 'selected' : '' }}>
                                            {{ $jk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Filter Kelas</label>
                                <select
                                    name="kelas"
                                    class="w-full rounded-xl border border-slate-300 px-3 pr-8 py-2 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                >
                                    <option value="">Semua</option>
                                    @foreach($kelasOptions as $kelas)
                                        <option value="{{ $kelas }}" {{ $filterKelas === $kelas ? 'selected' : '' }}>
                                            {{ $kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Filter Jurusan</label>
                                <select
                                    name="jurusan"
                                    class="w-full rounded-xl border border-slate-300 px-3 pr-8 py-2 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                >
                                    <option value="">Semua</option>
                                    @foreach($jurusanOptions as $j)
                                        <option value="{{ $j }}" {{ $filterJurusan === $j ? 'selected' : '' }}>
                                            {{ $j }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Filter Angkatan</label>
                                <select
                                    name="angkatan"
                                    class="w-full rounded-xl border border-slate-300 px-3 pr-8 py-2 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                >
                                    <option value="">Semua</option>
                                    @foreach($angkatanOptions as $angkatan)
                                        <option value="{{ $angkatan }}" {{ ($filterAngkatan ?? '') === $angkatan ? 'selected' : '' }}>
                                            {{ $angkatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Filter Status</label>
                                <select
                                    name="status"
                                    class="w-full rounded-xl border border-slate-300 px-3 pr-8 py-2 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                >
                                    @php
                                        $currentStatus = $filterStatus ?? '';
                                    @endphp
                                    <option value="">Semua</option>
                                    <option value="aktif" {{ $currentStatus === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="lulus" {{ $currentStatus === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                    <option value="alumni" {{ $currentStatus === 'alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="px-6 py-3 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="select-all-siswa" class="rounded border-slate-300 text-orange-500 focus:ring-orange-400">
                        <label for="select-all-siswa" class="select-none cursor-pointer">Pilih semua</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <span class="hidden sm:inline text-slate-500">Naikkan ke kelas</span>
                            <select
                                id="bulk-kelas-select"
                                class="rounded-lg border border-slate-300 pl-3 pr-8 py-1.5 text-xs text-slate-700 focus:border-orange-500 focus:ring-1 focus:ring-orange-200"
                            >
                                <option value="">Pilih kelas</option>
                                @foreach($kelasOptions as $kelas)
                                    <option value="{{ $kelas }}">{{ $kelas }}</option>
                                @endforeach
                            </select>
                            <button
                                type="button"
                                id="bulk-promote-button"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg border border-emerald-200 text-emerald-700 font-semibold hover:bg-emerald-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
                                disabled
                            >
                                Naik Kelas
                            </button>
                        </div>
                        <button
                            type="button"
                            id="bulk-delete-button"
                            class="inline-flex items-center px-3 py-1.5 rounded-lg border border-red-200 text-red-600 font-semibold hover:bg-red-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
                            disabled
                        >
                            Hapus terpilih
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-4 text-center">
                                    <span class="sr-only">Pilih</span>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">NIS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">Kelas / Jurusan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">Total Sertifikat</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold tracking-wider text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($siswas as $siswa)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-4 text-center">
                                        <input type="checkbox" class="siswa-checkbox rounded border-slate-300 text-orange-500 focus:ring-orange-400" value="{{ $siswa->id }}">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">{{ $siswa->nama }}</p>
                                            <p class="text-xs text-slate-500">ID: {{ $siswa->id }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm font-medium shadow-sm">{{ $siswa->nis }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-700">
                                            <p>{{ $siswa->kelas ?? '-' }}</p>
                                            <p class="text-xs text-slate-500">{{ $siswa->jurusan ?? '' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $siswa->sertifikats_count ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }} text-sm font-medium shadow-sm">
                                            @if($siswa->sertifikats_count)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                            {{ $siswa->sertifikats_count }} sertifikat
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div x-data="{ open: false }" class="relative inline-block text-left">
                                            <button
                                                type="button"
                                                @click="open = !open"
                                                @click.away="open = false"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-1"
                                            >
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </button>

                                            <div
                                                x-show="open"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                class="origin-top-right absolute right-0 mt-2 w-40 rounded-xl shadow-lg bg-white border border-slate-100 ring-1 ring-black ring-opacity-5 focus:outline-none z-20"
                                            >
                                                <div class="py-1 text-sm text-slate-700">
                                                    <a
                                                        href="{{ route('siswa.show', $siswa) }}"
                                                        class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"
                                                    >
                                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        <span>Detail</span>
                                                    </a>
                                                    <a
                                                        href="{{ route('sertifikat.create', ['siswa_id' => $siswa->id, 'mode' => 'single']) }}"
                                                        class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"
                                                    >
                                                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        <span>Sertifikat</span>
                                                    </a>
                                                    <a
                                                        href="{{ route('siswa.edit', $siswa) }}"
                                                        class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50"
                                                    >
                                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        <span>Edit</span>
                                                    </a>
                                                    <form
                                                        action="{{ route('siswa.destroy', $siswa) }}"
                                                        method="POST"
                                                        class="inline"
                                                        data-swal-confirm="Hapus data {{ $siswa->nama }}? Semua sertifikat yang terkait akan ikut terhapus."
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="submit"
                                                            class="w-full flex items-center gap-2 px-3 py-2 text-left text-rose-600 hover:bg-rose-50"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            <span>Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a1 1 0 001 1h3m10 0h3a1 1 0 001-1V7m-4 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m12 0H5"/>
                                            </svg>
                                            <p class="font-semibold text-slate-600">Belum ada data siswa.</p>
                                            <p class="text-sm">Tambahkan data siswa untuk mulai mengelola sertifikat.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($siswas->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 backdrop-blur flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-sm text-slate-500">
                            Menampilkan {{ $siswas->firstItem() }} - {{ $siswas->lastItem() }} dari {{ $siswas->total() }} siswa
                        </p>
                        {{ $siswas->links('pagination.simple') }}
                    </div>
                @endif
            </div>

            <form id="bulk-delete-form" method="POST" action="{{ route('siswa.bulk-destroy') }}" data-swal-confirm="Hapus data siswa terpilih? Semua sertifikat terkait juga akan terhapus.">
                @csrf
                @method('DELETE')
                <div id="bulk-delete-ids-container"></div>
            </form>

            <form id="bulk-promote-form" method="POST" action="{{ route('siswa.bulk-promote') }}">
                @csrf
                <input type="hidden" name="kelas_baru" id="bulk-kelas-baru">
                <div id="bulk-promote-ids-container"></div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const selectAll = document.getElementById('select-all-siswa');
                    const checkboxes = () => Array.from(document.querySelectorAll('.siswa-checkbox'));
                    const bulkDeleteButton = document.getElementById('bulk-delete-button');
                    const bulkPromoteButton = document.getElementById('bulk-promote-button');
                    const bulkForm = document.getElementById('bulk-delete-form');
                    const idsContainer = document.getElementById('bulk-delete-ids-container');
                    const promoteForm = document.getElementById('bulk-promote-form');
                    const promoteIdsContainer = document.getElementById('bulk-promote-ids-container');
                    const kelasSelect = document.getElementById('bulk-kelas-select');
                    const kelasBaruInput = document.getElementById('bulk-kelas-baru');

                    function updateBulkButtonState() {
                        const anyChecked = checkboxes().some(cb => cb.checked);
                        bulkDeleteButton.disabled = !anyChecked;
                        if (bulkPromoteButton) {
                            bulkPromoteButton.disabled = !anyChecked || !kelasSelect?.value;
                        }
                    }

                    if (selectAll) {
                        selectAll.addEventListener('change', function () {
                            checkboxes().forEach(cb => {
                                cb.checked = selectAll.checked;
                            });
                            updateBulkButtonState();
                        });
                    }

                    checkboxes().forEach(cb => {
                        cb.addEventListener('change', function () {
                            if (!cb.checked && selectAll) {
                                selectAll.checked = false;
                            }
                            updateBulkButtonState();
                        });
                    });

                    kelasSelect?.addEventListener('change', function () {
                        updateBulkButtonState();
                    });

                    bulkDeleteButton?.addEventListener('click', function () {
                        const selected = checkboxes().filter(cb => cb.checked).map(cb => cb.value);
                        if (!selected.length) {
                            return;
                        }

                        idsContainer.innerHTML = '';
                        selected.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            idsContainer.appendChild(input);
                        });

                        if (typeof Swal === 'undefined') {
                            bulkForm.submit();
                            return;
                        }

                        Swal.fire({
                            title: 'Hapus data terpilih?',
                            text: 'Semua sertifikat yang terkait dengan siswa terpilih juga akan dihapus.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, hapus',
                            cancelButtonText: 'Batal',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl px-4 py-2 font-semibold',
                                cancelButton: 'rounded-xl px-4 py-2 font-semibold',
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                bulkForm.submit();
                            }
                        });
                    });

                    bulkPromoteButton?.addEventListener('click', function () {
                        const selected = checkboxes().filter(cb => cb.checked).map(cb => cb.value);
                        const kelas = kelasSelect?.value || '';

                        if (!selected.length || !kelas) {
                            return;
                        }

                        promoteIdsContainer.innerHTML = '';
                        selected.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            promoteIdsContainer.appendChild(input);
                        });

                        kelasBaruInput.value = kelas;

                        if (typeof Swal === 'undefined') {
                            promoteForm.submit();
                            return;
                        }

                        Swal.fire({
                            title: 'Naikkan kelas siswa terpilih?',
                            text: `Kelas akan diubah menjadi "${kelas}" untuk ${selected.length} siswa. Jurusan tidak akan berubah.`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#059669',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, naikkan',
                            cancelButtonText: 'Batal',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl px-4 py-2 font-semibold',
                                cancelButton: 'rounded-xl px-4 py-2 font-semibold',
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                promoteForm.submit();
                            }
                        });
                    });
                });
            </script>
        </main>
    </div>
</x-app-layout>
