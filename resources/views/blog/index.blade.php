@extends('layouts.app')

@section('content')
<style>
    /* Sembunyikan navigasi bawaan dari layout.app khusus di halaman ini */
    body > nav.fixed { display: none !important; }
    body > main { padding-top: 0 !important; }
</style>

<!-- Navigation Minimalis -->
<nav class="fixed top-0 left-0 w-full z-50 bg-[#1f4b62]/80 backdrop-blur-md shadow-sm transition-all duration-300">
    <div class="px-6 py-4 lg:px-16 flex justify-between items-center max-w-[1440px] mx-auto w-full">
        <!-- Logo -->
        <div class="text-3xl font-black text-white" style="font-family: 'Brush Script MT', 'Dancing Script', cursive;">
            Si-Tetas
        </div>
        
        <!-- Center Links -->
        <div class="hidden md:flex items-center gap-10 text-white/90 text-sm font-semibold tracking-wide">
            <a href="{{ url('/#beranda') }}" class="hover:text-white transition-colors">Home</a>
            <a href="{{ route('blog.index') }}" class="hover:text-white transition-colors text-white">Blog</a>
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

<main class="pt-32 pb-20 px-6 max-w-7xl mx-auto">
<!-- Hero Header -->
<header class="mb-16 text-center">
<h1 class="font-display text-5xl font-extrabold text-primary tracking-tight mb-4">Pusat Artikel &amp; Panduan</h1>
<p class="text-on-surface-variant max-w-2xl mx-auto text-lg">
                Temukan tips ahli, panduan teknis, dan berita terbaru seputar teknologi penetasan pintar untuk kesuksesan ternak Anda.
            </p>
<!-- Search Bar Desktop & Mobile -->
<div class="mt-8 max-w-xl mx-auto px-4">
<form action="{{ route('blog.index') }}" method="GET" class="flex items-center bg-surface-container-lowest shadow-ambient rounded-full px-5 py-3 border border-outline-variant/10 focus-within:border-primary/50 transition-all">
<span class="material-symbols-outlined text-primary">search</span>
<input name="search" value="{{ request('search') }}" class="bg-transparent border-none focus:ring-0 text-base flex-1 ml-2 outline-none w-full" placeholder="Cari panduan..." type="text"/>
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
        <a href="{{ route('blog.read', $article->slug) }}" class="block bg-surface-container-lowest rounded-lg p-4 shadow-ambient group cursor-pointer transition-all hover:-translate-y-1">
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
                <span class="text-secondary font-bold text-xs tracking-wider uppercase mb-2 block">{{ $article->category ?? 'PANDUAN' }}</span>
                <h3 class="font-display text-xl font-bold text-primary leading-snug mb-4 group-hover:text-primary-container transition-colors">{{ $article->title }}</h3>
                @if($article->subtitle)
                    <p class="text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $article->subtitle }}</p>
                @endif
                <div class="flex items-center justify-between mt-auto pt-4 border-t border-surface-container-high">
                    <span class="text-on-surface-variant text-sm">{{ $article->created_at ? $article->created_at->diffForHumans() : '' }}</span>
                    <div class="flex items-center text-primary font-bold text-sm">
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
@endsection
