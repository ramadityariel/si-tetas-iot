<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
</head>
<body class="text-on-surface bg-surface antialiased font-body">

    <!-- SideNavBar -->
    <aside class="h-screen w-64 fixed left-0 top-0 bg-[#f2f4f7] dark:bg-slate-950 flex flex-col p-4 gap-2 z-50">
        <div class="mb-8 px-2 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center text-white shadow-lg">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">egg</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#194A63] leading-none">Si-Tetas Admin</h1>
                <p class="font-['Plus_Jakarta_Sans'] font-medium text-xs text-slate-500 mt-1">Incubator System</p>
            </div>
        </div>
        
        <nav class="flex-1 space-y-1">
            <!-- Active: Dashboard -->
            <a class="{{ request()->routeIs('dashboard') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-transform hover:translate-x-1" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">Dashboard</span>
            </a>
            
            <a class="{{ request()->routeIs('monitoring') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-transform hover:translate-x-1" href="{{ route('monitoring') }}">
                <span class="material-symbols-outlined" data-icon="sensors">sensors</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">Monitoring IoT</span>
            </a>
            
            <a class="{{ request()->routeIs('prediksi') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-transform hover:translate-x-1" href="{{ route('prediksi') }}">
                <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">Prediksi &amp; Kamera</span>
            </a>
            
            @if(auth()->check() && auth()->user()->role === 'super_admin')
            <a class="{{ request()->routeIs('users.*') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-transform hover:translate-x-1" href="{{ route('users.index') }}">
                <span class="material-symbols-outlined" data-icon="group">group</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">Manajemen Pengguna</span>
            </a>
            @endif
            
            <a class="{{ request()->routeIs('admin.blog') ? 'bg-[#35627C] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800' }} rounded-xl flex items-center gap-3 px-4 py-3 transition-transform hover:translate-x-1" href="{{ route('admin.blog') }}">
                <span class="material-symbols-outlined" data-icon="article">article</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">Manajemen Blog</span>
            </a>
        </nav>
        
        <div class="pt-4 mt-auto border-t border-slate-200/50">
            <a class="text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl flex items-center gap-3 px-4 py-3 transition-transform hover:translate-x-1" href="{{ route('home') }}">
                <span class="material-symbols-outlined" data-icon="logout">logout</span>
                <span class="font-['Plus_Jakarta_Sans'] font-medium text-sm">Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="ml-64 min-h-screen flex flex-col">
        
        <!-- TopNavBar -->
        <header class="sticky top-0 z-40 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl flex justify-end items-center px-8 py-4 transition-opacity duration-300">
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="font-['Manrope'] text-sm font-semibold text-[#194A63]">Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-slate-500 font-medium">Terakhir login: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->translatedFormat('d M Y, H:i') . ' WIB' : now()->translatedFormat('d M Y, H:i') . ' WIB' }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden ring-2 ring-white shadow-sm">
                    <img alt="Admin Profile" class="w-full h-full object-cover" data-alt="professional portrait of a confident male system administrator in a modern office setting with soft natural lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC9Mypu3p_ttiw41cn2fDEoDPHLDXsRGTrA3U5bGSsILl1Ug3cY5O5pF0y1PAwe_IZVMa5G2-_jiP7iYRJZMCnnKcZOFzdbKYQ6qXGXtA-NVGLph1BWsOw7S09pvQ0Lxc17kZmmQX8dQ_O-F4gjzlGYioD_ACLzyMc96M2hfKBR4inl_MXl0gvRJAKTjzDHRJk1--xsv4qApDkbQdI2FkAWJ1jwAhUQ2QvvVgFcO6mSOKlZAeYc87xwnN8HXnrnOg9MrwgD4e3j5xo"/>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="flex-1">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="w-full py-8 mt-auto bg-white dark:bg-slate-950 flex flex-col md:flex-row justify-between items-center px-12 gap-4 border-t border-slate-100 dark:border-slate-800">
            <div class="font-['Plus_Jakarta_Sans'] text-xs text-slate-500">
                <span class="font-black text-[#194A63]">Si-Tetas</span> © {{ date('Y') }} Si-Tetas Smart Incubator. Membina Kehidupan Digital.
            </div>
            <div class="flex gap-6">
                <a class="font-['Plus_Jakarta_Sans'] text-xs text-slate-500 hover:text-[#715B36] transition-colors underline decoration-[#715B36]" href="#">Kebijakan Privasi</a>
                <a class="font-['Plus_Jakarta_Sans'] text-xs text-slate-500 hover:text-[#715B36] transition-colors underline decoration-[#715B36]" href="#">Syarat &amp; Ketentuan</a>
                <a class="font-['Plus_Jakarta_Sans'] text-xs text-slate-500 hover:text-[#715B36] transition-colors underline decoration-[#715B36]" href="#">Hubungi Kami</a>
            </div>
        </footer>
        
    </main>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            showConfirmButton: true
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
            showConfirmButton: true
        });
    </script>
    @endif
</body>
</html>
