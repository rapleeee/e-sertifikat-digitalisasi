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
                    <h1 class="text-2xl font-semibold text-gray-900">Manajemen Pengguna</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Atur role pengguna yang dapat mengakses panel administrasi sertifikat.
                    </p>
                </div>
            </header>

            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <p class="font-semibold mb-1">Form tambah pengguna belum lengkap:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <p class="text-sm font-medium text-slate-700">Tambah pengguna baru</p>
                    <p class="text-xs text-slate-500 mt-1">
                        Gunakan formulir ini untuk membuat akun admin atau perusahaan baru.
                    </p>
                </div>
                <div class="px-6 py-5">
                    <form action="{{ route('users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-200"
                                placeholder="Nama lengkap"
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-200"
                                placeholder="email@contoh.com"
                                required
                            >
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Role</label>
                                <select
                                    name="role"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-200"
                                    required
                                >
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih role</option>
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-200"
                                    placeholder="Min. 8 karakter"
                                    required
                                >
                            </div>
                        </div>
                        <div class="flex md:justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2.5 rounded-xl bg-orange-500 text-white text-sm font-semibold shadow-sm hover:bg-orange-600 transition"
                            >
                                Tambah Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <p class="text-sm font-medium text-slate-700">Daftar pengguna terdaftar</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold tracking-wider text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-3 text-sm text-slate-800">
                                        <div class="font-semibold">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500">ID: {{ $user->id }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-sm text-slate-700">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-slate-700">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium shadow-sm capitalize">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm">
                                        <form action="{{ route('users.update', $user) }}" method="POST" class="inline-flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select
                                                name="role"
                                                class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 focus:border-orange-500 focus:ring-1 focus:ring-orange-200"
                                            >
                                                @foreach($roles as $value => $label)
                                                    <option value="{{ $value }}" {{ $user->role === $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button
                                                type="submit"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-orange-500 text-white text-xs font-semibold shadow-sm hover:bg-orange-600 transition"
                                            >
                                                Simpan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-500 text-sm">
                                        Belum ada pengguna yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between text-xs text-slate-500">
                        <span>
                            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                        </span>
                        {{ $users->links('pagination.simple') }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-app-layout>
