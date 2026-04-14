<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan ke Admin - Certisat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .brutal-shadow { box-shadow: 5px 5px 0px 0px #000; }
    </style>
</head>
<body class="bg-amber-50 text-black antialiased">
    @include('profile.partials.navbar-user')

    <main class="pt-24 pb-16 px-4 sm:px-6 lg:px-10 max-w-3xl mx-auto space-y-8">
        <section class="space-y-4">
            <div>
                <div class="inline-block border-[3px] border-black bg-orange-500 px-4 py-1 text-xs font-black uppercase tracking-widest text-white mb-3" style="box-shadow: 3px 3px 0px 0px #000;">
                    Laporan
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-black uppercase tracking-tight">Kirim Laporan ke Admin</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Gunakan formulir ini untuk melaporkan kendala, koreksi data sertifikat, atau saran perbaikan terkait sistem sertifikat siswa.
                </p>
            </div>

            @if(session('success'))
                <div class="border-[3px] border-black bg-lime-300 px-4 py-3 text-sm text-black font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border-[3px] border-black p-6 brutal-shadow">
                <form action="{{ route('laporan.public.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-black text-black mb-1 uppercase tracking-wide">Nama lengkap</label>
                            <input
                                type="text"
                                name="nama"
                                value="{{ old('nama') }}"
                                required
                                class="w-full border-[2px] border-black px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-0 bg-amber-50 font-medium @error('nama') border-red-500 @enderror"
                            >
                            @error('nama')
                                <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-black text-black mb-1 uppercase tracking-wide">
                                Email
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full border-[2px] border-black px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-0 bg-amber-50 font-medium @error('email') border-red-500 @enderror"
                            >
                            @error('email')
                                <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-black text-black mb-1 uppercase tracking-wide">NIS (opsional)</label>
                            <input
                                type="text"
                                name="nis"
                                value="{{ old('nis') }}"
                                class="w-full border-[2px] border-black px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-0 bg-amber-50 font-medium @error('nis') border-red-500 @enderror"
                            >
                            @error('nis')
                                <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-black text-black mb-1 uppercase tracking-wide">Judul laporan (opsional)</label>
                            <input
                                type="text"
                                name="subject"
                                value="{{ old('subject') }}"
                                placeholder="Contoh: Koreksi nama di sertifikat"
                                class="w-full border-[2px] border-black px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-0 bg-amber-50 font-medium @error('subject') border-red-500 @enderror"
                            >
                            @error('subject')
                                <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-black mb-1 uppercase tracking-wide">Isi laporan</label>
                        <textarea
                            name="message"
                            rows="5"
                            required
                            class="w-full border-[2px] border-black px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-0 bg-amber-50 font-medium @error('message') border-red-500 @enderror"
                            placeholder="Ceritakan masalah atau saran kamu secara jelas. Jika terkait sertifikat, sebutkan jenis dan tanggal kegiatan."
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-gray-500">
                        <p class="font-medium">
                            Pastikan email yang kamu isi aktif karena admin akan mengirim balasan dan hasil tindak lanjut laporan ke email tersebut.
                        </p>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 border-[3px] border-black bg-orange-500 text-white text-sm font-black uppercase tracking-wide hover:bg-orange-600 transition-colors"
                            style="box-shadow: 4px 4px 0px 0px #000;"
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
