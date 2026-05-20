@extends('layouts.app')

@php
    // <!-- UBAH LINK YOUTUBE DI VARIABEL $video_url DI BAWAH -->
    $video_url = 'https://www.youtube.com/embed/qdGOl990uLQ';
    
    // <!-- UBAH NAMA DAN FOTO TIM DI ARRAY INI -->
    $team = [
        ['name' => 'Ariel Pasha Ramaditya', 'role' => 'Project Manager & IoT Engineer', 'image' => 'ariel.jpg'],
        ['name' => 'Della', 'role' => 'Hardware & Research', 'image' => 'anggota1.jpg'],
        ['name' => 'Chris', 'role' => 'UI/UX & Frontend', 'image' => 'anggota2.jpg'],
        ['name' => 'Cipung', 'role' => 'Main ML', 'image' => 'anggota3.jpg'],
        ['name' => 'Kenang', 'role' => 'Role 5', 'image' => 'anggota4.jpg'],
        ['name' => 'Fathir', 'role' => 'Role 6', 'image' => 'anggota5.jpg'],
        ['name' => 'Fadilla', 'role' => 'Role 7', 'image' => 'anggota6.jpg'],
        ['name' => 'Dinda', 'role' => 'Role 8', 'image' => 'anggota7.jpg']
    ];
@endphp

@section('content')
<!-- Hero Section -->
<style>
    /* Sembunyikan navigasi bawaan dari layout.app khusus di halaman ini */
    body > nav.fixed { display: none !important; }
    body > main { padding-top: 0 !important; }
</style>

<!-- Navigation Minimalis Khusus Landing Page -->
<nav id="landing-navbar" class="fixed top-0 left-0 w-full z-50 bg-transparent transition-all duration-300">
    <div class="px-6 py-4 lg:px-16 flex justify-between items-center max-w-[1440px] mx-auto w-full">
        <!-- Logo -->
        <div class="text-3xl font-black text-white" style="font-family: 'Brush Script MT', 'Dancing Script', cursive;">
            Si-Tetas
        </div>
        
        <!-- Center Links -->
        <div class="hidden md:flex items-center gap-10 text-white/90 text-sm font-semibold tracking-wide">
            <a href="{{ url('/#beranda') }}" class="hover:text-white transition-colors">Home</a>
            <a href="{{ url('/#artikel-terbaru') }}" class="hover:text-white transition-colors">Blog</a>
            <a href="{{ url('/#tentang-kami') }}" class="hover:text-white transition-colors">Tentang Kami</a>
            <a href="{{ url('/#demo-operasional') }}" class="hover:text-white transition-colors">Demo</a>
        </div>
        
        <!-- Right Button -->
        <div>
            <a href="{{ route('login') }}" class="border border-white/40 text-white px-6 py-2 rounded-sm text-sm font-bold tracking-wider hover:bg-white hover:text-black transition-all">
                LOGIN
            </a>
        </div>
    </div>
</nav>

<section id="beranda" class="relative min-h-[100vh] flex flex-col overflow-hidden bg-slate-900">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Smart Incubator Background" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 flex-1 flex flex-col justify-center px-6 lg:px-16 max-w-[1440px] mx-auto w-full pb-32 pt-32">
        <div class="max-w-3xl text-left">
            <p class="text-sky-400 font-bold tracking-widest uppercase mb-6 text-sm">
                Sistem Inkubator Cerdas, AI & IoT
            </p>
            <h1 class="text-5xl md:text-6xl lg:text-[5rem] font-serif text-white mb-8 leading-[1.1] font-light tracking-wide">
                Si-Tetas Smart<br>Incubator System.
            </h1>
            <p class="text-white/70 text-base md:text-lg mb-12 max-w-xl leading-relaxed font-light">
                Tingkatkan efisiensi penetasan dengan pemantauan suhu, kelembaban, dan rotasi telur otomatis secara real-time dari genggaman tangan Anda.
            </p>
            
            <div class="flex flex-wrap gap-5">
                <a href="{{ route('login') }}" class="bg-[#35627C] hover:bg-[#194A63] text-white px-10 py-4 rounded-sm text-sm font-bold tracking-widest transition-colors shadow-lg uppercase">
                    Mulai Sekarang
                </a>
                <a href="#demo-operasional" class="border border-white/80 hover:bg-white hover:text-black text-white px-10 py-4 rounded-sm text-sm font-bold tracking-widest transition-colors uppercase">
                    Lihat Demo
                </a>
            </div>
        </div>
    </div>

    <!-- Concave Wave / Curve -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-10 translate-y-[2px]">
        <svg viewBox="0 0 1440 100" class="w-full h-[60px] md:h-[120px] fill-surface-container-low" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,100 L0,0 C360,90 1080,90 1440,0 L1440,100 Z"></path>
        </svg>
    </div>
