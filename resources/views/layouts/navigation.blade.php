<nav
    x-data="{
        open: JSON.parse(localStorage.getItem('sidebarOpen') || 'true'),
        userMenuOpen: false,
        toggleSidebar() {
            this.open = !this.open;
            localStorage.setItem('sidebarOpen', this.open);
            window.dispatchEvent(new Event('sidebar-toggled'));
        }
    }"
    x-init="
        localStorage.setItem('sidebarOpen', open);
        window.dispatchEvent(new Event('sidebar-toggled'));
    "
    class="relative z-50"
>
    <!-- Sidebar -->
    <aside
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-0 left-0 h-screen w-64 bg-white z-50 text-gray-800 border-r border-gray-200 shadow-sm transform transition-transform duration-200 z-40"
    >
        <div class="flex flex-col h-full overflow-y-auto py-4 px-3">
            <!-- Simple Brand -->
            <div class="flex items-center justify-center h-14 mb-6">
                <div class="flex items-center space-x-3">
                   <img src="{{ asset('images/smk.png') }}" alt="Logo SMK" class="w-12 h-10">
                    <div x-show="open" x-transition class="overflow-hidden">
                        <p class="text-sm font-semibold text-gray-900">
                            Sertifikat Digital
                        </p>
                        <p class="text-xs text-gray-500">Management System</p>
                    </div>
                </div>
            </div>

            <!-- Menu Items -->
            <ul class="space-y-1 flex-grow">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                        <div class="w-8 h-8 flex items-center justify-center text-gray-500">
                            <ion-icon name="home-outline" class="w-5 h-5"></ion-icon>
                        </div>
                        <span x-show="open" x-transition>Beranda</span>
                    </a>
                </li>

                @if (auth()->user()->role == 'admin')
                    <!-- Section: Data -->
                    <li class="pt-3">
                        <p x-show="open" class="px-3 mb-1 text-[11px] font-semibold text-gray-400 tracking-wide uppercase">
                            Data
                        </p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('siswa.index') }}"
                                   class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                    <div class="w-8 h-8 flex items-center justify-center text-gray-500">
                                        <ion-icon name="people" class="w-5 h-5"></ion-icon>
                                    </div>
                                    <span x-show="open" x-transition>Data Siswa</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Section: Sertifikat -->
                    <li class="pt-3">
                        <p x-show="open" class="px-3 mb-1 text-[11px] font-semibold text-gray-400 tracking-wide uppercase">
                            Sertifikat
                        </p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('sertifikat.create') }}"
                                   class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                    <div class="w-8 h-8 flex items-center justify-center text-gray-500">
                                        <ion-icon name="add-circle-outline" class="w-5 h-5"></ion-icon>
                                    </div>
                                    <span x-show="open" x-transition>Tambah Sertifikat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sertifikat.import.form') }}"
                                   class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                    <div class="w-8 h-8 flex items-center justify-center text-gray-500">
                                        <ion-icon name="cloud-upload-outline" class="w-5 h-5"></ion-icon>
                                    </div>
                                    <span x-show="open" x-transition>Import Excel</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sertifikat.upload') }}"
                                   class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                    <div class="w-8 h-8 flex items-center justify-center text-gray-500">
                                        <ion-icon name="image-outline" class="w-5 h-5"></ion-icon>
                                    </div>
                                    <span x-show="open" x-transition>Upload Foto Sertifikat</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Section: Laporan -->
                    <li class="pt-3">
                        <p x-show="open" class="px-3 mb-1 text-[11px] font-semibold text-gray-400 tracking-wide uppercase">
                            Laporan
                        </p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('laporan.index') }}"
                                   class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                    <div class="w-8 h-8 flex items-center justify-center text-gray-500">
                                        <ion-icon name="chatbubbles-outline" class="w-5 h-5"></ion-icon>
                                    </div>
                                    <span x-show="open" x-transition>Laporan Masuk</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Section: Pengaturan -->
                    <li class="pt-3">
                        <p x-show="open" class="px-3 mb-1 text-[11px] font-semibold text-gray-400 tracking-wide uppercase">
                            Pengaturan
                        </p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('users.index') }}"
                                   class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                    <div class="w-8 h-8 flex items-center justify-center text-gray-500">
                                        <ion-icon name="people-circle-outline" class="w-5 h-5"></ion-icon>
                                    </div>
                                    <span x-show="open" x-transition>Manajemen User</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>

            <!-- Profile & Logout -->
            <div class="mt-4 border-t border-gray-200 pt-4">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                            :class="open ? 'w-full justify-start px-3 py-2' : 'w-10 h-10 justify-center mx-auto'"
                            class="flex items-center gap-2 rounded-lg text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <ion-icon name="log-out-outline" class="w-5 h-5"></ion-icon>
                        <span x-show="open" x-transition>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Top Navbar -->
    <header class="fixed inset-x-0 top-0 bg-white border-b border-gray-200 shadow-sm z-40">
        <div
            class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8"
            :style="open ? 'margin-left: 16rem' : 'margin-left: 0'"
        >
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    @click="toggleSidebar()"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors"
                >
                    <span class="sr-only">Toggle sidebar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h10M4 18h16" />
                    </svg>
                </button>
                <div>
                    <p class="text-sm font-semibold text-gray-900">
                        Dashboard Sertifikat Siswa
                    </p>
                    <p class="text-xs text-gray-500 hidden sm:block">
                        Panel administrasi sertifikat siswa
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 text-sm text-gray-500">
                <span id="navbar-time" class="font-medium whitespace-nowrap"></span>

                <!-- User menu -->
                <div class="relative hidden sm:flex items-center">
                    <button
                        type="button"
                        @click="userMenuOpen = !userMenuOpen"
                        @click.outside="userMenuOpen = false"
                        class="inline-flex items-center gap-2 px-2 py-1 rounded-full hover:bg-gray-100 transition-colors"
                    >
                        <div class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="text-left max-w-[9rem]">
                            <p class="text-sm font-medium text-gray-700 leading-tight truncate">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-gray-400 leading-tight capitalize">
                                {{ Auth::user()->role }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="userMenuOpen"
                        x-transition
                        x-cloak
                        class="absolute right-0 top-full mt-3 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-1 text-sm text-gray-700 z-50"
                    >
                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50"
                        >
                            <ion-icon name="settings-outline" class="w-4 h-4 text-gray-400"></ion-icon>
                            <span>Pengaturan profil</span>
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <p class="px-3 pt-1 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">
                            Role
                        </p>
                        <p class="px-3 pb-2 text-xs font-medium text-gray-700 capitalize">
                            {{ Auth::user()->role }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const timeElement = document.getElementById('navbar-time');
        if (!timeElement) return;

        const updateTime = () => {
            const now = new Date();
            const options = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            };
            timeElement.textContent = now.toLocaleString('id-ID', options);
        };

        updateTime();
        setInterval(updateTime, 60000);
    });
</script>

<style>
aside::-webkit-scrollbar {
    width: 4px;
}

aside::-webkit-scrollbar-track {
    background: transparent;
}

aside::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.4);
    border-radius: 2px;
}

aside::-webkit-scrollbar-thumb:hover {
    background: rgba(107, 114, 128, 0.7);
}
</style>
