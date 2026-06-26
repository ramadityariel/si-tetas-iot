@extends('layouts.app')

@php
    // <!-- UBAH LINK YOUTUBE DI VARIABEL DI BAWAH JIKA ADA VIDEO BARU -->
    $video_url = 'https://www.youtube.com/embed/qdGOl990uLQ';
    
    // <!-- ARRAY DATA TIM FINAL & STERIL DARI ERROR PARSE PHP -->
    $team = [
        ['name' => 'Ariel Pasha Ramaditya', 'role' => 'Software Engineer', 'image' => 'ariel.jpg', 'quote' => 'Membangun arsitektur web robust dan integrasi API IoT yang real-time.'],
        ['name' => 'Della', 'role' => 'Project Manager', 'image' => 'anggota1.jpg', 'quote' => 'Mengorkestrasi timeline pengembangan sistem agar rilis tepat waktu dan presisi.'],
        ['name' => 'Chris', 'role' => '3D Desain', 'image' => 'anggota2.jpg', 'quote' => 'Merancang pemodelan 3D mekanik inkubator dengan presisi tinggi.'],
        ['name' => 'Irdan Rifqy', 'role' => 'Hardware', 'image' => 'anggota3.jpg', 'quote' => 'Merakit skema sirkuit mikrokontroler dan kalibrasi akurasi sensor.'],
        ['name' => 'Setia Mega', 'role' => 'Hardware', 'image' => 'anggota4.jpg', 'quote' => 'Optimalisasi manajemen daya hardware dan kestabilan aktuator pemanas.'],
        ['name' => 'Fathir', 'role' => 'Machine Learning Engineer', 'image' => 'anggota5.jpg', 'quote' => 'Mengembangkan algoritma visi komputer untuk deteksi otomatis embrio telur.'],
        ['name' => 'Fadilla', 'role' => 'Hardware & Publication', 'image' => 'anggota6.jpg', 'quote' => 'Menjembatani validasi teknis perangkat dengan publikasi ilmiah yang kredibel.'],
        ['name' => 'Dinda', 'role' => 'Software Engineer & Video Editor', 'image' => 'anggota7.jpg', 'quote' => 'Mengembangkan logika front-end sekaligus mengemas visualisasi video dokumentasi proyek.']
    ]; 
@endphp

@section('content')
<!-- Tambahkan Assets CDN Swiper.js di Atas Style -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Style Utility Minimalis Khusus Landing Page -->
<style>
    /* Sembunyikan navigasi bawaan dari layout.app khusus di halaman utama ini */
    body > nav.fixed { display: none !important; }
    body > main { padding-top: 0 !important; }
    
    /* Atur kontainer Swiper agar efek hover scale tidak terpotong oleh overflow */
    .swiper {
        width: 100%;
        padding-top: 20px !important;
        padding-bottom: 50px !important;
        overflow: visible !important;
    }
    .swiper-wrapper {
        z-index: 10 !important;
    }

    /* =============================================
       STACKED SCROLL SECTIONS EFFECT
       Demo section jadi sticky, Tentang Kami naik
       menutupinya seperti kartu ditumpuk
    ============================================= */
    .stack-wrapper {
        position: relative;
    }

    /* Section Demo: sticky sehingga tertahan ketika di-scroll */
    #demo-operasional {
        position: sticky;
        top: 0;
        z-index: 1;
        /* Pastikan min-height cukup agar sticky bekerja */
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Section Tentang Kami: naik menutupi Demo dengan efek card */
    #tentang-kami {
        position: relative;
        z-index: 2;
        border-radius: 32px 32px 0 0;
        box-shadow: 0 -20px 60px rgba(0, 0, 0, 0.18);
        margin-top: -40px; /* Tumpang tindih sedikit dengan section di atas */
    }

    /* Dark mode shadow lebih gelap */
    .dark #tentang-kami {
        box-shadow: 0 -20px 80px rgba(0, 0, 0, 0.55);
    }

    /* Pastikan overflow hidden agar border-radius terlihat bersih */
    #tentang-kami {
        overflow: hidden;
    }
</style>

<!-- =========================================================================
     NAVIGATION BAR (TRANSPARENT TO SOLID BLUE ON SCROLL)
     ========================================================================= -->