</section>

<!-- Blog Section (Bento Grid Style) -->
<section id="artikel-terbaru" class="bg-surface-container-low py-24">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div>
                <h2 class="text-3xl font-extrabold font-headline text-primary mb-2">Artikel Terbaru</h2>
                <p class="text-on-surface-variant">Panduan dan tips terbaik untuk manajemen inkubasi modern.</p>
            </div>
            <a class="text-primary font-bold flex items-center gap-1 hover:gap-3 transition-all" href="{{ route('blog.index') }}">
                Lihat Semua Artikel
                <span class="material-symbols-outlined">chevron_right</span>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($articles) && $articles->isNotEmpty())
                @foreach($articles as $article)
                <div class="bg-surface-container-lowest rounded-lg p-4 shadow-sm hover:shadow-xl transition-all group">
                    <div class="aspect-video mb-6 overflow-hidden rounded-lg">
                        @if($article->thumbnail)
                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="{{ asset('storage/'.$article->thumbnail) }}" alt="{{ $article->title }}"/>
                        @else
                            <div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-slate-400">image</span>
                            </div>
                        @endif
                    </div>
                    <div class="px-2">
                        <span class="text-xs font-bold text-secondary uppercase tracking-widest">{{ $article->category ?? 'Artikel' }}</span>
                        <h3 class="text-xl font-bold font-headline mt-2 mb-4 text-primary leading-snug">{{ $article->title }}</h3>
                        <p class="text-sm text-on-surface-variant mb-4">{{ \Illuminate\Support\Str::limit($article->subtitle ?? $article->content, 100) }}</p>
                        <a href="{{ route('blog.read', $article->slug) }}" class="text-[#715B36] font-bold text-sm flex items-center gap-2 hover:underline decoration-[#715B36]">
                            Baca Selengkapnya
                            <span class="material-symbols-outlined text-base">open_in_new</span>
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-8">
                    <p class="text-on-surface-variant">Belum ada artikel terbaru.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Video Demo Section -->
