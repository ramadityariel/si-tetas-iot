<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Si-Tetas Admin Dashboard')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
<body class="text-slate-800 dark:text-white bg-slate-50 dark:bg-slate-950 antialiased font-body transition-colors duration-500">

    <!-- Ambient Background Image -->
    <div class="fixed inset-0 z-0 pointer-events-none opacity-30 dark:opacity-100 transition-opacity duration-500">
        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Background" class="w-full h-full object-cover" />
    </div>
    <div class="fixed inset-0 bg-white/80 dark:bg-black/70 z-0 pointer-events-none transition-colors duration-500"></div>
    <!-- Ambient Light Blob -->
    <div class="fixed top-[-10%] right-[-5%] w-[500px] h-[500px] bg-sky-400/20 dark:bg-sky-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- SideNavBar -->
    <aside class="h-screen w-64 fixed left-0 top-0 bg-white/80 dark:bg-slate-900/40 backdrop-blur-xl border-r border-slate-200 dark:border-white/10 flex flex-col p-4 gap-2 z-50 transition-colors duration-500 shadow-xl">
        <div class="mb-8 px-2 flex items-center gap-3">
            <div class="w-10 h-10 bg-[#35627C] dark:bg-sky-500/20 border dark:border-sky-400/30 rounded-xl flex items-center justify-center text-white dark:text-sky-400 shadow-lg">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">egg</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#194A63] dark:text-white leading-none">{{ __('admin.sidebar.title') }}</h1>
                <p class="font-['Plus_Jakarta_Sans'] font-medium text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('admin.sidebar.subtitle') }}</p>
            </div>
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

    <!-- Main Content Area -->
    <main class="ml-64 min-h-screen flex flex-col relative z-10">
        
        <!-- TopNavBar -->
        <header class="sticky top-0 z-40 bg-white/50 dark:bg-slate-900/40 backdrop-blur-xl border-b border-slate-200 dark:border-white/10 flex justify-end items-center px-8 py-4 transition-all duration-300">
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
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden ring-2 ring-white dark:ring-slate-800 shadow-md">
                        <img alt="Admin Profile" class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=35627C&color=fff"/>
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

    <!-- Theme Control Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('admin-theme-toggle');
            const themeIcon = document.getElementById('admin-theme-icon');
            const htmlElement = document.documentElement;

            function updateIcons() {
                if (htmlElement.classList.contains('dark')) {
                    themeIcon.textContent = 'light_mode';
                    themeIcon.classList.add('text-amber-400');
                    themeIcon.classList.remove('text-slate-500');
                } else {
                    themeIcon.textContent = 'dark_mode';
                    themeIcon.classList.remove('text-amber-400');
                    themeIcon.classList.add('text-slate-500');
                }
            }
            updateIcons();

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    htmlElement.classList.toggle('dark');
                    updateIcons();
                    if (htmlElement.classList.contains('dark')) {
                        localStorage.setItem('theme', 'dark');
                    } else {
                        localStorage.setItem('theme', 'light');
                    }
                });
            }
        });
    </script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            showConfirmButton: true,
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
        });
    </script>
    @endif
    @if($errors->any())
    <script>
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
    </script>
    @endif
</body>
</html>
