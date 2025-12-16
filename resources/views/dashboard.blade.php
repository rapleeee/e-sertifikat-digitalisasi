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
      <main class="pt-24 px-4 sm:px-6 lg:px-8 pb-12 max-w-7xl mx-auto space-y-10">
        {{-- Alert Messages --}}
        @if(session('error'))
          <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 shadow-sm">
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
          <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
              <div>
                <h4 class="font-semibold text-emerald-800">Success!</h4>
                <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
              </div>
            </div>
          </div>
        @endif
        @if(session('skipped'))
          <div class="mb-6 rounded-xl bg-amber-50 border border-amber-200 p-4 shadow-sm">
            <div class="flex items-start gap-3">
              <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 17a1 1 0 110 2 1 1 0 010-2zm0-12a9 9 0 110 18 9 9 0 010-18z"/>
                  </svg>
                </div>
              </div>
              <div>
                <h4 class="font-semibold text-amber-800">Beberapa file dilewati</h4>
                <p class="text-amber-700 text-sm mb-2">Pastikan NIS sesuai dengan data siswa yang terdaftar:</p>
                <ul class="list-disc list-inside text-sm text-amber-700 space-y-1">
                  @foreach(session('skipped') as $row)
                    <li>{{ $row }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        @endif

        {{-- Hero Section --}}
        <section class="mb-4">
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                  </svg>
                </div>
                <div>
                  <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Selamat datang, {{ Auth::user()->name }}</h1>
                  <p class="text-sm text-gray-500">Ringkasan singkat data sertifikat dan siswa.</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        {{-- Statistics Cards --}}
        <section class="mb-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
              $stats = [
                [
                  'title' => 'Total Sertifikat', 
                  'value' => $totalSertifikasi ?? 0, 
                  'gradient' => 'from-blue-500 via-blue-600 to-blue-700', 
                  'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                  'changeType' => 'increase'
                ],
                [
                  'title' => 'Total Siswa', 
                  'value' => $totalSiswa ?? 0, 
                  'gradient' => 'from-emerald-500 via-emerald-600 to-green-700', 
                  'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                  'changeType' => 'increase'
                ],
                [
                  'title' => 'Sertifikat Bulan Ini', 
                  'value' => $totalSertifikatBulanIni ?? 0, 
                  'gradient' => 'from-purple-500 via-purple-600 to-indigo-700', 
                  'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>',
                  'changeType' => 'increase'
                ],
                [
                  'title' => 'Admin Aktif', 
                  'value' => $totalAdminAktif ?? 0, 
                  'gradient' => 'from-orange-500 via-orange-600 to-red-600', 
                  'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                  'changeType' => 'increase'
                ]
              ];
            @endphp

            @foreach($stats as $stat)
              <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $stat['title'] }}</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($stat['value']) }}</p>
                  </div>
                  <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500">
                    {!! $stat['icon'] !!}
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </section>

        {{-- Analitik Sertifikat --}}
        <section class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-10">
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-sm font-semibold text-gray-800">Sertifikat 12 Bulan Terakhir</h2>
              <span class="text-xs text-gray-400">Semua jenis sertifikat</span>
            </div>
            <div class="h-52">
              <canvas id="sertifikat-per-bulan-chart" class="w-full h-full"></canvas>
            </div>
          </div>
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Distribusi Sertifikat per Jurusan</h2>
            @if(isset($jurusanLabels) && count($jurusanLabels))
              <ul class="space-y-2 text-sm">
                @foreach($jurusanLabels as $index => $label)
                  <li class="flex items-center justify-between">
                    <span class="text-gray-600">{{ $label }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                      {{ $jurusanValues[$index] ?? 0 }} sertifikat
                    </span>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="text-sm text-gray-500">Belum ada data jurusan untuk sertifikat.</p>
            @endif
          </div>
        </section>

        {{-- Data Siswa --}}
        <section class="mb-10">
          <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
              <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                  </div>
                  <div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Siswa</h2>
                    <p class="text-gray-600 mt-1">Kelola dan lihat detail sertifikat siswa</p>
                  </div>
                </div>
                <a href="{{ route('siswa.create') }}" 
                   class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                  <span>Tambah Siswa</span>
                </a>
              </div>

              {{-- Filter Section --}}
              <div class="bg-white rounded-lg p-4 border border-gray-200">
                <form method="GET" action="{{ route('dashboard') }}" class="space-y-4">
                  <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span class="font-semibold text-gray-700">Filter Data</span>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- Search Input --}}
                    <div>
                      <label for="search" class="block text-xs font-medium text-gray-600 mb-2">Cari (Nama/NIS/NISN)</label>
                      <input type="text" 
                             id="search" 
                             name="search" 
                             value="{{ $filters['search'] ?? '' }}"
                             placeholder="Ketik nama atau NIS..."
                             class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    {{-- Jurusan Filter --}}
                    <div>
                      <label for="jurusan" class="block text-xs font-medium text-gray-600 mb-2">Jurusan</label>
                      <select id="jurusan" 
                              name="jurusan"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Jurusan</option>
                        @foreach($filterOptions['jurusans'] ?? [] as $j)
                          <option value="{{ $j }}" {{ $filters['jurusan'] === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                      </select>
                    </div>

                    {{-- Angkatan Filter --}}
                    <div>
                      <label for="angkatan" class="block text-xs font-medium text-gray-600 mb-2">Angkatan</label>
                      <select id="angkatan" 
                              name="angkatan"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Angkatan</option>
                        @foreach($filterOptions['angkatans'] ?? [] as $a)
                          <option value="{{ $a }}" {{ $filters['angkatan'] === $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                      </select>
                    </div>

                    {{-- Kelas Filter --}}
                    <div>
                      <label for="kelas" class="block text-xs font-medium text-gray-600 mb-2">Kelas</label>
                      <select id="kelas" 
                              name="kelas"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Kelas</option>
                        @foreach($filterOptions['kelases'] ?? [] as $k)
                          <option value="{{ $k }}" {{ $filters['kelas'] === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                      </select>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                      <label for="status" class="block text-xs font-medium text-gray-600 mb-2">Status</label>
                      <select id="status" 
                              name="status"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        @foreach($filterOptions['statuses'] ?? [] as $st)
                          <option value="{{ $st }}" {{ $filters['status'] === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="flex gap-2 pt-2">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                      </svg>
                      Terapkan Filter
                    </button>
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center gap-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                      Reset
                    </a>
                  </div>
                </form>
              </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                      Nama Siswa
                    </th>
                    <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                      NIS
                    </th>
                    <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                      Jumlah Sertifikat
                    </th>
                    <th scope="col" class="px-6 py-5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                  @forelse ($siswas as $s)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                      <td class="px-8 py-6 whitespace-nowrap">
                        <div class="flex flex-col">
                          <span class="text-sm font-semibold text-gray-900">
                            {{ $s->nama }}
                          </span>
                          <span class="text-xs text-gray-500">Student</span>
                        </div>
                      </td>
                      <td class="px-6 py-6 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $s->nis }}</div>
                        <div class="text-sm text-gray-500">NIS</div>
                      </td>
                      <td class="px-6 py-6 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                          <span class="px-4 py-2 inline-flex items-center gap-2 text-sm font-semibold rounded-full {{ $s->sertifikats_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} shadow-sm">
                            @if($s->sertifikats_count > 0)
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                              </svg>
                            @endif
                            {{ $s->sertifikats_count }} Sertifikat
                          </span>
                        </div>
                      </td>
                      <td class="px-6 py-6 whitespace-nowrap text-center">
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                          <button type="button"
                                  @click="open = !open"
                                  @keydown.escape.window="open = false"
                                  class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg border border-gray-200 hover:bg-gray-200 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c.828 0 1.5-.672 1.5-1.5S12.828 5 12 5s-1.5.672-1.5 1.5S11.172 8 12 8zM12 14c.828 0 1.5-.672 1.5-1.5S12.828 11 12 11s-1.5.672-1.5 1.5S11.172 14 12 14zM12 20c.828 0 1.5-.672 1.5-1.5S12.828 17 12 17s-1.5.672-1.5 1.5S11.172 20 12 20z"/>
                            </svg>
                            Kelola
                          </button>
                          <div x-cloak
                               x-show="open"
                               x-transition
                               @click.away="open = false"
                               class="origin-top-right absolute right-0 mt-3 w-56 rounded-2xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 border border-slate-100 divide-y divide-slate-100 z-20">
                            <div class="py-2">
                              <a href="{{ route('siswa.show', $s) }}"
                                 class="flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                              </a>
                              <a href="{{ route('sertifikat.create', ['siswa_id' => $s->id, 'mode' => 'single']) }}"
                                 class="flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Sertifikat
                              </a>
                              <a href="{{ route('siswa.edit', $s) }}"
                                 class="flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-6-9l6 6m0 0L13 5m6 6v2"/>
                                </svg>
                                Edit Data
                              </a>
                            </div>
                            <div class="py-2">
                              <form action="{{ route('siswa.destroy', $s) }}" method="POST" data-swal-confirm="Hapus data {{ $s->nama }}? Sertifikat terkait juga akan terhapus.">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        @click="open = false"
                                        class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                  <svg class="w-4 h-4 mr-2" fill="none" stroke="CurrentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                  </svg>
                                  Hapus Data
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                          <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                          </div>
                          <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Data Siswa</h3>
                          <p class="text-gray-500 mb-4">Mulai tambahkan data siswa untuk mengelola sertifikat mereka.</p>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            @if($siswas->hasPages())
              <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                  <div class="text-sm text-gray-600">
                    Menampilkan {{ $siswas->firstItem() }} - {{ $siswas->lastItem() }} dari {{ $siswas->total() }} siswa
                  </div>
                  <div class="pagination-wrapper">
                    {{ $siswas->links('pagination.simple') }}
                  </div>
                </div>
              </div>
            @endif
          </div>
        </section>

      </main>
  </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function () {
    const ctx = document.getElementById('sertifikat-per-bulan-chart');
    if (!ctx || typeof Chart === 'undefined') return;

    const labels = @json($chartMonths ?? []);
    const values = @json($chartValues ?? []);

    if (!labels.length) {
      ctx.parentElement.innerHTML = '<p class="text-sm text-gray-500">Belum ada data sertifikat dalam 12 bulan terakhir.</p>';
      return;
    }

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Jumlah Sertifikat',
          data: values,
          backgroundColor: 'rgba(249, 115, 22, 0.7)',
          borderRadius: 6,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { size: 11 } }
          },
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1, precision: 0 }
          }
        }
      }
    });
  })();
</script>
