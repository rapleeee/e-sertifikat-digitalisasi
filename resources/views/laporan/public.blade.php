@extends('layouts.glass')

@section('title', 'Laporan ke Admin - Certisat')

@section('main-class', 'px-4 pb-24 md:pb-8 pt-8 md:pt-24')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <section class="text-center space-y-3">
        <div class="inline-flex items-center gap-2 bg-blue-300 nb-border-2 nb-shadow-sm rounded-full px-4 py-1.5 text-xs font-bold text-[#1a1a2e] tracking-wide uppercase">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
            Laporan
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-[#1a1a2e] tracking-tight">Kirim Laporan ke Admin</h1>
        <p class="text-sm text-gray-500 max-w-lg mx-auto">
            Gunakan formulir ini untuk melaporkan kendala, koreksi data, atau saran perbaikan terkait sistem.
        </p>
    </section>

    @if(session('success'))
        <div class="nb-card bg-green-300 rounded-xl px-5 py-4 text-sm text-[#1a1a2e] font-bold flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Card -->
    <div class="nb-card rounded-2xl p-6 sm:p-8">
        <form action="{{ route('laporan.public.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#1a1a2e] mb-1.5 uppercase tracking-wide">Nama lengkap</label>
                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama') }}"
                        required
                        class="w-full nb-border-2 rounded-xl px-4 py-3 text-sm text-[#1a1a2e] bg-[#fefbf4] font-medium focus:ring-0 focus:border-blue-500 transition-all @error('nama') border-red-500 @enderror"
                    >
                    @error('nama')
                        <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#1a1a2e] mb-1.5 uppercase tracking-wide">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full nb-border-2 rounded-xl px-4 py-3 text-sm text-[#1a1a2e] bg-[#fefbf4] font-medium focus:ring-0 focus:border-blue-500 transition-all @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#1a1a2e] mb-1.5 uppercase tracking-wide">NIS (opsional)</label>
                    <input
                        type="text"
                        name="nis"
                        value="{{ old('nis') }}"
                        class="w-full nb-border-2 rounded-xl px-4 py-3 text-sm text-[#1a1a2e] bg-[#fefbf4] font-medium focus:ring-0 focus:border-blue-500 transition-all @error('nis') border-red-500 @enderror"
                    >
                    @error('nis')
                        <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#1a1a2e] mb-1.5 uppercase tracking-wide">Judul laporan (opsional)</label>
                    <input
                        type="text"
                        name="subject"
                        value="{{ old('subject') }}"
                        placeholder="Contoh: Koreksi data eligible"
                        class="w-full nb-border-2 rounded-xl px-4 py-3 text-sm text-[#1a1a2e] bg-[#fefbf4] font-medium focus:ring-0 focus:border-blue-500 transition-all placeholder-gray-400 @error('subject') border-red-500 @enderror"
                    >
                    @error('subject')
                        <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#1a1a2e] mb-1.5 uppercase tracking-wide">Isi laporan</label>
                <textarea
                    name="message"
                    rows="5"
                    required
                    class="w-full nb-border-2 rounded-xl px-4 py-3 text-sm text-[#1a1a2e] bg-[#fefbf4] font-medium focus:ring-0 focus:border-blue-500 transition-all resize-none placeholder-gray-400 @error('message') border-red-500 @enderror"
                    placeholder="Ceritakan masalah atau saran kamu secara jelas..."
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                <p class="text-xs text-gray-500 leading-relaxed font-medium">
                    Pastikan email aktif agar admin bisa mengirim balasan.
                </p>
                <button
                    type="submit"
                    class="nb-btn inline-flex items-center justify-center px-7 py-3 rounded-xl bg-blue-500 text-white text-sm font-bold tracking-wide"
                >
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