<section id="demo-operasional" class="py-24 relative overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Technology Background" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-[#0f2a3f]/85"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-8 mb-12 text-center">
        <h2 class="text-4xl font-extrabold font-headline text-white drop-shadow-lg mb-4">Demo Operasional</h2>
        <p class="text-white/80 max-w-2xl mx-auto drop-shadow-md">Geser kartu di bawah ini untuk menjelajahi simulasi dan arsitektur sistem cerdas Si-Tetas.</p>
    </div>

    <style>
        /* Sembunyikan scrollbar bawaan browser */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <!-- Container Utama (Scrollable) -->
    <div class="relative z-10 flex gap-6 overflow-x-auto snap-x snap-mandatory pb-10 pt-4 px-4 lg:px-[calc((100vw-80rem)/2)] hide-scroll">
        
        <!-- Spacer Kiri -->
        <div class="shrink-0 w-2 md:w-8"></div>

        <!-- Slide 1: Simulasi Operasional (Video) -->
        <div class="snap-center shrink-0 w-[85%] md:w-[60%] rounded-3xl shadow-2xl border border-white/10 overflow-hidden backdrop-blur-md bg-white/5 aspect-video relative group">
            <div class="absolute top-4 left-4 z-10 bg-black/60 text-white px-4 py-2 rounded-full text-sm font-bold backdrop-blur-md pointer-events-none drop-shadow-md border border-white/20">
                🎬 Video Demo
            </div>
            <iframe class="absolute top-0 left-0 w-full h-full z-0" src="{{ $video_url }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <!-- Slide 2: Perangkat Keras IoT -->
        <div class="snap-center shrink-0 w-[85%] md:w-[60%] rounded-3xl shadow-2xl border border-white/10 overflow-hidden backdrop-blur-md bg-white/5 aspect-video relative flex flex-col justify-end group">
             <!-- Label Kiri Atas -->
             <div class="absolute top-4 left-4 z-20 bg-black/60 text-white px-4 py-2 rounded-full text-sm font-bold backdrop-blur-md pointer-events-none drop-shadow-md border border-white/20">
                 ⚙️ Perangkat Keras IoT
             </div>
             
             <!-- Gambar Ilustrasi Hardware -->
             <img src="https://images.unsplash.com/photo-1555664424-778a1e5e1b48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" alt="Hardware Setup" class="absolute inset-0 w-full h-full object-cover z-0 group-hover:scale-105 transition-transform duration-700" />
             
             <!-- Dark Overlay Tipis -->
             <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-black/10 z-10"></div>
             
             <div class="relative z-20 p-6 md:p-10">
                 <h3 class="text-2xl md:text-3xl font-bold font-headline text-white mb-3 drop-shadow-md">Arsitektur Hardware</h3>
                 <p class="text-white/90 leading-relaxed text-sm md:text-base drop-shadow-sm max-w-2xl">
                     Sistem ditenagai oleh Sensor DHT22 untuk presisi suhu & kelembaban, modul kamera ESP32-CAM untuk visual internal, serta NodeMCU ESP8266 sebagai mikrokontroler pengatur rotasi rak telur dan transmisi IoT.
                 </p>
             </div>
        </div>

        <!-- Slide 3: Dashboard & Prediksi AI -->
        <div class="snap-center shrink-0 w-[85%] md:w-[60%] rounded-3xl shadow-2xl border border-white/10 overflow-hidden backdrop-blur-md bg-white/5 aspect-video relative flex flex-col justify-end group">
             <!-- Gambar Screenshot Dashboard -->
             <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" alt="Dashboard Interface" class="absolute inset-0 w-full h-full object-cover z-0 group-hover:scale-105 transition-transform duration-700" />
             
             <!-- Dark Overlay Tipis -->
             <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-black/10 z-10"></div>
             
             <div class="relative z-20 p-6 md:p-10">
                 <h3 class="text-2xl md:text-3xl font-bold font-headline text-white mb-3 drop-shadow-md">📊 Antarmuka & Analisis Cerdas</h3>
                 <p class="text-white/90 leading-relaxed text-sm md:text-base drop-shadow-sm max-w-2xl">
                     Pantau grafik suhu, kelembaban, dan status rotasi secara real-time melalui web. Terintegrasi dengan tangkapan visual deteksi AI yang memonitor perkembangan embrio di dalam telur secara otomatis.
                 </p>
             </div>
        </div>

        <!-- Spacer Kanan -->
        <div class="shrink-0 w-4 md:w-12"></div>
    </div>
</section>

<!-- About Us Section -->
<section id="tentang-kami" class="max-w-7xl mx-auto px-8 py-24">
    <div class="text-center mb-16">
        <h2 class="text-4xl font-extrabold font-headline text-primary mb-4">Tim Kami</h2>
        <p class="text-on-surface-variant max-w-2xl mx-auto">Para ahli di balik pengembangan Si-Tetas yang berdedikasi untuk memajukan peternakan digital.</p>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-16 gap-x-8 max-w-6xl mx-auto">
        @foreach($team as $member)
        <div class="flex flex-col items-center text-center space-y-4">
            <div class="w-32 h-32 rounded-full overflow-hidden p-1 bg-gradient-to-tr from-primary to-secondary">
                <img class="w-full h-full object-cover rounded-full border-4 border-white" src="{{ asset('images/' . $member['image']) }}" alt="{{ $member['name'] }}"/>
            </div>
            <div>
                <h4 class="font-bold text-lg font-headline text-primary">{{ $member['name'] }}</h4>
                <p class="text-sm text-secondary font-medium">{{ $member['role'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- CTA Section -->
<section class="max-w-7xl mx-auto px-8 py-20">
    <div class="bg-gradient-to-br from-[#1f4b62] to-[#112a38] rounded-lg p-12 text-center text-white relative overflow-hidden">
        <!-- Efek Glow Kiri Atas -->
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-cyan-500/20 rounded-full blur-[100px] pointer-events-none"></div>
        <!-- Efek Glow Kanan Bawah -->
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-cyan-500/20 rounded-full blur-[100px] pointer-events-none"></div>
        
        <h2 class="text-4xl font-extrabold font-headline mb-6 relative z-10 drop-shadow-md">Siap untuk Memulai Era Baru Penetasan?</h2>
        <p class="text-white/80 max-w-xl mx-auto mb-10 text-lg relative z-10">
            Dapatkan akses ke dashboard monitoring paling canggih dan optimalkan hasil penetasan Anda sekarang juga.
        </p>
        
        <a href="{{ route('login') }}" class="bg-secondary-container text-on-secondary-container px-10 py-4 rounded-full font-bold text-lg inline-block relative z-10 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_20px_rgba(226,184,139,0.4)]">
            Masuk Sekarang
        </a>
    </div>
</section>

<!-- Contact & Location Section -->
<section id="kontak-lokasi" class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-stretch">
            <!-- Kolom Kiri: Info Kontak -->
            <div class="bg-[#1f4b62] text-white rounded-xl shadow-lg p-8 md:p-10 flex flex-col justify-between">
                <div>
                    <h2 class="text-2xl font-bold font-headline mb-8">Hubungi Kami</h2>
                    
                    <div class="flex flex-col space-y-6">
                        <!-- Alamat -->
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <p class="leading-relaxed text-white/90">
                                Program Studi Teknologi Rekayasa Komputer (TNK)<br>
                                Sekolah Vokasi IPB University<br>
                                Kota Bogor, Jawa Barat
                            </p>
                        </div>
                        
                        <!-- Telepon -->
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <p class="text-white/90 leading-relaxed">+62 812-3456-7890</p>
                        </div>
                        
                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <a href="mailto:tnk@apps.ipb.ac.id" class="text-white/90 hover:text-cyan-400 transition-colors leading-relaxed">tnk@apps.ipb.ac.id</a>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="flex items-center gap-5 mt-10 pt-8 border-t border-white/10">
                    <!-- X / Twitter -->
                    <a href="#" class="text-white/80 hover:text-cyan-400 transition-colors" aria-label="Twitter">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <!-- Facebook -->
                    <a href="#" class="text-white/80 hover:text-cyan-400 transition-colors" aria-label="Facebook">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </a>
                    <!-- Instagram -->
                    <a href="#" class="text-white/80 hover:text-cyan-400 transition-colors" aria-label="Instagram">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
                    </a>
                    <!-- LinkedIn -->
                    <a href="#" class="text-white/80 hover:text-cyan-400 transition-colors" aria-label="LinkedIn">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
                    </a>
                    <!-- YouTube -->
                    <a href="#" class="text-white/80 hover:text-cyan-400 transition-colors" aria-label="YouTube">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Google Maps -->
            <div class="h-full min-h-[350px] w-full">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.435759795034!2d106.80410061477038!3d-6.580108395241477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c43232c45f47%3A0xc07a8fc964cd0517!2sSekolah%20Vokasi%20IPB%20University!5e0!3m2!1sen!2sid!4v1689578195843!5m2!1sen!2sid" 
                    class="w-full h-full rounded-xl shadow-lg grayscale hover:grayscale-0 transition-all duration-500" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('landing-navbar');
        
        function onScroll() {
            if (window.scrollY > 50) {
                navbar.classList.remove('bg-transparent');
                navbar.classList.add('bg-[#1f4b62]/80', 'backdrop-blur-md', 'shadow-sm');
            } else {
                navbar.classList.add('bg-transparent');
                navbar.classList.remove('bg-[#1f4b62]/80', 'backdrop-blur-md', 'shadow-sm');
            }
        }
        
        window.addEventListener('scroll', onScroll);
        onScroll(); // Inisialisasi saat load
    });
</script>
@endsection
