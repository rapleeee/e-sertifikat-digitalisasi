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
    class="transition-all duration-300 ease-in-out"
  >
      <main class="pt-24 px-4 sm:px-6 lg:px-8 pb-12 max-w-7xl mx-auto space-y-8">

        {{-- Alert Messages --}}
        @if(session('error'))
          <div class="rounded-xl bg-red-50 border border-red-200 p-4 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                  </svg>
                </div>
              </div>
              <div>
                <h4 class="font-semibold text-red-800">Error!</h4>
                <p class="text-red-700 text-sm">{{ session('error') }}</p>
              </div>
            </div>
          </div>
        @endif

        @if(session('success'))
          <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
              <div>
                <h4 class="font-semibold text-emerald-800">Berhasil!</h4>
                <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
              </div>
            </div>
          </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Backup Database</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola backup database MySQL untuk keamanan data</p>
            </div>
            <form action="{{ route('backup.create') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <ion-icon name="download-outline" class="w-4 h-4"></ion-icon>
                    Buat Backup Baru
                </button>
            </form>
        </div>

        {{-- Info Card --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex gap-3">
                <div class="flex-shrink-0">
                    <ion-icon name="information-circle-outline" class="w-5 h-5 text-blue-500 mt-0.5"></ion-icon>
                </div>
                <div class="text-sm text-blue-700">
                    <p class="font-semibold mb-1">Informasi</p>
                    <ul class="list-disc list-inside space-y-0.5 text-blue-600">
                        <li>Backup menggunakan <code class="bg-blue-100 px-1 rounded text-xs">mysqldump</code> dan disimpan di server lokal</li>
                        <li>Disarankan untuk rutin membuat backup dan mendownload file-nya sebagai cadangan</li>
                        <li>Hanya admin yang dapat mengakses fitur ini</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Backup List --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <ion-icon name="server-outline" class="w-5 h-5 text-gray-400"></ion-icon>
                    Daftar Backup
                </h2>
            </div>

            @if($backups->isEmpty())
                <div class="px-5 py-16 text-center">
                    <ion-icon name="cloud-offline-outline" class="w-12 h-12 text-gray-300 mx-auto"></ion-icon>
                    <p class="text-gray-500 mt-3 text-sm">Belum ada backup database</p>
                    <p class="text-gray-400 text-xs mt-1">Klik tombol "Buat Backup Baru" untuk membuat backup pertama</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama File</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ukuran</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($backups as $backup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-2">
                                            <ion-icon name="document-outline" class="w-4 h-4 text-gray-400"></ion-icon>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $backup['filename'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-gray-600 dark:text-gray-300">
                                        @if($backup['size'] < 1024)
                                            {{ $backup['size'] }} B
                                        @elseif($backup['size'] < 1048576)
                                            {{ number_format($backup['size'] / 1024, 1) }} KB
                                        @else
                                            {{ number_format($backup['size'] / 1048576, 2) }} MB
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-gray-600 dark:text-gray-300">
                                        {{ \Carbon\Carbon::createFromTimestamp($backup['date'])->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('backup.download', $backup['filename']) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                                                <ion-icon name="download-outline" class="w-3.5 h-3.5"></ion-icon>
                                                Download
                                            </a>
                                            <form action="{{ route('backup.destroy', $backup['filename']) }}" method="POST" data-swal-confirm="Apakah Anda yakin ingin menghapus backup ini? Tindakan ini tidak dapat dibatalkan.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                                    <ion-icon name="trash-outline" class="w-3.5 h-3.5"></ion-icon>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

      </main>
  </div>
</x-app-layout>