<nav id="landing-navbar" x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 w-full z-50 bg-transparent transition-all duration-300">
    <div class="px-6 py-4 lg:px-16 flex justify-between items-center max-w-[1440px] mx-auto w-full">
        <!-- Brand Identity -->
        <div class="text-3xl font-black text-slate-900 dark:text-white transition-transform duration-300 hover:scale-105" style="font-family: 'Brush Script MT', 'Dancing Script', cursive;">
            Si-Tetas
        </div>
        
        <!-- Center Nav Links -->
        <div class="hidden lg:flex items-center gap-10 text-slate-800 dark:text-white/90 text-sm font-semibold tracking-wide">
            <a href="{{ url('/#beranda') }}" class="hover:text-sky-600 dark:hover:text-white transition-colors relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-sky-400 hover:after:w-full after:transition-all">{!! __('welcome.nav.home') !!}</a>
            <a href="{{ url('/#artikel-terbaru') }}" class="hover:text-sky-600 dark:hover:text-white transition-colors relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-sky-400 hover:after:w-full after:transition-all">{!! __('welcome.nav.blog') !!}</a>
            <a href="{{ url('/#demo-operasional') }}" class="hover:text-sky-600 dark:hover:text-white transition-colors relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-sky-400 hover:after:w-full after:transition-all">{!! __('welcome.nav.demo') !!}</a>
            <a href="{{ url('/#tentang-kami') }}" class="hover:text-sky-600 dark:hover:text-white transition-colors relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-sky-400 hover:after:w-full after:transition-all">{!! __('welcome.nav.about') !!}</a>
        </div>
        
        <!-- Call to Action Login Button & Toggles (Desktop Only) -->
        <div class="hidden lg:flex items-center gap-4">
            <!-- Theme Toggle Icon -->
            <button id="theme-toggle" class="theme-toggle w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-slate-800 text-slate-500 dark:text-sky-300 shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors" title="Toggle Theme">
                <span id="theme-icon" class="theme-icon material-symbols-outlined text-[18px]">dark_mode</span>
            </button>
            
            <!-- Language Toggle Capsule -->
            <a href="{{ route('lang.switch', app()->getLocale() == 'id' ? 'en' : 'id') }}" class="relative flex items-center w-16 h-8 rounded-full bg-slate-200 dark:bg-slate-700 shadow-inner border border-slate-300 dark:border-slate-600 transition-colors overflow-hidden font-bold text-[10px]" title="Toggle Language">
                <div class="absolute w-1/2 h-full bg-white dark:bg-slate-600 rounded-full shadow transition-transform duration-300 {{ app()->getLocale() == 'id' ? 'translate-x-full' : 'translate-x-0' }}"></div>
                <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'en' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">EN</span>
                <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'id' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">ID</span>
            </a>

            <a href="{{ route('login') }}" onclick="confirmLogin(event, this.href)" class="border border-slate-600 dark:border-white/40 text-slate-800 dark:text-white px-6 py-2 rounded-sm text-sm font-bold tracking-wider hover:bg-slate-800 hover:text-white dark:hover:bg-white dark:hover:text-black dark:hover:border-white transition-all">
                {!! __('welcome.nav.login') !!}
            </a>
        </div>

        <!-- Hamburger Menu (Mobile Only) -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-slate-800 dark:text-white p-2 rounded-md hover:bg-slate-200 dark:hover:bg-white/10 transition-colors focus:outline-none flex items-center justify-center">
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
                <a @click="mobileMenuOpen = false" href="{{ url('/#beranda') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors flex items-center gap-3"><span class="material-symbols-outlined text-[20px]">home</span> {!! __('welcome.nav.home') !!}</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#artikel-terbaru') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors flex items-center gap-3"><span class="material-symbols-outlined text-[20px]">article</span> {!! __('welcome.nav.blog') !!}</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#demo-operasional') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors flex items-center gap-3"><span class="material-symbols-outlined text-[20px]">play_circle</span> {!! __('welcome.nav.demo') !!}</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#tentang-kami') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors flex items-center gap-3"><span class="material-symbols-outlined text-[20px]">groups</span> {!! __('welcome.nav.about') !!}</a>
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
                
                <a @click="mobileMenuOpen = false" href="{{ route('login') }}" onclick="confirmLogin(event, this.href)" class="w-full text-center bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-6 py-3.5 rounded-xl text-sm font-bold tracking-wider hover:bg-[#35627C] dark:hover:bg-sky-100 transition-all shadow-md mt-2">
                    {!! __('welcome.nav.login') !!}
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- =========================================================================
     SECTION 1: HERO SECTION
     ========================================================================= -->
