<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Certisat'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('head')
    <style>
        body { font-family: 'Space Grotesk', sans-serif; }

        /* Neubrutalist utilities */
        .nb-shadow { box-shadow: 4px 4px 0px 0px #1a1a2e; }
        .nb-shadow-sm { box-shadow: 3px 3px 0px 0px #1a1a2e; }
        .nb-shadow-lg { box-shadow: 6px 6px 0px 0px #1a1a2e; }
        .nb-border { border: 3px solid #1a1a2e; }
        .nb-border-2 { border: 2px solid #1a1a2e; }

        .nb-card {
            background: #ffffff;
            border: 3px solid #1a1a2e;
            box-shadow: 4px 4px 0px 0px #1a1a2e;
        }

        .nb-btn {
            border: 3px solid #1a1a2e;
            box-shadow: 4px 4px 0px 0px #1a1a2e;
            transition: all 0.1s ease;
        }
        .nb-btn:hover {
            box-shadow: 2px 2px 0px 0px #1a1a2e;
            transform: translate(2px, 2px);
        }
        .nb-btn:active {
            box-shadow: 0px 0px 0px 0px #1a1a2e;
            transform: translate(4px, 4px);
        }

        /* SweetAlert2 Neubrutalist Override */
        .swal-nb {
            background: #ffffff !important;
            border: 3px solid #1a1a2e !important;
            box-shadow: 6px 6px 0px 0px #1a1a2e !important;
            border-radius: 16px !important;
        }
        .swal-nb .swal2-title { font-family: 'Space Grotesk', sans-serif !important; font-weight: 700 !important; color: #1a1a2e !important; }
        .swal-nb .swal2-html-container { font-family: 'Space Grotesk', sans-serif !important; color: #4b5563 !important; }
        .swal-btn-nb {
            border: 2px solid #1a1a2e !important;
            box-shadow: 3px 3px 0px 0px #1a1a2e !important;
            border-radius: 12px !important;
            font-family: 'Space Grotesk', sans-serif !important;
            font-weight: 700 !important;
            padding: 10px 28px !important;
            transition: all 0.1s !important;
        }
        .swal-btn-nb:hover {
            box-shadow: 1px 1px 0px 0px #1a1a2e !important;
            transform: translate(2px, 2px) !important;
        }

        /* Bottom nav active indicator */
        .bottom-nav-item.active .nav-indicator { opacity: 1; transform: scaleX(1); }
        .bottom-nav-item .nav-indicator { opacity: 0; transform: scaleX(0); transition: all 0.3s ease; }

        /* Grid pattern bg */
        .bg-grid {
            background-image:
                linear-gradient(rgba(26, 26, 46, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26, 26, 46, 0.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        @stack('styles')
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#fefbf4] bg-grid text-[#1a1a2e] antialiased overflow-x-hidden relative">

    <!-- Decorative shapes -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-yellow-300 rounded-full border-[3px] border-[#1a1a2e] opacity-20"></div>
        <div class="absolute top-1/3 -left-16 w-32 h-32 bg-blue-400 rounded-full border-[3px] border-[#1a1a2e] opacity-15"></div>
        <div class="absolute bottom-1/4 right-10 w-24 h-24 bg-pink-300 border-[3px] border-[#1a1a2e] opacity-15 rotate-12"></div>
    </div>

    <!-- Desktop Top Navbar (hidden on mobile) -->
    <nav class="hidden md:block fixed top-0 left-0 w-full z-40">
        <div class="px-4 mt-4 max-w-5xl mx-auto">
            <div class="nb-card rounded-2xl px-6 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/smk.png') }}" alt="Logo SMK" class="h-9">
                        <span class="font-bold text-[#1a1a2e] text-sm tracking-wide">SMK Informatika Pesat</span>
                    </div>
                    <div class="flex items-center gap-1">
                        @php $currentRoute = request()->route()?->getName(); @endphp
                        <a href="/"
                           class="px-4 py-2 text-sm font-bold rounded-xl transition-all {{ request()->is('/') ? 'bg-yellow-300 nb-border-2 nb-shadow-sm' : 'text-[#1a1a2e] hover:bg-yellow-100' }}">
                            Home
                        </a>
                        <a href="{{ route('pencarian.eligible') }}"
                           class="px-4 py-2 text-sm font-bold rounded-xl transition-all {{ $currentRoute === 'pencarian.eligible' ? 'bg-yellow-300 nb-border-2 nb-shadow-sm' : 'text-[#1a1a2e] hover:bg-yellow-100' }}">
                            Eligible PTN
                        </a>
                        <a href="{{ route('laporan.public.form') }}"
                           class="px-4 py-2 text-sm font-bold rounded-xl transition-all {{ $currentRoute === 'laporan.public.form' ? 'bg-yellow-300 nb-border-2 nb-shadow-sm' : 'text-[#1a1a2e] hover:bg-yellow-100' }}">
                            Laporan
                        </a>
                        {{-- <a href="{{ route('tim.profil') }}"
                           class="px-4 py-2 text-sm font-bold rounded-xl transition-all {{ $currentRoute === 'tim.profil' ? 'bg-yellow-300 nb-border-2 nb-shadow-sm' : 'text-[#1a1a2e] hover:bg-yellow-100' }}">
                            Tim
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 relative z-10 @yield('main-class', 'px-4 pb-24 md:pb-8 pt-8 md:pt-24')">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 px-3 pb-3 pt-2">
        <div class="nb-card rounded-2xl px-2 py-2">
            <div class="flex justify-around items-center">
                @php $currentRoute = request()->route()?->getName(); @endphp
                <a href="/" class="bottom-nav-item {{ request()->is('/') ? 'active' : '' }} flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->is('/') ? 'bg-yellow-300' : '' }}">
                    <svg class="w-5 h-5 text-[#1a1a2e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z" />
                    </svg>
                    <span class="text-[10px] font-bold text-[#1a1a2e]">Home</span>
                    <div class="nav-indicator w-1 h-1 rounded-full bg-[#1a1a2e]"></div>
                </a>
                <a href="{{ route('pencarian.eligible') }}" class="bottom-nav-item {{ $currentRoute === 'pencarian.eligible' ? 'active' : '' }} flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ $currentRoute === 'pencarian.eligible' ? 'bg-yellow-300' : '' }}">
                    <svg class="w-5 h-5 text-[#1a1a2e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-[10px] font-bold text-[#1a1a2e]">Eligible</span>
                    <div class="nav-indicator w-1 h-1 rounded-full bg-[#1a1a2e]"></div>
                </a>
                <a href="{{ route('laporan.public.form') }}" class="bottom-nav-item {{ $currentRoute === 'laporan.public.form' ? 'active' : '' }} flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ $currentRoute === 'laporan.public.form' ? 'bg-yellow-300' : '' }}">
                    <svg class="w-5 h-5 text-[#1a1a2e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span class="text-[10px] font-bold text-[#1a1a2e]">Laporan</span>
                    <div class="nav-indicator w-1 h-1 rounded-full bg-[#1a1a2e]"></div>
                </a>
                {{-- <a href="{{ route('tim.profil') }}" class="bottom-nav-item {{ $currentRoute === 'tim.profil' ? 'active' : '' }} flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ $currentRoute === 'tim.profil' ? 'bg-yellow-300' : '' }}">
                    <svg class="w-5 h-5 text-[#1a1a2e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-[10px] font-bold text-[#1a1a2e]">Tim</span>
                    <div class="nav-indicator w-1 h-1 rounded-full bg-[#1a1a2e]"></div>
                </a> --}}
            </div>
        </div>
    </nav>

    <!-- Desktop Footer -->
    <footer class="hidden md:block relative z-10 mt-4">
        <div class="nb-card rounded-t-2xl mx-4">
            <div class="max-w-5xl mx-auto px-6 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/smk.png') }}" alt="Logo SMK" class="h-8">
                        <span class="text-sm font-bold text-[#1a1a2e]">SMK Informatika Pesat</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="https://smkpesat.sch.id/" target="_blank" class="nb-btn w-9 h-9 rounded-xl bg-yellow-100 hover:bg-yellow-300 flex items-center justify-center transition-all">
                            <i class="fas fa-globe text-[#1a1a2e] text-sm"></i>
                        </a>
                        <a href="https://www.instagram.com/smkpesat_itxpro/" target="_blank" class="nb-btn w-9 h-9 rounded-xl bg-yellow-100 hover:bg-yellow-300 flex items-center justify-center transition-all">
                            <i class="fab fa-instagram text-[#1a1a2e] text-sm"></i>
                        </a>
                        <a href="https://www.facebook.com/people/SMK-Informatika-Pesat-It-XPro/100092495414821/" target="_blank" class="nb-btn w-9 h-9 rounded-xl bg-yellow-100 hover:bg-yellow-300 flex items-center justify-center transition-all">
                            <i class="fab fa-facebook text-[#1a1a2e] text-sm"></i>
                        </a>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} SMK Informatika Pesat</p>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
