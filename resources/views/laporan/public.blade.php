<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan ke Admin - Certisat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    @include('profile.partials.navbar-user')

    <main class="pt-24 pb-16 px-4 sm:px-6 lg:px-10 max-w-3xl mx-auto space-y-8">
        <section class="space-y-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Kirim Laporan ke Admin</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Gunakan formulir ini untuk melaporkan kendala, koreksi data sertifikat, atau saran perbaikan terkait sistem sertifikat siswa.
                </p>
            </div>

            @if(session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <form action="{{ route('laporan.public.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama lengkap</label>
                            <input
                                type="text"
                                name="nama"
                                value="{{ old('nama') }}"
                                required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('nama') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('nama')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Email
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('email') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">NIS (opsional)</label>
                            <input
                                type="text"
                                name="nis"
                                value="{{ old('nis') }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('nis') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('nis')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Judul laporan (opsional)</label>
                            <input
                                type="text"
                                name="subject"
                                value="{{ old('subject') }}"
                                placeholder="Contoh: Koreksi nama di sertifikat"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('subject') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            >
                            @error('subject')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Isi laporan</label>
                        <textarea
                            name="message"
                            rows="5"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('message') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                            placeholder="Ceritakan masalah atau saran kamu secara jelas. Jika terkait sertifikat, sebutkan jenis dan tanggal kegiatan."
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-500">
                        <p>
                            Pastikan email yang kamu isi aktif karena admin akan mengirim balasan dan hasil tindak lanjut laporan ke email tersebut.
                        </p>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 transition"
                        >
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    @include('profile.partials.footer')
</body>
</html>
