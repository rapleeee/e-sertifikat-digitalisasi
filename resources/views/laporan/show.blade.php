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
        <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-10 max-w-5xl mx-auto space-y-6">
            <div class="flex items-center gap-3 text-sm text-slate-500">
                <a href="{{ route('laporan.index') }}" class="inline-flex items-center hover:text-orange-600 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke daftar laporan
                </a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Detail Laporan</span>
            </div>

            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-semibold text-slate-900">
                            {{ $laporan->subject ?? 'Laporan tanpa judul' }}
                        </h1>
                        <p class="text-xs text-slate-500 mt-1">
                            Dari {{ $laporan->nama }} ({{ $laporan->email }})
                            @if($laporan->nis)
                                · NIS: {{ $laporan->nis }}
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <form action="{{ route('laporan.update-status', $laporan) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $laporan->status === 'open' ? 'closed' : 'open' }}">
                            <button
                                type="submit"
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border {{ $laporan->status === 'open' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}"
                            >
                                @if($laporan->status === 'open')
                                    Tandai selesai
                                @else
                                    Buka kembali
                                @endif
                            </button>
                        </form>
                    </div>
                </div>

                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <div class="space-y-4 max-h-[480px] overflow-y-auto pr-1">
                            @foreach($laporan->messages as $message)
                                @php
                                    $isAdmin = $message->sender_type === 'admin';
                                @endphp
                                <div class="flex {{ $isAdmin ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[80%]">
                                        <div class="text-xs text-slate-400 mb-1 {{ $isAdmin ? 'text-right' : '' }}">
                                            @if($isAdmin)
                                                Admin {{ $message->sender?->name ?? '' }}
                                            @else
                                                {{ $laporan->nama }}
                                            @endif
                                            · {{ $message->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="{{ $isAdmin ? 'bg-orange-500 text-white' : 'bg-slate-100 text-slate-800' }} rounded-2xl px-3 py-2 text-sm shadow-sm">
                                            {!! nl2br(e($message->message)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form action="{{ route('laporan.reply', $laporan) }}" method="POST" class="mt-4 border-t border-slate-100 pt-4 space-y-2">
                            @csrf
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Balasan admin</label>
                            <textarea
                                name="message"
                                rows="3"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100 @error('message') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                                placeholder="Tulis balasan untuk laporan ini..."
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 transition"
                                >
                                    Kirim Balasan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-4 text-xs text-slate-600">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="font-semibold text-slate-700 mb-2">Ringkasan laporan</p>
                            <dl class="space-y-1.5">
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-500">Status</dt>
                                    <dd class="font-medium">
                                        {{ $laporan->status === 'closed' ? 'Selesai' : 'Terbuka' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-500">Dibuat</dt>
                                    <dd class="font-medium">{{ $laporan->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-500">Terakhir update</dt>
                                    <dd class="font-medium">{{ $laporan->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>