<section id="beranda" class="relative min-h-[100vh] flex flex-col overflow-hidden bg-slate-50 dark:bg-slate-900 transition-colors duration-500">
    <!-- Ambient Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Smart Incubator Background" class="w-full h-full object-cover opacity-30 dark:opacity-100 transition-opacity duration-500" />
        <div class="absolute inset-0 bg-white/80 dark:bg-black/70 transition-colors duration-500"></div>
    </div>

    <!-- Hero Core Content -->
    <div class="relative z-10 flex-1 flex flex-col justify-center px-6 lg:px-16 max-w-[1440px] mx-auto w-full pb-32 pt-32">
        <div class="max-w-3xl text-left">
            <p class="text-sky-600 dark:text-sky-400 font-bold tracking-widest uppercase mb-6 text-sm flex items-center gap-2">
                <span class="inline-block w-2 h-2 bg-sky-500 dark:bg-sky-400 rounded-full animate-pulse"></span>
                {!! __('welcome.hero.badge') !!}
            </p>
            <h1 class="text-5xl md:text-6xl lg:text-[5rem] font-serif text-slate-900 dark:text-white mb-8 leading-[1.1] font-light tracking-wide">
                {!! __('welcome.hero.title') !!}
            </h1>
            <p class="text-slate-700 dark:text-white/70 text-base md:text-lg mb-12 max-w-xl leading-relaxed font-light">
                {!! __('welcome.hero.desc') !!}
            </p>
            
            <div class="flex flex-col lg:flex-row gap-4 lg:gap-5 w-full sm:w-auto">
                <a href="{{ route('login') }}" onclick="confirmLogin(event, this.href)" class="text-center bg-[#35627C] hover:bg-[#194A63] text-white px-10 py-4 rounded-sm text-sm font-bold tracking-widest transition-all shadow-lg hover:shadow-[#35627C]/20 hover:-translate-y-0.5 uppercase">
                    {!! __('welcome.hero.btn_start') !!}
                </a>
                <a href="#demo-operasional" class="text-center border border-slate-600 dark:border-white/80 hover:bg-slate-800 hover:text-white dark:hover:bg-white dark:hover:text-black text-slate-800 dark:text-white px-10 py-4 rounded-sm text-sm font-bold tracking-widest transition-colors uppercase">
                    {!! __('welcome.hero.btn_demo') !!}
                </a>
            </div>
        </div>
    </div>

    <!-- Elemen Kurva Concave Transisi -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-10 translate-y-[2px]">
        <svg viewBox="0 0 1440 100" class="w-full h-[60px] md:h-[120px] fill-white dark:fill-slate-900 transition-colors duration-500" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,100 L0,0 C360,90 1080,90 1440,0 L1440,100 Z"></path>
        </svg>
    </div>
</section>

<!-- =========================================================================
     SECTION 2: ARTIKEL TERBARU
     ========================================================================= -->
