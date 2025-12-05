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
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Laporan Masuk</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Daftar laporan yang dikirim oleh pengguna. Balas laporan melalui tampilan percakapan.
                    </p>
                </div>
            </header>

            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between text-xs text-slate-500">
                    <span>Total laporan: {{ $laporans->total() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengirim</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Subjek</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Terakhir diperbarui</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($laporans as $laporan)
                                <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="window.location='{{ route('laporan.show', $laporan) }}'">
                                    <td class="px-6 py-3 text-sm text-slate-800">
                                        <div class="font-semibold">{{ $laporan->nama }}</div>
                                        <div class="text-xs text-slate-500">{{ $laporan->email }}</div>
                                        @if($laporan->nis)
                                            <div class="text-xs text-slate-500">NIS: {{ $laporan->nis }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-sm text-slate-800">
                                        {{ $laporan->subject ?? 'Tanpa judul' }}
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $laporan->messages_count }} pesan</div>
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        @php
                                            $status = $laporan->status;
                                            $statusLabel = $status === 'closed' ? 'Selesai' : 'Terbuka';
                                            $statusClass = $status === 'closed'
                                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                                : 'bg-amber-50 text-amber-700 border-amber-200';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right text-xs text-slate-500">
                                        {{ $laporan->updated_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-500 text-sm">
                                        Belum ada laporan yang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($laporans->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between text-xs text-slate-500">
                        <span>
                            Menampilkan {{ $laporans->firstItem() }} - {{ $laporans->lastItem() }} dari {{ $laporans->total() }} laporan
                        </span>
                        {{ $laporans->links('pagination.simple') }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-app-layout>

