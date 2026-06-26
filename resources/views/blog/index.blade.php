@extends('layouts.app')

@section('content')
<style>
    /* Sembunyikan navigasi bawaan dari layout.app khusus di halaman ini */
    body > nav.fixed { display: none !important; }
    body > main { padding-top: 0 !important; }
</style>

<!-- Navigation Minimalis (Responsif) -->
<nav id="blog-navbar" x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 w-full z-50 bg-[#1f4b62]/90 backdrop-blur-md shadow-md transition-all duration-300 border-b border-white/10">
    <div class="px-6 py-4 lg:px-16 flex justify-between items-center max-w-[1440px] mx-auto w-full">
        <!-- Logo -->
        <div class="text-3xl font-black text-white hover:scale-105 transition-transform" style="font-family: 'Brush Script MT', 'Dancing Script', cursive;">
            Si-Tetas
        </div>
        
        <!-- Center Links (Desktop Only) -->
        <div class="hidden lg:flex items-center gap-10 text-white/90 text-sm font-semibold tracking-wide">
            <a href="{{ url('/#beranda') }}" class="hover:text-sky-300 transition-colors relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-sky-400 hover:after:w-full after:transition-all">Home</a>
            <a href="{{ url('/#artikel-terbaru') }}" class="hover:text-sky-300 transition-colors relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-sky-400 hover:after:w-full after:transition-all">Blog</a>
            <a href="{{ url('/#demo-operasional') }}" class="hover:text-sky-300 transition-colors relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-sky-400 hover:after:w-full after:transition-all">Demo</a>
            <a href="{{ url('/#tentang-kami') }}" class="hover:text-sky-300 transition-colors relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-sky-400 hover:after:w-full after:transition-all">Tentang Kami</a>
        </div>
        
        <!-- Right: Toggles + Login (Desktop Only) -->
        <div class="hidden lg:flex items-center gap-4">
            <!-- Theme Toggle -->
            <button id="theme-toggle" class="theme-toggle w-8 h-8 flex items-center justify-center rounded-full bg-white/10 dark:bg-slate-800 text-white dark:text-sky-300 shadow-sm border border-white/20 dark:border-slate-700 hover:bg-white/20 dark:hover:bg-slate-700 transition-colors" title="Toggle Theme">
                <span id="theme-icon" class="theme-icon material-symbols-outlined text-[18px]">dark_mode</span>
            </button>

            <!-- Language Toggle Capsule -->
            <a href="{{ route('lang.switch', app()->getLocale() == 'id' ? 'en' : 'id') }}" class="relative flex items-center w-16 h-8 rounded-full bg-white/10 dark:bg-slate-700 shadow-inner border border-white/20 dark:border-slate-600 transition-colors overflow-hidden font-bold text-[10px]" title="Toggle Language">
                <div class="absolute w-1/2 h-full bg-white dark:bg-slate-600 rounded-full shadow transition-transform duration-300 {{ app()->getLocale() == 'id' ? 'translate-x-full' : 'translate-x-0' }}"></div>
                <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'en' ? 'text-[#194A63] dark:text-sky-400' : 'text-white/70 dark:text-slate-400' }}">EN</span>
                <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'id' ? 'text-[#194A63] dark:text-sky-400' : 'text-white/70 dark:text-slate-400' }}">ID</span>
            </a>

            <a href="{{ route('login') }}" class="border border-white/40 text-white px-6 py-2 rounded-sm text-sm font-bold tracking-wider hover:bg-white hover:text-[#194A63] transition-all">
                LOGIN
            </a>
        </div>

        <!-- Hamburger Menu (Mobile Only) -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-white p-2 rounded-md hover:bg-white/10 transition-colors focus:outline-none flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
        </button>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden absolute top-full left-0 w-full bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-b border-slate-200 dark:border-white/10 shadow-xl"
         style="display: none;">
        <div class="px-6 py-8 flex flex-col gap-6">
            <!-- Mobile Links -->
            <div class="flex flex-col gap-5 text-slate-800 dark:text-white font-semibold text-base">
                <a @click="mobileMenuOpen = false" href="{{ url('/#beranda') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors flex items-center gap-3"><span class="material-symbols-outlined text-[20px]">home</span> Home</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#artikel-terbaru') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors flex items-center gap-3"><span class="material-symbols-outlined text-[20px]">article</span> Blog</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#demo-operasional') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors flex items-center gap-3"><span class="material-symbols-outlined text-[20px]">play_circle</span> Demo</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#tentang-kami') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors flex items-center gap-3"><span class="material-symbols-outlined text-[20px]">groups</span> Tentang Kami</a>
            </div>
            
            <div class="h-[1px] w-full bg-slate-200 dark:bg-white/10"></div>
            
            <!-- Mobile Toggles & Actions -->
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">Theme</span>
                    <!-- Mobile Theme Toggle -->
                    <button class="theme-toggle w-9 h-9 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-sky-300 shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" title="Toggle Theme">
                        <span class="theme-icon material-symbols-outlined text-[20px]">dark_mode</span>
                    </button>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">Language</span>
                    <!-- Mobile Language Toggle Capsule -->
                    <a href="{{ route('lang.switch', app()->getLocale() == 'id' ? 'en' : 'id') }}" class="relative flex items-center w-[72px] h-9 rounded-full bg-slate-200 dark:bg-slate-700 shadow-inner border border-slate-300 dark:border-slate-600 transition-colors overflow-hidden font-bold text-[11px]" title="Toggle Language">
                        <div class="absolute w-1/2 h-full bg-white dark:bg-slate-600 rounded-full shadow transition-transform duration-300 {{ app()->getLocale() == 'id' ? 'translate-x-full' : 'translate-x-0' }}"></div>
                        <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'en' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">EN</span>
                        <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'id' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">ID</span>
                    </a>
                </div>
                
                <a @click="mobileMenuOpen = false" href="{{ route('login') }}" class="w-full text-center bg-[#194A63] dark:bg-white text-white dark:text-slate-900 px-6 py-3.5 rounded-xl text-sm font-bold tracking-wider hover:bg-sky-700 dark:hover:bg-sky-100 transition-all shadow-md mt-2">
                    LOGIN
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Terapkan dark mode sebelum render untuk menghindari flash
    (function() {
        const stored = localStorage.getItem('theme');
        if (stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    })();
</script>

<main class="pt-32 pb-20 px-6 max-w-7xl mx-auto">
<!-- Hero Header -->
<header class="mb-16 text-center">
<h1 class="font-display text-5xl font-extrabold text-[#194A63] dark:text-sky-300 tracking-tight mb-4">Pusat Artikel &amp; Panduan</h1>
<p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto text-lg">
                Temukan tips ahli, panduan teknis, dan berita terbaru seputar teknologi penetasan pintar untuk kesuksesan ternak Anda.
            </p>
<!-- Search Bar Desktop & Mobile -->
<div class="mt-8 max-w-xl mx-auto px-4">
<form action="{{ route('blog.index') }}" method="GET" class="flex items-center bg-white dark:bg-slate-800 shadow-md rounded-full px-5 py-3 border border-slate-200 dark:border-slate-700 focus-within:border-sky-500 transition-all">
<span class="material-symbols-outlined text-primary">search</span>
<input name="search" value="{{ request('search') }}" class="bg-transparent border-none focus:ring-0 text-base flex-1 ml-2 outline-none w-full text-slate-800 dark:text-white placeholder:text-slate-400" placeholder="Cari panduan..." type="text"/>
</form>
</div>
</header>
<!-- Blog Grid -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @if(isset($articles) && $articles->isEmpty())
        <div class="col-span-full text-center py-12 bg-surface-container-lowest rounded-lg shadow-ambient">
            <span class="material-symbols-outlined text-6xl text-primary/30 mb-4 block">article</span>
            <h3 class="text-xl font-bold text-primary">Belum ada artikel</h3>
            <p class="text-on-surface-variant mt-2">Silakan tambahkan artikel baru melalui dashboard admin.</p>
        </div>
    @elseif(isset($articles))
        @foreach($articles as $article)
        <a href="{{ route('blog.read', $article->slug) }}" class="block bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-lg p-4 shadow-md group cursor-pointer transition-all hover:-translate-y-1 hover:shadow-lg">
            <div class="relative overflow-hidden rounded-lg mb-5 aspect-[16/10]">
                @if($article->thumbnail)
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="{{ asset('storage/'.$article->thumbnail) }}" alt="{{ $article->title }}"/>
                @else
                    <div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl text-slate-400">image</span>
                    </div>
                @endif
            </div>
            <div class="px-2">
                <span class="text-sky-600 dark:text-sky-400 font-bold text-xs tracking-wider uppercase mb-2 block">{{ $article->category ?? 'PANDUAN' }}</span>
                <h3 class="font-display text-xl font-bold text-[#194A63] dark:text-white leading-snug mb-4 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">{{ $article->title }}</h3>
                @if($article->subtitle)
                    <p class="text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $article->subtitle }}</p>
                @endif
                <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500 dark:text-slate-400 text-sm">{{ $article->created_at ? $article->created_at->diffForHumans() : '' }}</span>
                    <div class="flex items-center text-sky-600 dark:text-sky-400 font-bold text-sm">
                        Baca Selengkapnya
                        <span class="material-symbols-outlined ml-1 text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    @endif
</section>
<!-- Pagination -->
<div class="flex justify-center items-center gap-2 mt-16">
<a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/30 text-primary hover:bg-primary/5 transition-all">
<span class="material-symbols-outlined">chevron_left</span>
</a>
<a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#194A63] text-white font-bold shadow-md shadow-primary/20">
        1
    </a>
<a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg text-on-surface-variant hover:bg-surface-container transition-all">
        2
    </a>
<a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg text-on-surface-variant hover:bg-surface-container transition-all">
        3
    </a>
<span class="w-10 h-10 flex items-center justify-center text-outline-variant">...</span>
<a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg text-on-surface-variant hover:bg-surface-container transition-all">
        5
    </a>
<a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/30 text-primary hover:bg-primary/5 transition-all">
<span class="material-symbols-outlined">chevron_right</span>
</a>
</div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggleBtns = document.querySelectorAll('.theme-toggle, #theme-toggle');
        const themeIcons = document.querySelectorAll('.theme-icon, #theme-icon');
        const htmlElement = document.documentElement;

        function updateThemeIcon() {
            const isDark = htmlElement.classList.contains('dark');
            themeIcons.forEach(icon => {
                if (isDark) {
                    icon.textContent = 'light_mode';
                    icon.classList.add('text-amber-400');
                    icon.classList.remove('text-slate-200', 'text-slate-500');
                } else {
                    icon.textContent = 'dark_mode';
                    icon.classList.remove('text-amber-400');
                    icon.classList.add('text-slate-200');
                }
            });
        }

        updateThemeIcon();

        themeToggleBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                htmlElement.classList.toggle('dark');
                updateThemeIcon();
                localStorage.setItem('theme', htmlElement.classList.contains('dark') ? 'dark' : 'light');
            });
        });
    });
</script>
@endsection
