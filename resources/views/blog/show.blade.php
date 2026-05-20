@extends('layouts.app')

@section('content')
<style>
    .no-border { border: none !important; }
    .prose h1, .prose h2, .prose h3 { font-family: 'Manrope', sans-serif; font-weight: 800; color: #194A63; }
    .prose p { font-family: 'Plus Jakarta Sans', sans-serif; color: #41484d; line-height: 1.8; }
</style>
<main class="pt-32 pb-20 px-6 max-w-4xl mx-auto">
<!-- Back Link -->
<a class="inline-flex items-center gap-2 text-secondary font-semibold hover:opacity-75 transition-opacity mb-8 group" href="{{ route('blog.index') }}">
<span class="material-symbols-outlined text-sm transition-transform group-hover:-translate-x-1">arrow_back</span>
<span>Kembali ke Blog</span>
</a>
<!-- Article Header -->
<header class="mb-12">
<div class="inline-block px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container text-xs font-bold uppercase tracking-wider mb-4">
                {{ $article->category ?? 'Info' }}
            </div>
<h1 class="font-display text-4xl md:text-5xl font-extrabold text-primary leading-tight mb-6">
                {{ $article->title }}
            </h1>
<div class="flex items-center gap-4 py-6 border-y border-outline-variant/20">
<div class="w-12 h-12 rounded-full overflow-hidden bg-primary-container flex items-center justify-center text-on-primary-container">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
</div>
<div>
<p class="text-on-surface font-bold text-sm">{{ $article->author->name ?? 'Admin Si-Tetas' }}</p>
<p class="text-on-surface-variant text-xs">Dipublikasikan pada {{ $article->created_at->format('d M Y') }}</p>
</div>
</div>
</header>
<!-- Hero Image -->
<figure class="mb-12">
@if($article->thumbnail)
    <img alt="{{ $article->title }}" class="w-full aspect-[21/9] object-cover rounded-lg shadow-ambient" src="{{ asset('storage/' . $article->thumbnail) }}"/>
@else
    <div class="w-full aspect-[21/9] bg-slate-200 dark:bg-slate-800 flex items-center justify-center rounded-lg shadow-ambient">
        <span class="material-symbols-outlined text-6xl text-slate-400">image</span>
    </div>
@endif
@if($article->subtitle)
<figcaption class="mt-4 text-center text-on-surface-variant text-sm italic">
                {{ $article->subtitle }}
            </figcaption>
@endif
</figure>
<!-- Content Body -->
<article class="prose prose-slate lg:prose-lg break-words overflow-hidden prose-headings:text-primary prose-a:text-secondary prose-strong:text-primary">
{!! $article->content !!}
</article>

<!-- Bottom Back Link -->
<div class="flex justify-end mt-12 pt-8 border-t border-outline-variant/20">
    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-secondary font-semibold hover:opacity-75 transition-opacity group">
        <span>Kembali ke Blog</span>
        <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-1">arrow_forward</span>
    </a>
</div>
<!-- Related Footer -->
<section class="mt-20 pt-10 border-t border-outline-variant/20">
@if($relatedArticles->count() > 0)
    <h3 class="font-display text-xl font-bold text-primary mb-6">Artikel Terkait</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($relatedArticles as $related)
        <a href="{{ route('blog.read', $related->slug) }}" class="block group cursor-pointer">
            <div class="aspect-video rounded-lg overflow-hidden mb-3 bg-slate-100">
                @if($related->thumbnail)
                    <img alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $related->thumbnail) }}"/>
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400 group-hover:scale-105 transition-transform duration-500">
                        <span class="material-symbols-outlined text-4xl">image</span>
                    </div>
                @endif
            </div>
            <h4 class="font-bold text-on-surface group-hover:text-primary transition-colors">{{ $related->title }}</h4>
        </a>
        @endforeach
    </div>
@endif
</section>
</main>
@endsection
