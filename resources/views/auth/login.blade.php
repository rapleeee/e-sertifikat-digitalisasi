<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <div class="mb-6">
                <a href="{{ url('/') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke beranda
                </a>
            </div>

            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl px-6 py-8 space-y-6">
                <div class="space-y-3 text-center">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-orange-100 text-orange-600 text-sm font-semibold">
                        {{ Str::of(config('app.name', 'Dashboard'))->substr(0, 2)->upper() }}
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Masuk ke Dashboard</h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Silakan masuk untuk mengelola data siswa dan sertifikat.
                        </p>
                    </div>
                </div>

                <!-- Status sesi -->
                <x-auth-session-status class="mb-3" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-slate-700" />
                        <x-text-input
                            id="email"
                            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-slate-700" />
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-orange-600 hover:text-orange-700">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="mt-1 relative">
                            <x-text-input
                                id="password"
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 pr-10 text-sm shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                            />
                            <button
                                type="button"
                                onclick="togglePassword('password')"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input
                                id="remember_me"
                                type="checkbox"
                                class="rounded border-slate-300 text-orange-600 shadow-sm focus:ring-orange-400"
                                name="remember"
                            >
                            <span class="ml-2 text-xs text-slate-600">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Tombol submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-lg bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-slate-50"
                        >
                            Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            var field = document.getElementById(fieldId);
            if (!field) return;
            field.type = field.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-guest-layout>
