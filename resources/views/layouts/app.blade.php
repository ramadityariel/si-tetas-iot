<!DOCTYPE html>
<html class="scroll-smooth transition-colors duration-300" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Si-Tetas | Smart Incubator System')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Dark Mode Init: apply before render to prevent flash -->
    <script>
        (function() {
            const stored = localStorage.getItem('theme');
            if (stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>
<body class="bg-background dark:bg-slate-950 font-body text-on-background dark:text-slate-100 selection:bg-primary-container selection:text-on-primary-container transition-colors duration-300">

    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md shadow-[0_8px_24px_rgba(25,47,63,0.06)]">
        <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
            <!-- Brand Identity -->
            <div class="text-2xl font-black text-[#194A63] dark:text-sky-500 font-headline">
                Si-Tetas
            </div>
            
            <!-- Desktop Links -->
            <div class="hidden md:flex items-center gap-8 font-['Manrope'] font-bold tracking-tight">
                <a id="nav-beranda" class="{{ request()->is('/') ? 'text-[#194A63] font-bold border-b-2 border-[#194A63] pb-1' : 'text-slate-600 dark:text-slate-400 hover:text-[#194A63] transition-all' }}" href="{{ url('/') }}">Beranda</a>
                <a id="nav-blog" class="{{ request()->is('blog*') ? 'text-[#194A63] font-bold border-b-2 border-[#194A63] pb-1' : 'text-slate-600 dark:text-slate-400 hover:text-[#194A63] transition-all' }}" href="{{ url('/#artikel-terbaru') }}">Blog</a>
                <a id="nav-tentang" class="text-slate-600 dark:text-slate-400 hover:text-[#194A63] transition-all" href="{{ url('/#tentang-kami') }}">Tentang Kami</a>
            </div>
            
            <!-- Trailing Action -->
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="bg-[#35627C] text-white px-6 py-2.5 rounded-full font-bold hover:opacity-90 active:scale-95 transition-all shadow-lg inline-block">
                    Masuk
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-12 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex flex-col items-center md:items-start gap-4">
                <div class="font-black text-[#194A63] text-2xl font-headline">Si-Tetas</div>
                <p class="font-['Plus_Jakarta_Sans'] text-xs text-slate-500">© {{ date('Y') }} Si-Tetas Smart Incubator. Membina Kehidupan Digital.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-8 font-['Plus_Jakarta_Sans'] text-xs">
                <a class="text-slate-500 hover:text-[#715B36] underline decoration-[#715B36] transition-all" href="#">Kebijakan Privasi</a>
                <a class="text-slate-500 hover:text-[#715B36] underline decoration-[#715B36] transition-all" href="#">Syarat &amp; Ketentuan</a>
                <a class="text-slate-500 hover:text-[#715B36] underline decoration-[#715B36] transition-all" href="#">Hubungi Kami</a>
            </div>
            <div class="flex items-center gap-4">
                <a class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary hover:bg-primary-container hover:text-white transition-all" href="#">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path></svg>
                </a>
                <a class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary hover:bg-primary-container hover:text-white transition-all" href="#">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
                </a>
            </div>
        </div>
    </footer>

    <!-- ScrollSpy Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Only run scrollspy on the landing page
            if (window.location.pathname === '/' || window.location.pathname === '') {
                const sections = [
                    { id: 'beranda', navId: 'nav-beranda' },
                    { id: 'artikel-terbaru', navId: 'nav-blog' },
                    { id: 'tentang-kami', navId: 'nav-tentang' }
                ];
                
                function updateNav() {
                    let currentSection = sections[0].navId;
                    
                    for (const section of sections) {
                        const element = document.getElementById(section.id);
                        if (element) {
                            const rect = element.getBoundingClientRect();
                            if (rect.top <= 150) {
                                currentSection = section.navId;
                            }
                        }
                    }

                    sections.forEach(sec => {
                        const link = document.getElementById(sec.navId);
                        if (link) {
                            if (sec.navId === currentSection) {
                                link.className = 'text-[#194A63] font-bold border-b-2 border-[#194A63] pb-1';
                            } else {
                                link.className = 'text-slate-600 dark:text-slate-400 hover:text-[#194A63] transition-all';
                            }
                        }
                    });
                }

                window.addEventListener('scroll', updateNav);
                updateNav();
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
            timer: 2000
        });
    </script>
    @endif
</body>
</html>
