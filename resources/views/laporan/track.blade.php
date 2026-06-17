<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Laporan - Certisat</title>
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
                <div class="inline-block border-[3px] border-black bg-blue-500 px-4 py-1 text-xs font-black uppercase tracking-widest text-white mb-3" style="box-shadow: 3px 3px 0px 0px #000;">
                    Tracking
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-black uppercase tracking-tight">Lacak Laporan Anda</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Masukkan kode pelacakan yang dikirimkan ke email Anda untuk melihat status laporan dan balasan dari admin.
                </p>
            </div>

            <div class="bg-white border-[3px] border-black p-6 brutal-shadow">
                <form action="{{ route('laporan.track') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-grow">
                        <label class="sr-only">Kode Pelacakan</label>
                        <input
                            type="text"
                            name="code"
                            value="{{ request('code') }}"
                            placeholder="Contoh: TRK-A8B9C1D2"
                            required
                            class="w-full border-[2px] border-black px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-0 bg-amber-50 font-medium"
                        >
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 border-[3px] border-black bg-blue-500 text-white text-sm font-black uppercase tracking-wide hover:bg-blue-600 transition-colors shrink-0"
                        style="box-shadow: 4px 4px 0px 0px #000;"
                    >
                        Lacak Tiket
                    </button>
                </form>
            </div>

            @if(request('code') && !$laporan)
                <div class="border-[3px] border-black bg-red-400 px-4 py-3 text-sm text-black font-bold">
                    Kode pelacakan tidak ditemukan. Pastikan kode yang Anda masukkan benar.
                </div>
            @endif

            @if($laporan)
                <div class="bg-white border-[3px] border-black p-6 brutal-shadow space-y-6 mt-8">
                    <div class="flex items-center justify-between border-b-[3px] border-black pb-4">
                        <div>
                            <h2 class="text-lg font-black uppercase tracking-tight">Detail Laporan</h2>
                            <p class="text-sm font-medium text-gray-600">Kode: {{ $laporan->tracking_code }}</p>
                        </div>
                        <div>
                            @if($laporan->status === 'open')
                                <span class="border-[2px] border-black bg-green-400 px-3 py-1 text-xs font-black uppercase">Open</span>
                            @else
                                <span class="border-[2px] border-black bg-gray-400 px-3 py-1 text-xs font-black uppercase">Closed</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <p><span class="font-black uppercase">Nama:</span> {{ $laporan->nama }}</p>
                        <p><span class="font-black uppercase">Subjek:</span> {{ $laporan->subject ?? '-' }}</p>
                        <p><span class="font-black uppercase">Tanggal:</span> {{ $laporan->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <div class="space-y-4 pt-4 border-t-[3px] border-black">
                        <h3 class="font-black uppercase tracking-tight text-md">Riwayat Pesan</h3>
                        
                        <div class="space-y-4">
                            @foreach($laporan->messages as $msg)
                                <div class="border-[2px] border-black p-4 {{ $msg->sender_type === 'user' ? 'bg-amber-50' : 'bg-blue-50' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-black uppercase tracking-wider {{ $msg->sender_type === 'user' ? 'text-orange-600' : 'text-blue-600' }}">
                                            {{ $msg->sender_type === 'user' ? 'Anda' : 'Admin (' . ($msg->sender->name ?? 'Admin') . ')' }}
                                        </span>
                                        <span class="text-[10px] text-gray-500 font-bold">
                                            {{ $msg->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-sm font-medium whitespace-pre-wrap">{{ $msg->message }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </section>
    </main>

    @include('profile.partials.footer')
</body>
</html>
