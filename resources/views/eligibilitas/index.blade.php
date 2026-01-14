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
            <!-- Header -->
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Daftar Siswa Eligibilitas</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Kelola status eligibilitas siswa kelas 12 untuk masuk PTN.
                    </p>
                </div>
            </header>

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

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Total Siswa Kelas 12 -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 font-medium">Total Siswa Kelas 12</p>
                            <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalSiswaKelas12 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                            <ion-icon name="people-outline" class="w-6 h-6"></ion-icon>
                        </div>
                    </div>
                </div>

                <!-- Eligible -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 font-medium">Eligible</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalEligible }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $persentaseEligible }}%</p>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                            <ion-icon name="checkmark-circle-outline" class="w-6 h-6"></ion-icon>
                        </div>
                    </div>
                </div>

                <!-- Tidak Eligible -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 font-medium">Tidak Eligible</p>
                            <p class="text-3xl font-bold text-red-600 mt-2">{{ $totalTidakEligible }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ round((($totalTidakEligible / max($totalSiswaKelas12, 1)) * 100), 2) }}%</p>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                            <ion-icon name="close-circle-outline" class="w-6 h-6"></ion-icon>
                        </div>
                    </div>
                </div>

                <!-- Belum Ditentukan -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 font-medium">Belum Ditentukan</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $totalBelumDitentukan }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ round((($totalBelumDitentukan / max($totalSiswaKelas12, 1)) * 100), 2) }}%</p>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                            <ion-icon name="help-circle-outline" class="w-6 h-6"></ion-icon>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Statistik -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pie Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-4">Distribusi Status Eligibilitas</h2>
                    <div class="flex items-center justify-center h-64">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>

                <!-- Bar Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-4">Jumlah Siswa per Status</h2>
                    <div class="flex items-center justify-center h-64">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 space-y-3">
                    <form action="{{ route('eligibilitas.index') }}" method="GET" class="space-y-3">
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

                            <select name="eligibilitas" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Semua Status</option>
                                <option value="eligible" @selected($filterEligibilitas === 'eligible')>Eligible</option>
                                <option value="tidak_eligible" @selected($filterEligibilitas === 'tidak_eligible')>Tidak Eligible</option>
                            </select>

                            <div class="inline-flex items-center gap-2">
                                <button type="submit" class="px-4 py-2 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 transition">
                                    Cari
                                </button>
                                @if($search !== '' || $filterEligibilitas)
                                    <a href="{{ route('eligibilitas.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Daftar Siswa Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-slate-700">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">No</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Nama Siswa</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">NIS</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Kelas</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                                <th class="px-6 py-3 text-center font-semibold text-slate-700">Status Eligibilitas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($siswas as $index => $siswa)
                                <tr class="hover:bg-slate-50 transition">
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center">
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
            </div>
        </main>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalEligible = {{ $totalEligible }};
            const totalTidakEligible = {{ $totalTidakEligible }};
            const totalBelumDitentukan = {{ $totalBelumDitentukan }};

            // Pie Chart
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Eligible', 'Tidak Eligible', 'Belum Ditentukan'],
                    datasets: [{
                        data: [totalEligible, totalTidakEligible, totalBelumDitentukan],
                        backgroundColor: ['#10b981', '#ef4444', '#eab308'],
                        borderColor: ['#047857', '#dc2626', '#ca8a04'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            // Bar Chart
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Eligible', 'Tidak Eligible', 'Belum Ditentukan'],
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: [totalEligible, totalTidakEligible, totalBelumDitentukan],
                        backgroundColor: ['#10b981', '#ef4444', '#eab308'],
                        borderColor: ['#047857', '#dc2626', '#ca8a04'],
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
