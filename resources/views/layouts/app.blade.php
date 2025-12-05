<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#ffffff">

        <title>{{ config('app.name', 'Dashboard Admin') }}</title>

        <!-- Alpine.js -->
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Ionicons CDN -->
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const attachConfirm = (form) => {
                    if (form.dataset.swalBound === 'true') {
                        return;
                    }

                    form.dataset.swalBound = 'true';
                    const message = form.dataset.swalConfirm || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';

                    form.addEventListener('submit', function (event) {
                        if (form.dataset.swalConfirmed === 'true') {
                            return;
                        }

                        event.preventDefault();

                        Swal.fire({
                            title: 'Konfirmasi Tindakan',
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, lanjutkan',
                            cancelButtonText: 'Batal',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl px-4 py-2 font-semibold',
                                cancelButton: 'rounded-xl px-4 py-2 font-semibold',
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.dataset.swalConfirmed = 'true';
                                form.submit();
                            }
                        });
                    });
                };

                document.querySelectorAll('form[data-swal-confirm]').forEach(form => attachConfirm(form));

                document.addEventListener('swal:bind', (event) => {
                    const form = event.target;
                    if (form.matches && form.matches('form[data-swal-confirm]')) {
                        attachConfirm(form);
                    }
                }, true);
            });
        </script>
    </body>
</html>