<section id="artikel-terbaru" class="bg-white dark:bg-slate-900 py-24 relative overflow-hidden transition-colors duration-500">
    <!-- Ambient Light Effect -->
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-sky-200/40 dark:bg-sky-500/5 rounded-full blur-[100px] pointer-events-none transition-colors duration-500"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-wide uppercase">
                {!! __('welcome.blog.title') !!}
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-xl mx-auto">
                {!! __('welcome.blog.desc') !!}
            </p>
        </div>
        
        <!-- Grid System -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 items-stretch">
            
            @if(isset($articles) && $articles->isNotEmpty())
                @foreach($articles->take(7) as $article)
                <div class="bg-slate-50 dark:bg-white/5 backdrop-blur-md rounded-none border border-slate-200 dark:border-white/10 shadow-md dark:shadow-lg flex flex-col justify-between group hover:shadow-sky-500/10 dark:hover:shadow-sky-500/5 hover:bg-slate-100 dark:hover:bg-white/10 hover:-translate-y-1 transition-all duration-300">
                    
                    <div>
                        <div class="aspect-[4/3] w-full overflow-hidden bg-slate-200 dark:bg-slate-800">
                            @if($article->thumbnail)
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/'.$article->thumbnail) }}" alt="{{ $article->title }}"/>
                            @else
                                <div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-slate-400 dark:text-slate-600">image</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-5 pb-4">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white tracking-wide uppercase group-hover:text-sky-500 dark:group-hover:text-sky-400 transition-colors duration-200 line-clamp-2">
                                <a href="{{ route('blog.read', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-2 leading-relaxed font-light">
                                {{ \Illuminate\Support\Str::limit($article->subtitle ?? $article->content, 80) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="px-5 pb-4">
                        <div class="border-t border-dashed border-slate-300 dark:border-white/10 pt-3 flex justify-between items-center text-[11px] font-medium tracking-wider uppercase">
                            <span class="text-sky-600 dark:text-sky-400 font-bold">
                                {{ $article->category ?? 'Inkubasi' }}
                            </span>
                            <a href="{{ route('blog.read', $article->slug) }}" class="text-slate-500 dark:text-slate-300 font-extrabold hover:text-slate-800 dark:hover:text-white flex items-center gap-0.5">
                                {!! __('welcome.blog.read') !!} 
                                <span class="material-symbols-outlined text-xs">chevron_right</span>
                            </a>
                        </div>
                    </div>

                </div>
                @endforeach
            @else
                <div class="col-span-full md:col-span-3 bg-slate-50 dark:bg-white/5 backdrop-blur-md border border-slate-200 dark:border-white/10 p-12 text-center flex flex-col justify-center items-center">
                    <span class="material-symbols-outlined text-4xl text-slate-400 dark:text-slate-500 mb-2">draft</span>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{!! __('welcome.blog.empty') !!}</p>
                </div>
            @endif

            <!-- KARTU AKSI -->
            <div class="bg-slate-50 dark:bg-white/5 backdrop-blur-md border border-slate-200 dark:border-white/10 shadow-md dark:shadow-lg flex flex-col items-center justify-center p-8 text-center min-h-[320px]">
                <div class="text-slate-400 mb-4">
                    <svg class="w-10 h-10 text-sky-500 dark:text-sky-400/80" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25A2.25 2.25 0 0 1 13.5 8V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                </div>
                <h4 class="text-base font-black text-slate-800 dark:text-white uppercase tracking-widest mb-1">
                    {!! __('welcome.blog.search_title') !!}
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-6 max-w-[160px] mx-auto leading-relaxed font-light">
                    {!! __('welcome.blog.search_desc') !!}
                </p>
                <a href="{{ route('blog.index') }}" class="border border-slate-300 dark:border-white/20 text-slate-700 dark:text-slate-300 px-5 py-2.5 rounded-none text-xs font-bold tracking-wider hover:bg-slate-800 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 transition-all duration-300 uppercase">
                    {!! __('welcome.blog.btn_all') !!}
                </a>
            </div>

        </div>
    </div>
</section>

<!-- =========================================================================
     SECTION 3 + 4: STACKED SCROLL WRAPPER (Demo sticky, Tim naik di atasnya)
     ========================================================================= -->
<div class="stack-wrapper">

<!-- =========================================================================
     SECTION 3: DEMO TIM (sticky — tertahan saat section berikutnya naik)
     ========================================================================= -->
<section id="demo-operasional" class="py-24 relative overflow-hidden bg-slate-100 dark:bg-slate-900 transition-colors duration-500">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Technology Background" class="w-full h-full object-cover opacity-20 dark:opacity-100" />
        <div class="absolute inset-0 bg-white/90 dark:bg-[#0f2a3f]/90 transition-colors duration-500"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-8 mb-4 text-center">
        <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white drop-shadow-lg mb-4">{!! __('welcome.demo.title') !!}</h2>
        <p class="text-slate-700 dark:text-white/80 max-w-2xl mx-auto text-sm font-light">{!! __('welcome.demo.desc') !!}</p>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-8">
        <div class="swiper demoTimSwiper">
            <div class="swiper-wrapper">
                
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <div onclick="openVideoModal('https://www.youtube.com/embed/qdGOl990uLQ', '{!! __('welcome.demo.slide1_title') !!}')" class="w-full rounded-2xl shadow-xl border border-slate-200 dark:border-white/10 overflow-hidden backdrop-blur-md bg-white/40 dark:bg-white/5 aspect-video relative group cursor-pointer hover:border-sky-500 dark:hover:border-sky-400 hover:scale-105 transition-all duration-300 transform-gpu">
                        <div class="absolute top-3 left-3 z-10 bg-black/60 text-white px-3 py-1 rounded-full text-xs font-bold border border-white/20">{!! __('welcome.demo.slide1_badge') !!}</div>
                        <div class="absolute inset-0 bg-black/30 dark:bg-black/40 group-hover:bg-black/10 dark:group-hover:bg-black/20 transition-colors z-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-sky-500 rounded-full flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300"><span class="material-symbols-outlined text-2xl pl-1">play_arrow</span></div>
                        </div>
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover" alt="Video Thumbnail">
                        <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/80 to-transparent text-white"><p class="text-xs font-bold tracking-wide truncate">{!! __('welcome.demo.slide1_desc') !!}</p></div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <div onclick="openVideoModal('https://www.youtube.com/embed/qdGOl990uLQ', '{!! __('welcome.demo.slide2_title') !!}')" class="w-full rounded-2xl shadow-xl border border-slate-200 dark:border-white/10 overflow-hidden backdrop-blur-md bg-white/40 dark:bg-white/5 aspect-video relative group cursor-pointer hover:border-sky-500 dark:hover:border-sky-400 hover:scale-105 transition-all duration-300 transform-gpu">
                        <div class="absolute top-3 left-3 z-10 bg-black/60 text-white px-3 py-1 rounded-full text-xs font-bold border border-white/20">{!! __('welcome.demo.slide2_badge') !!}</div>
                        <div class="absolute inset-0 bg-black/30 dark:bg-black/40 group-hover:bg-black/10 dark:group-hover:bg-black/20 transition-colors z-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-sky-500 rounded-full flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300"><span class="material-symbols-outlined text-2xl pl-1">play_arrow</span></div>
                        </div>
                        <img src="https://images.unsplash.com/photo-1555664424-778a1e5e1b48?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover" alt="Video Thumbnail">
                        <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/80 to-transparent text-white"><p class="text-xs font-bold tracking-wide truncate">{!! __('welcome.demo.slide2_desc') !!}</p></div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <div onclick="openVideoModal('https://www.youtube.com/embed/qdGOl990uLQ', '{!! __('welcome.demo.slide3_title') !!}')" class="w-full rounded-2xl shadow-xl border border-slate-200 dark:border-white/10 overflow-hidden backdrop-blur-md bg-white/40 dark:bg-white/5 aspect-video relative group cursor-pointer hover:border-sky-500 dark:hover:border-sky-400 hover:scale-105 transition-all duration-300 transform-gpu">
                        <div class="absolute top-3 left-3 z-10 bg-black/60 text-white px-3 py-1 rounded-full text-xs font-bold border border-white/20">{!! __('welcome.demo.slide3_badge') !!}</div>
                        <div class="absolute inset-0 bg-black/30 dark:bg-black/40 group-hover:bg-black/10 dark:group-hover:bg-black/20 transition-colors z-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-sky-500 rounded-full flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300"><span class="material-symbols-outlined text-2xl pl-1">play_arrow</span></div>
                        </div>
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover" alt="Video Thumbnail">
                        <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/80 to-transparent text-white"><p class="text-xs font-bold tracking-wide truncate">{!! __('welcome.demo.slide3_desc') !!}</p></div>
                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="swiper-slide">
                    <div onclick="openVideoModal('https://www.youtube.com/embed/qdGOl990uLQ', '{!! __('welcome.demo.slide4_title') !!}')" class="w-full rounded-2xl shadow-xl border border-slate-200 dark:border-white/10 overflow-hidden backdrop-blur-md bg-white/40 dark:bg-white/5 aspect-video relative group cursor-pointer hover:border-sky-500 dark:hover:border-sky-400 hover:scale-105 transition-all duration-300 transform-gpu">
                        <div class="absolute top-3 left-3 z-10 bg-black/60 text-white px-3 py-1 rounded-full text-xs font-bold border border-white/20">{!! __('welcome.demo.slide4_badge') !!}</div>
                        <div class="absolute inset-0 bg-black/30 dark:bg-black/40 group-hover:bg-black/10 dark:group-hover:bg-black/20 transition-colors z-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-sky-500 rounded-full flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300"><span class="material-symbols-outlined text-2xl pl-1">play_arrow</span></div>
                        </div>
                        <img src="https://images.unsplash.com/photo-1506318137071-a8e063b4bec0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover" alt="Video Thumbnail">
                        <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/80 to-transparent text-white"><p class="text-xs font-bold tracking-wide truncate">{!! __('welcome.demo.slide4_desc') !!}</p></div>
                    </div>
                </div>

                <!-- Slide 5 -->
                <div class="swiper-slide">
                    <div onclick="openVideoModal('https://www.youtube.com/embed/qdGOl990uLQ', '{!! __('welcome.demo.slide5_title') !!}')" class="w-full rounded-2xl shadow-xl border border-slate-200 dark:border-white/10 overflow-hidden backdrop-blur-md bg-white/40 dark:bg-white/5 aspect-video relative group cursor-pointer hover:border-sky-500 dark:hover:border-sky-400 hover:scale-105 transition-all duration-300 transform-gpu">
                        <div class="absolute top-3 left-3 z-10 bg-black/60 text-white px-3 py-1 rounded-full text-xs font-bold border border-white/20">{!! __('welcome.demo.slide5_badge') !!}</div>
                        <div class="absolute inset-0 bg-black/30 dark:bg-black/40 group-hover:bg-black/10 dark:group-hover:bg-black/20 transition-colors z-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-sky-500 rounded-full flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300"><span class="material-symbols-outlined text-2xl pl-1">play_arrow</span></div>
                        </div>
                        <img src="https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover" alt="Video Thumbnail">
                        <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/80 to-transparent text-white"><p class="text-xs font-bold tracking-wide truncate">{!! __('welcome.demo.slide5_desc') !!}</p></div>
                    </div>
                </div>

            </div>
            <div class="swiper-pagination !bottom-0"></div>
        </div>
    </div>
</section>

<!-- =========================================================================
     SECTION 4: TENTANG KAMI (slide naik menutupi section Demo)
     ========================================================================= -->
<section id="tentang-kami" class="bg-white dark:bg-slate-900 py-24 relative transition-colors duration-500">
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-sky-200/50 dark:bg-sky-500/10 rounded-full blur-[120px] pointer-events-none transition-colors duration-500"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
        
        <div class="text-center mb-24">
            <h2 class="text-xs font-bold tracking-widest text-sky-600 dark:text-sky-400 uppercase">
                {!! __('welcome.team.badge') !!}
            </h2>
            <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white sm:text-4xl tracking-tight uppercase">
                {!! __('welcome.team.title') !!}
            </h3>
            <div class="mt-4 mx-auto w-16 h-1 bg-sky-500 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-20 max-w-6xl mx-auto">
            @foreach($team as $member)
                <div class="group relative flex flex-col items-center">
                    <div class="w-full bg-slate-50 dark:bg-white/5 backdrop-blur-md border border-slate-200 dark:border-white/10 rounded-xl px-6 pt-8 pb-12 text-center shadow-lg group-hover:shadow-sky-500/10 dark:group-hover:shadow-sky-500/10 hover:bg-slate-100 dark:group-hover:bg-white/10 group-hover:-translate-y-2 transition-all duration-300 ease-in-out min-h-[220px] flex flex-col justify-between relative z-10">
                        <p class="text-xs italic text-slate-600 dark:text-slate-300/80 leading-relaxed px-1 font-light">
                            "{{ $member['quote'] }}"
                        </p>
                        <div class="mt-4">
                            <h4 class="text-base font-extrabold text-slate-900 dark:text-white tracking-wide line-clamp-1 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors duration-200">
                                {{ $member['name'] }}
                            </h4>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 italic mt-0.5">
                                {{ $member['role'] }}
                            </p>
                        </div>
                    </div>
                    <div class="w-20 h-20 rounded-full bg-white dark:bg-slate-800 p-1 shadow-md -mt-10 relative z-20 group-hover:scale-105 transition-transform duration-300 ease-in-out border border-slate-200 dark:border-white/20">
                        <div class="w-full h-full rounded-full overflow-hidden bg-slate-200 dark:bg-slate-700">
                            <img class="w-full h-full object-cover" src="{{ asset('images/team/' . $member['image']) }}" alt="{{ $member['name'] }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"/>
                            <div class="hidden w-full h-full bg-gradient-to-br from-[#35627C] to-[#1f4b62] text-white font-bold text-lg items-center justify-center uppercase">
                                {{ substr($member['name'], 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

</div><!-- END .stack-wrapper -->

<!-- =========================================================================
     SECTION 5: CALL TO ACTION (CTA) CARD
     ========================================================================= -->
<section class="w-full bg-slate-50 dark:bg-slate-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-500">
    <div class="max-w-7xl mx-auto">
        <div class="bg-slate-900 rounded-2xl p-8 sm:p-12 text-center text-white relative overflow-hidden shadow-2xl border border-slate-200 dark:border-white/10 backdrop-blur-md">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/bg peternakan.png') }}" alt="Peternakan Background" class="w-full h-full object-cover opacity-80" onerror="this.src='https://images.unsplash.com/photo-1596568359553-9799b66bb159?q=80&w=2070&auto=format&fit=crop'">
                <div class="absolute inset-0 bg-gradient-to-br from-[#1f4b62]/75 to-[#112a38]/85 z-0"></div>
            </div>
            
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-cyan-500/20 rounded-full blur-[100px] pointer-events-none z-0"></div>
            <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-cyan-500/20 rounded-full blur-[100px] pointer-events-none z-0"></div>
            
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-6 relative z-10 drop-shadow-md tracking-wide">
                {!! __('welcome.cta.title') !!}
            </h2>
            <p class="text-white/80 max-w-xl mx-auto mb-10 text-sm sm:text-lg relative z-10 font-light leading-relaxed">
                {!! __('welcome.cta.desc') !!}
            </p>
            
            <a href="{{ url('/login') }}" onclick="confirmLogin(event, this.href)" class="bg-white text-[#1f4b62] px-10 py-4 rounded-full font-bold text-sm inline-block relative z-10 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:bg-slate-50 active:scale-[0.98]">
                {!! __('welcome.cta.btn') !!}
            </a>
        </div>
    </div>
</section> 

<!-- =========================================================================
     SECTION 6: KONTAK & MAPS
     ========================================================================= -->
<section id="kontak-lokasi" class="w-full bg-slate-100 dark:bg-slate-900 overflow-hidden transition-colors duration-500">
    <div class="flex flex-col md:flex-row w-full h-auto md:min-h-[550px]">
        <div class="w-full md:w-1/2 bg-[#1f4b62] p-12 md:p-24 flex flex-col justify-center text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-bl-full pointer-events-none"></div>
            
            <h2 class="text-4xl font-black mb-12 uppercase tracking-tighter">
                {!! __('welcome.contact.title') !!}
            </h2>
            
            <div class="space-y-6">
                <!-- Alamat Kampus Bogor -->
                <div>
                    <p class="font-light text-base opacity-90 leading-relaxed">
                        KAMPUS BOGOR – Jl. Kumbang No.14,<br>
                        Kelurahan Babakan, Kecamatan Bogor<br>
                        Tengah, Kota Bogor, Jawa Barat 16128
                    </p>
                </div>

                <!-- Alamat Kampus Sukabumi -->
                <div>
                    <p class="font-light text-base opacity-90 leading-relaxed">
                        KAMPUS SUKABUMI – Jl. Sarasa No.<br>
                        45, Babakan, Kec. Cibeureum, Kota<br>
                        Sukabumi, Jawa Barat 43142
                    </p>
                </div>


                <!-- Telepon -->
                <div class="flex items-center gap-4 group mb-6">
                    <span class="material-symbols-outlined text-white group-hover:text-sky-300 transition-colors">call</span>
                    <span class="font-light text-base opacity-90 transition-colors">
                        (0251) 8348007
                    </span>
                </div>

                <!-- Email -->
                <div class="flex items-center gap-4 group mb-6">
                    <span class="material-symbols-outlined text-white group-hover:text-sky-300 transition-colors">mail</span>
                    <a href="mailto:sv@apps.ipb.ac.id" class="font-light text-base opacity-90 hover:text-sky-300 hover:opacity-100 transition-colors">
                        sv@apps.ipb.ac.id
                    </a>
                </div>

                <!-- Website TNK -->
                <div class="flex items-center gap-4 group">
                    <span class="material-symbols-outlined text-white group-hover:text-sky-300 transition-colors">language</span>
                    <a href="https://sv.ipb.ac.id/teknologi-dan-manajemen-ternak/" target="_blank" rel="noopener noreferrer" class="font-light text-base opacity-90 hover:text-sky-300 hover:opacity-100 transition-colors">
                        Teknologi dan Manajemen Ternak
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 h-[450px] md:h-auto bg-slate-200 dark:bg-slate-800">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.435759795034!2d106.80410061477038!3d-6.580108395241477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c43232c45f47%3A0xc07a8fc964cd0517!2sSekolah%20Vokasi%20IPB%20University!5e0!3m2!1sen!2sid!4v1689578195843!5m2!1sen!2sid" 
                class="w-full h-full grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition-all duration-700" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- MODAL LIGHTBOX -->
<div id="videoLightboxModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 p-4 opacity-0 transition-opacity duration-300 ease-in-out backdrop-blur-sm">
    <button onclick="closeVideoModal()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors bg-white/10 p-2 rounded-full flex items-center justify-center">
        <span class="material-symbols-outlined text-3xl">close</span>
    </button>
    <div class="w-full max-w-4xl aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl border border-white/10 relative scale-95 transition-transform duration-300 ease-in-out" id="modalVideoContainer">
        <iframe id="lightboxIframe" class="w-full h-full" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('landing-navbar');
        
        // Theme Toggle Logic
        const themeToggleBtns = document.querySelectorAll('.theme-toggle, #theme-toggle');
        const themeIcons = document.querySelectorAll('.theme-icon, #theme-icon');
        const htmlElement = document.documentElement;
        
        function updateThemeIcon() {
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
        
        // Cek LocalStorage
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlElement.classList.add('dark');
        } else {
            htmlElement.classList.remove('dark');
        }
        updateThemeIcon();

        themeToggleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                htmlElement.classList.toggle('dark');
                updateThemeIcon();
                if (htmlElement.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
            });
        });

        // Pengendali Animasi Solid Navbar on Scroll
        function onScroll() {
            if (window.scrollY > 50) {
                navbar.classList.remove('bg-transparent');
                navbar.classList.add('bg-white/90', 'dark:bg-[#1f4b62]/90', 'backdrop-blur-md', 'shadow-md', 'border-b', 'border-slate-200', 'dark:border-white/10');
            } else {
                navbar.classList.remove('bg-white/90', 'dark:bg-[#1f4b62]/90', 'backdrop-blur-md', 'shadow-md', 'border-b', 'border-slate-200', 'dark:border-white/10');
                navbar.classList.add('bg-transparent');
            }
        }
        window.addEventListener('scroll', onScroll);
        onScroll();

        // Inisialisasi Carousel Swiper Demo Tim (Infinite Loop + Manual Grab)
        const swiper = new Swiper('.demoTimSwiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,             
            grabCursor: true,
            freeMode: true,       // Membuat geseran (swipe) benar-benar mengalir bebas
            mousewheel: {         // Memungkinkan pengguna scroll/swipe pakai touchpad laptop atau mouse wheel
                forceToAxis: true,
            },
            speed: 800,           // Membuat transisi slide lebih halus (800ms)
            touchRatio: 1.5,      // Membuat swiping lebih sensitif/responsif terhadap tarikan
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });
    });

    function openVideoModal(videoUrl, title) {
        const modal = document.getElementById('videoLightboxModal');
        const iframe = document.getElementById('lightboxIframe');
        const container = document.getElementById('modalVideoContainer');
        
        iframe.src = videoUrl + "?autoplay=1";
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            container.classList.remove('scale-95');
            container.classList.add('scale-100');
        }, 10);
    }

    function closeVideoModal() {
        const modal = document.getElementById('videoLightboxModal');
        const iframe = document.getElementById('lightboxIframe');
        const container = document.getElementById('modalVideoContainer');
        
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        container.classList.remove('scale-100');
        container.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            iframe.src = ""; 
        }, 300);
    }

    function confirmLogin(event, url) {
        event.preventDefault();
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin masuk ke halaman Login?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#35627C',
            cancelButtonColor: isDark ? '#475569' : '#94a3b8',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            background: isDark ? '#1e293b' : '#fff',
            color: isDark ? '#fff' : '#000'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>
@endsection