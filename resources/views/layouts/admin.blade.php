<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Si-Tetas Admin Dashboard')</title>
    
    <!-- Preconnect untuk mempercepat koneksi ke Google Fonts & CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net"/>

    <!-- Google Fonts: swap agar teks langsung tampil tanpa menunggu font -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" media="print" onload="this.media='all'"/>
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet"/>
    </noscript>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 via CDN — defer agar tidak memblokir render -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js" defer></script>

    <script>
        // Set theme immediately to avoid flash
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        /* Fix Laravel Pagination in Dark Mode */
        html.dark nav[role="navigation"] .bg-white {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        html.dark nav[role="navigation"] .text-gray-500,
        html.dark nav[role="navigation"] .text-gray-700 {
            color: #94a3b8 !important; /* slate-400 */
        }
        html.dark nav[role="navigation"] a:hover.bg-white,
        html.dark nav[role="navigation"] a:hover .text-gray-500,
        html.dark nav[role="navigation"] a:hover .text-gray-700 {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #f8fafc !important; /* slate-50 */
        }
        html.dark nav[role="navigation"] .border-gray-300 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="text-slate-800 dark:text-white bg-slate-50 dark:bg-slate-950 antialiased font-body transition-colors duration-500 overflow-x-hidden">

    <!-- Page Loading Progress Bar -->
    <div id="page-progress" style="position:fixed;top:0;left:0;width:0%;height:3px;background:linear-gradient(90deg,#35627C,#38bdf8);z-index:9999;transition:width 0.3s ease;pointer-events:none;"></div>

    <!-- Ambient Background (pure CSS — no external image request) -->
    <div class="fixed inset-0 z-0 pointer-events-none" style="
        background: radial-gradient(ellipse at 80% 20%, rgba(56,189,248,0.12) 0%, transparent 50%),
                    radial-gradient(ellipse at 20% 80%, rgba(53,98,124,0.10) 0%, transparent 50%),
                    radial-gradient(ellipse at 50% 50%, rgba(148,163,184,0.05) 0%, transparent 60%);
    "></div>
    <div class="fixed inset-0 z-0 pointer-events-none dark:hidden" style="background:rgba(255,255,255,0.3);"></div>
    <div class="fixed inset-0 z-0 pointer-events-none hidden dark:block" style="background:rgba(0,0,0,0.4);"></div>
    <!-- Ambient Light Blob (CSS only) -->
    <div class="fixed top-[-10%] right-[-5%] w-[500px] h-[500px] bg-sky-400/20 dark:bg-sky-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- Sidebar Mobile Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition.opacity
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden"
         style="display: none;">
    </div>

    <!-- SideNavBar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="h-screen w-64 fixed left-0 top-0 bg-white/80 dark:bg-slate-900/40 backdrop-blur-xl border-r border-slate-200 dark:border-white/10 flex flex-col p-4 gap-2 z-50 transition-transform duration-300 shadow-xl lg:translate-x-0">
        <div class="mb-8 px-2 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#35627C] dark:bg-sky-500/20 border dark:border-sky-400/30 rounded-xl flex items-center justify-center text-white dark:text-sky-400 shadow-lg">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">egg</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-[#194A63] dark:text-white leading-none">{{ __('admin.sidebar.title') }}</h1>
                    <p class="font-['Plus_Jakarta_Sans'] font-medium text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('admin.sidebar.subtitle') }}</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white p-1 rounded-md">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <nav class="flex-1 space-y-1">
            <a class="{{ request()->routeIs('dashboard') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-all hover:translate-x-1" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">{{ __('admin.sidebar.dashboard') }}</span>
            </a>
            
            <a class="{{ request()->routeIs('monitoring') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-all hover:translate-x-1" href="{{ route('monitoring') }}">
                <span class="material-symbols-outlined" data-icon="sensors">sensors</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">{{ __('admin.sidebar.monitoring') }}</span>
            </a>
            
            <!-- Submenu for Logs & AI Monitoring -->
            <div class="space-y-0.5 ml-6">
                <a class="{{ request()->routeIs('sensor-logs') ? 'bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-white/10' }} rounded-lg flex items-center gap-2.5 px-3 py-2 transition-all hover:translate-x-1" href="{{ route('sensor-logs') }}">
                    <span class="material-symbols-outlined" style="font-size:16px" data-icon="description">description</span>
                    <span class="font-['Plus_Jakarta_Sans'] font-medium text-xs">{{ __('admin.sidebar.sensor_logs') }}</span>
                </a>
                <a class="{{ request()->routeIs('anomaly-logs') ? 'bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-white/10' }} rounded-lg flex items-center gap-2.5 px-3 py-2 transition-all hover:translate-x-1" href="{{ route('anomaly-logs') }}">
                    <span class="material-symbols-outlined" style="font-size:16px" data-icon="warning">warning</span>
                    <span class="font-['Plus_Jakarta_Sans'] font-medium text-xs">{{ __('admin.sidebar.anomaly_logs') }}</span>
                </a>

            </div>
            
            <a class="{{ request()->routeIs('settings.threshold') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-all hover:translate-x-1" href="{{ route('settings.threshold') }}">
                <span class="material-symbols-outlined" data-icon="tune">tune</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">Threshold Settings</span>
            </a>
            
            <a class="{{ request()->routeIs('prediksi') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-all hover:translate-x-1" href="{{ route('prediksi') }}">
                <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">{{ __('admin.sidebar.prediction') }}</span>
            </a>
            
            @if(auth()->check() && auth()->user()->role === 'super_admin')
            <a class="{{ request()->routeIs('users.*') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-all hover:translate-x-1" href="{{ route('users.index') }}">
                <span class="material-symbols-outlined" data-icon="group">group</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">{{ __('admin.sidebar.users') }}</span>
            </a>
            @endif
            
            <a class="{{ request()->routeIs('admin.blog') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-all hover:translate-x-1" href="{{ route('admin.blog') }}">
                <span class="material-symbols-outlined" data-icon="article">article</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">{{ __('admin.sidebar.blog') }}</span>
            </a>
        </nav>
        
        <div class="pt-4 mt-auto border-t border-slate-200 dark:border-white/10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-slate-600 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 rounded-xl flex items-center gap-3 px-4 py-3 transition-all hover:translate-x-1">
                    <span class="material-symbols-outlined" data-icon="logout">logout</span>
                    <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">{{ __('admin.sidebar.logout') }}</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Top Navbar -->
    <div class="lg:hidden fixed top-0 left-0 w-full z-30 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-white/10 px-4 h-16 flex items-center justify-between transition-colors duration-500 shadow-sm">
        <div class="flex items-center gap-2 sm:gap-3">
            <button @click="sidebarOpen = true" class="text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 p-1.5 sm:p-2 rounded-lg transition-colors flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">menu</span>
            </button>
            <h1 class="font-bold text-[#194A63] dark:text-white text-base sm:text-lg truncate max-w-[100px] sm:max-w-none">{{ __('admin.sidebar.title') }}</h1>
        </div>
        
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Mobile Language Toggle -->
            <a href="{{ route('lang.switch', app()->getLocale() == 'id' ? 'en' : 'id') }}" class="relative flex items-center w-12 h-7 sm:w-16 sm:h-8 rounded-full bg-slate-200 dark:bg-slate-700 shadow-inner border border-slate-300 dark:border-slate-600 transition-colors overflow-hidden font-bold text-[9px] sm:text-[10px]" title="Toggle Language">
                <div class="absolute w-1/2 h-full bg-white dark:bg-slate-600 rounded-full shadow transition-transform duration-300 {{ app()->getLocale() == 'id' ? 'translate-x-full' : 'translate-x-0' }}"></div>
                <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'en' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">EN</span>
                <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'id' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">ID</span>
            </a>
            
            <!-- Mobile Theme Toggle -->
            <button class="mobile-theme-toggle w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-white dark:bg-slate-800 text-slate-500 dark:text-sky-300 shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors" title="Toggle Theme">
                <span class="mobile-theme-icon material-symbols-outlined text-[16px] sm:text-[18px]">dark_mode</span>
            </button>

            <!-- User Profile -->
            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden ring-2 ring-white dark:ring-slate-800 shadow-md ml-1 sm:ml-2 shrink-0">
                <img alt="Admin Profile" class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=35627C&color=fff"/>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="pl-0 lg:pl-64 pt-16 lg:pt-0 min-h-screen flex flex-col relative z-10 transition-all duration-300">
        
        <!-- TopNavBar Desktop -->
        <header class="hidden lg:flex sticky top-0 z-40 bg-white/50 dark:bg-slate-900/40 backdrop-blur-xl border-b border-slate-200 dark:border-white/10 justify-end items-center px-8 py-4 transition-all duration-300">
            <div class="flex items-center gap-6">
                
                <!-- Action Controls -->
                <div class="flex items-center gap-3 border-r border-slate-200 dark:border-white/10 pr-6">
                    <!-- Theme Toggle Icon -->
                    <button id="admin-theme-toggle" class="w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-slate-800 text-slate-500 dark:text-sky-300 shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors" title="Toggle Theme">
                        <span id="admin-theme-icon" class="material-symbols-outlined text-[18px]">dark_mode</span>
                    </button>
                    
                    <!-- Language Toggle Capsule -->
                    <a href="{{ route('lang.switch', app()->getLocale() == 'id' ? 'en' : 'id') }}" class="relative flex items-center w-16 h-8 rounded-full bg-slate-200 dark:bg-slate-700 shadow-inner border border-slate-300 dark:border-slate-600 transition-colors overflow-hidden font-bold text-[10px]" title="Toggle Language">
                        <div class="absolute w-1/2 h-full bg-white dark:bg-slate-600 rounded-full shadow transition-transform duration-300 {{ app()->getLocale() == 'id' ? 'translate-x-full' : 'translate-x-0' }}"></div>
                        <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'en' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">EN</span>
                        <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'id' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">ID</span>
                    </a>
                </div>

                <!-- User Profile -->
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="font-['Manrope'] text-sm font-bold text-[#194A63] dark:text-white">{{ __('admin.topbar.welcome') }}, {{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">{{ __('admin.topbar.last_login') }}: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->translatedFormat('d M Y, H:i') : now()->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    @php
                        $avatarName = auth()->user()->name ?? 'Admin';
                        $initials = collect(explode(' ', $avatarName))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->join('');
                    @endphp
                    <div class="w-10 h-10 rounded-full bg-[#35627C] overflow-hidden ring-2 ring-white dark:ring-slate-800 shadow-md flex items-center justify-center" title="{{ $avatarName }}">
                        <span class="text-white font-bold text-sm select-none">{{ $initials }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="flex-1">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="w-full py-8 mt-auto bg-white/50 dark:bg-slate-900/30 backdrop-blur-md flex flex-col md:flex-row justify-between items-center px-12 gap-4 border-t border-slate-200 dark:border-white/10 transition-colors duration-500">
            <div class="font-['Plus_Jakarta_Sans'] text-xs text-slate-500 dark:text-slate-400">
                <span class="font-black text-[#194A63] dark:text-sky-400">Si-Tetas</span> © {{ date('Y') }} Si-Tetas Smart Incubator. {{ __('admin.footer.rights') }}
            </div>
            <div class="flex gap-6">
                <a class="font-['Plus_Jakarta_Sans'] text-xs text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-white transition-colors underline decoration-slate-300 dark:decoration-slate-700" href="#">{{ __('admin.footer.privacy') }}</a>
                <a class="font-['Plus_Jakarta_Sans'] text-xs text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-white transition-colors underline decoration-slate-300 dark:decoration-slate-700" href="#">{{ __('admin.footer.terms') }}</a>
                <a class="font-['Plus_Jakarta_Sans'] text-xs text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-white transition-colors underline decoration-slate-300 dark:decoration-slate-700" href="#">{{ __('admin.footer.contact') }}</a>
            </div>
        </footer>
        
    </main>

    <!-- Theme Control + Page Progress Bar Script -->
    <script>
        // Page progress bar — tampil segera saat link diklik
        (function() {
            const bar = document.getElementById('page-progress');
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript') || link.target === '_blank') return;
                if (bar) { bar.style.width = '70%'; bar.style.opacity = '1'; }
            });
            window.addEventListener('pageshow', function() {
                if (bar) { bar.style.width = '100%'; setTimeout(() => { bar.style.opacity = '0'; bar.style.width = '0%'; }, 300); }
            });
        })();

        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtns = document.querySelectorAll('#admin-theme-toggle, .mobile-theme-toggle');
            const themeIcons = document.querySelectorAll('#admin-theme-icon, .mobile-theme-icon');
            const htmlElement = document.documentElement;

            function updateIcons() {
                const isDark = htmlElement.classList.contains('dark');
                themeIcons.forEach(icon => {
                    if (isDark) {
                        icon.textContent = 'light_mode';
                        icon.classList.add('text-amber-400');
                        icon.classList.remove('text-slate-500');
                    } else {
                        icon.textContent = 'dark_mode';
                        icon.classList.remove('text-amber-400');
                        icon.classList.add('text-slate-500');
                    }
                });
            }
            updateIcons();

            themeToggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    htmlElement.classList.toggle('dark');
                    updateIcons();
                    localStorage.setItem('theme', htmlElement.classList.contains('dark') ? 'dark' : 'light');
                });
            });
        });
    </script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000,
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
            });
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
            });
        });
    </script>
    @endif
    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: `
                    <ul style="text-align: left;">
                        @foreach($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                showConfirmButton: true,
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
            });
        });
    </script>
    @endif
</body>
</html>

