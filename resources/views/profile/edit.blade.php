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
        <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-10 max-w-7xl mx-auto space-y-6">
            <header class="mb-4">
                <h2 class="text-2xl font-semibold text-gray-900">
                    Profil Akun
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola informasi akun dan keamanan login Anda.
                </p>
            </header>

            <div class="p-4 sm:p-6 bg-white shadow-sm rounded-xl border border-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white shadow-sm rounded-xl border border-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white shadow-sm rounded-xl border border-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
