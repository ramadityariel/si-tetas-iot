@extends('layouts.admin')
@section('title', 'Manajemen Blog - Si-Tetas Admin')

@section('content')
<div class="p-8">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Hero Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-primary tracking-tight font-headline">Manajemen Blog</h2>
                <p class="text-slate-500 font-body">Kelola artikel edukasi dan berita terbaru untuk pengguna Si-Tetas.</p>
            </div>
            <a href="{{ route('blog.create') }}" class="bg-[#35627C] text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2 hover:opacity-90 active:scale-95 transition-all font-semibold font-body inline-flex">
                <span class="material-symbols-outlined">add</span>
                Buat Artikel Baru
            </a>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_8px_24px_rgba(25,47,63,0.04)] border border-outline-variant/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium font-body">Total Artikel</p>
                        <h3 class="text-3xl font-extrabold text-primary mt-1 font-headline">{{ $articles->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">description</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_8px_24px_rgba(25,47,63,0.04)] border border-outline-variant/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium font-body">Diterbitkan</p>
                        <h3 class="text-3xl font-extrabold text-primary mt-1 font-headline">{{ $articles->where('status', 'published')->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-700">check_circle</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_8px_24px_rgba(25,47,63,0.04)] border border-outline-variant/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium font-body">Draft</p>
                        <h3 class="text-3xl font-extrabold text-primary mt-1 font-headline">{{ $articles->where('status', 'draft')->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-orange-700">edit_note</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Table Section -->
        <div class="bg-surface-container-lowest rounded-xl shadow-[0_8px_24px_rgba(25,47,63,0.04)] overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-white">
                <h3 class="text-xl font-bold text-primary font-headline">Daftar Artikel</h3>
                <form action="{{ route('admin.blog') }}" method="GET" class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                    <input name="search" value="{{ request('search') }}" class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 w-64 font-body" placeholder="Cari artikel..." type="text"/>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse font-body">
                    <thead>
                        <tr class="bg-surface-container-low text-slate-600 text-sm font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">Thumbnail</th>
                            <th class="px-6 py-4">Judul Artikel</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($articles as $article)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-16 h-12 rounded-lg bg-slate-200 overflow-hidden">
                                    @if($article->thumbnail)
                                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $article->thumbnail) }}" alt="Thumbnail"/>
                                    @else
                                        <span class="material-symbols-outlined text-slate-400 mt-2 ml-4">image</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="font-bold text-primary truncate">{{ $article->title }}</p>
                                    <p class="text-xs text-slate-400">{{ $article->category ?? 'Umum' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $article->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($article->status === 'published')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full backdrop-blur-sm">Diterbitkan</span>
                                @else
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded-full backdrop-blur-sm">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    <a href="{{ route('blog.edit', $article->id) }}" class="p-2 text-slate-400 hover:text-primary transition-colors"><span class="material-symbols-outlined text-xl">edit</span></a>
                                    <form action="{{ route('blog.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Yakin hapus artikel ini?');" class="inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-error transition-colors flex items-center"><span class="material-symbols-outlined text-xl">delete</span></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-500 font-medium">Belum ada artikel.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 flex justify-between items-center text-sm text-slate-500 font-body">
                <p>Menampilkan {{ $articles->count() }} artikel</p>
                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-lg border border-slate-100 hover:bg-slate-50">Sebelumnya</button>
                    <button class="px-4 py-2 rounded-lg bg-primary-container text-white">1</button>
                    <button class="px-4 py-2 rounded-lg border border-slate-100 hover:bg-slate-50">2</button>
                    <button class="px-4 py-2 rounded-lg border border-slate-100 hover:bg-slate-50">Selanjutnya</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
