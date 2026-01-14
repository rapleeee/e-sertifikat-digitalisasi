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
                <a href="{{ route('eligibilitas.index') }}" class="inline-flex items-center hover:text-orange-600 transition">
                    <ion-icon name="arrow-back-outline" class="w-4 h-4 mr-1"></ion-icon>
                    Kembali
                </a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Update Status Eligibilitas Bulk</span>
            </div>

            <header>
                <h1 class="text-2xl font-semibold text-gray-900">Update Status Eligibilitas</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Ubah status eligibilitas siswa kelas 12 secara individual atau bulk.
                </p>
            </header>

            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <div class="flex items-center gap-2">
                        <ion-icon name="checkmark-circle-outline" class="w-4 h-4 flex-shrink-0"></ion-icon>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 space-y-3">
                    <form action="{{ route('eligibilitas.bulk-index') }}" method="GET" class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="relative flex-1">
                                <input
                                    type="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Cari siswa berdasarkan nama atau NIS..."
                                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 pl-11 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                >
                                <ion-icon name="search-outline" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4"></ion-icon>
                            </div>

                            <select name="eligibilitas" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Semua Status</option>
                                <option value="eligible" @selected($filterEligibilitas === 'eligible')>Eligible</option>
                                <option value="tidak_eligible" @selected($filterEligibilitas === 'tidak_eligible')>Tidak Eligible</option>
                                <option value="null" @selected($filterEligibilitas === 'null')>Belum Ditentukan</option>
                            </select>

                            <select name="per_page" onchange="this.form.submit()" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 bg-white">
                                <option value="">Items per halaman</option>
                                <option value="10" @selected($perPage == 10)>10 items</option>
                                <option value="20" @selected($perPage == 20)>20 items</option>
                                <option value="50" @selected($perPage == 50)>50 items</option>
                                <option value="100" @selected($perPage == 100)>100 items</option>
                            </select>
                            </select>

                            <div class="inline-flex items-center gap-2">
                                <button type="submit" class="px-4 py-2 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 transition">
                                    Cari
                                </button>
                                @if($search !== '' || $filterEligibilitas)
                                    <a href="{{ route('eligibilitas.bulk-index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Bulk Update Form -->
                <form id="bulkForm" action="{{ route('eligibilitas.bulk-update') }}" method="POST" class="divide-y divide-slate-200">
                    @csrf
                    @method('PUT')

                    <!-- Bulk Action Toolbar -->
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500 cursor-pointer"
                                   onchange="toggleAllCheckboxes(this)">
                            <label for="selectAll" class="text-sm font-medium text-slate-700 cursor-pointer">
                                Pilih Semua
                            </label>
                        </div>

                        <div id="bulkActions" style="display: none;" class="flex items-center gap-3">
                            <span class="text-sm text-slate-600">
                                <span id="selectedCount">0</span> siswa dipilih
                            </span>

                            <select name="bulk_status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">-- Pilih Status --</option>
                                <option value="eligible">Eligible</option>
                                <option value="tidak_eligible">Tidak Eligible</option>
                            </select>

                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-green-500 text-white text-sm font-semibold hover:bg-green-600 transition"
                                    onclick="return confirm('Update status untuk ' + document.querySelectorAll('input[name=\\\"siswa_ids[]\\\"]:checked').length + ' siswa?')">
                                <ion-icon name="checkmark-done-outline" class="w-4 h-4"></ion-icon>
                                Terapkan
                            </button>
                        </div>
                    </div>

                    <!-- Tabel Siswa -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-slate-700">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <div class="flex items-center">
                                            <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-orange-500" disabled>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left font-semibold text-slate-700">No</th>
                                    <th class="px-6 py-3 text-left font-semibold text-slate-700">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left font-semibold text-slate-700">NIS</th>
                                    <th class="px-6 py-3 text-left font-semibold text-slate-700">Kelas</th>
                                    <th class="px-6 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                                    <th class="px-6 py-3 text-center font-semibold text-slate-700">Status Saat Ini</th>
                                    <th class="px-6 py-3 text-center font-semibold text-slate-700">Ubah Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse($siswas as $index => $siswa)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" 
                                                   class="w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500 cursor-pointer"
                                                   onchange="updateBulkSelection()">
                                        </td>
                                        <td class="px-6 py-4">{{ ($siswas->currentPage() - 1) * $siswas->perPage() + $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900">{{ $siswa->nama }}</div>
                                            <div class="text-xs text-slate-500">{{ $siswa->nisn }}</div>
                                        </td>
                                        <td class="px-6 py-4">{{ $siswa->nis }}</td>
                                        <td class="px-6 py-4">{{ $siswa->kelas }}</td>
                                        <td class="px-6 py-4">{{ $siswa->jurusan ?? '-' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if($siswa->eligibilitas === 'eligible')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                    <ion-icon name="checkmark-circle" class="w-4 h-4"></ion-icon>
                                                    Eligible
                                                </span>
                                            @elseif($siswa->eligibilitas === 'tidak_eligible')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                                    <ion-icon name="close-circle" class="w-4 h-4"></ion-icon>
                                                    Tidak Eligible
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                                    <ion-icon name="help-circle" class="w-4 h-4"></ion-icon>
                                                    Belum Ditentukan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <select name="status_{{ $siswa->id }}" 
                                                    class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                                <option value="">-- Pilih --</option>
                                                <option value="eligible" @selected($siswa->eligibilitas === 'eligible')>Eligible</option>
                                                <option value="tidak_eligible" @selected($siswa->eligibilitas === 'tidak_eligible')>Tidak Eligible</option>
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center">
                                            <div class="flex flex-col items-center gap-2 text-slate-500">
                                                <ion-icon name="search-outline" class="w-8 h-8"></ion-icon>
                                                <p>Tidak ada data siswa yang sesuai</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                        {{ $siswas->appends(request()->query())->links() }}
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                <div class="flex gap-4">
                    <ion-icon name="information-circle-outline" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></ion-icon>
                    <div class="text-sm text-blue-900">
                        <p class="font-semibold mb-2">Cara menggunakan:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-800">
                            <li><strong>Update Individual:</strong> Pilih status di kolom "Ubah Status" untuk setiap siswa, kemudian klik tombol Terapkan untuk siswa tersebut</li>
                            <li><strong>Update Bulk:</strong> Centang kotak di sebelah nama siswa, pilih status di dropdown, kemudian klik "Terapkan"</li>
                            <li>Gunakan filter untuk mempermudah pencarian siswa</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle all checkboxes
        function toggleAllCheckboxes(selectAllCheckbox) {
            const checkboxes = document.querySelectorAll('input[name="siswa_ids[]"]');
            checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
            updateBulkSelection();
        }

        // Update bulk selection display
        function updateBulkSelection() {
            const selectedCount = document.querySelectorAll('input[name="siswa_ids[]"]:checked').length;
            document.getElementById('selectedCount').textContent = selectedCount;
            
            const bulkActions = document.getElementById('bulkActions');
            if (selectedCount > 0) {
                bulkActions.style.display = 'flex';
            } else {
                bulkActions.style.display = 'none';
            }

            // Update select all checkbox state
            const allCheckboxes = document.querySelectorAll('input[name="siswa_ids[]"]');
            const selectAllCheckbox = document.getElementById('selectAll');
            selectAllCheckbox.checked = selectedCount === allCheckboxes.length && allCheckboxes.length > 0;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < allCheckboxes.length;
        }

        // Validate bulk form before submit
        document.getElementById('bulkForm').addEventListener('submit', function(e) {
            const selectedIds = document.querySelectorAll('input[name="siswa_ids[]"]:checked').length;
            if (selectedIds > 0) {
                const bulkStatus = document.querySelector('select[name="bulk_status"]').value;
                if (!bulkStatus) {
                    e.preventDefault();
                    alert('Pilih status untuk update bulk!');
                    return false;
                }
            }
        });

        // Individual update via AJAX when select changes
        document.addEventListener('change', function(e) {
            if (e.target.name && e.target.name.startsWith('status_')) {
                const siswaId = e.target.name.split('_')[1];
                const status = e.target.value;
                
                if (status) {
                    const formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('siswa_id', siswaId);
                    formData.append('status', status);
                    
                    fetch('{{ route("eligibilitas.individual-update") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value,
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const toast = document.createElement('div');
                            toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                            toast.textContent = 'Status siswa berhasil diperbarui';
                            document.body.appendChild(toast);
                            setTimeout(() => toast.remove(), 3000);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }
        });
    </script>
</x-app-layout>
