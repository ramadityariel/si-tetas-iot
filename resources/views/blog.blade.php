@extends('layouts.admin')
@section('title', __('admin.blog.title') . ' - Si-Tetas Admin')

@section('content')
<div class="p-8 max-w-[1440px] mx-auto relative z-10">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Hero Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-[#194A63] dark:text-white tracking-tight font-headline drop-shadow-sm">{{ __('admin.blog.title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 font-body mt-1 font-medium">{{ __('admin.blog.subtitle') }}</p>
            </div>
            <a href="{{ route('blog.create') }}" class="bg-[#35627C] dark:bg-sky-600 hover:opacity-90 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2 active:scale-95 transition-all font-semibold font-body inline-flex">
                <span class="material-symbols-outlined">add</span>
                {{ __('admin.blog.create_new') }}
            </a>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="group relative overflow-hidden bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(56,189,248,0.1)] dark:hover:border-sky-500/30">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-sky-50 dark:bg-sky-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div class="transform group-hover:translate-x-1 transition-transform duration-300">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-bold font-body uppercase tracking-wider group-hover:text-sky-600 dark:group-hover:text-sky-300 transition-colors">{{ __('admin.blog.total_articles') }}</p>
                        <h3 class="text-4xl font-extrabold text-[#194A63] dark:text-white mt-1 font-headline" x-data="{ count: 0, target: {{ $totalArticles }} }" x-init="let duration = 1500; let start = null; let step = (timestamp) => { if (!start) start = timestamp; let progress = Math.min((timestamp - start) / duration, 1); count = Math.floor(progress * target); if (progress < 1) { window.requestAnimationFrame(step); } else { count = target; } }; window.requestAnimationFrame(step);" x-text="count">0</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-sky-100 dark:bg-sky-500/20 flex items-center justify-center border border-sky-200 dark:border-sky-500/30 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300">
                        <span class="material-symbols-outlined text-[#35627C] dark:text-sky-400 text-3xl">description</span>
                    </div>
                </div>
            </div>
            
            <div class="group relative overflow-hidden bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(52,211,153,0.1)] dark:hover:border-emerald-500/30">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div class="transform group-hover:translate-x-1 transition-transform duration-300">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-bold font-body uppercase tracking-wider group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors">{{ __('admin.blog.published') }}</p>
                        <h3 class="text-4xl font-extrabold text-[#194A63] dark:text-white mt-1 font-headline" x-data="{ count: 0, target: {{ $publishedArticles }} }" x-init="let duration = 1500; let start = null; let step = (timestamp) => { if (!start) start = timestamp; let progress = Math.min((timestamp - start) / duration, 1); count = Math.floor(progress * target); if (progress < 1) { window.requestAnimationFrame(step); } else { count = target; } }; window.requestAnimationFrame(step);" x-text="count">0</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center border border-emerald-200 dark:border-emerald-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <span class="material-symbols-outlined text-emerald-700 dark:text-emerald-400 text-3xl">check_circle</span>
                    </div>
                </div>
            </div>
            
            <div class="group relative overflow-hidden bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(249,115,22,0.1)] dark:hover:border-orange-500/30">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 dark:bg-orange-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div class="transform group-hover:translate-x-1 transition-transform duration-300">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-bold font-body uppercase tracking-wider group-hover:text-orange-700 dark:group-hover:text-orange-300 transition-colors">{{ __('admin.blog.draft') }}</p>
                        <h3 class="text-4xl font-extrabold text-[#194A63] dark:text-white mt-1 font-headline" x-data="{ count: 0, target: {{ $draftArticles }} }" x-init="let duration = 1500; let start = null; let step = (timestamp) => { if (!start) start = timestamp; let progress = Math.min((timestamp - start) / duration, 1); count = Math.floor(progress * target); if (progress < 1) { window.requestAnimationFrame(step); } else { count = target; } }; window.requestAnimationFrame(step);" x-text="count">0</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center border border-orange-200 dark:border-orange-500/30 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300">
                        <span class="material-symbols-outlined text-orange-700 dark:text-orange-400 text-3xl">edit_note</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Table Section -->
        <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden transition-all duration-300">
            <div class="p-6 flex justify-between items-center border-b border-slate-100 dark:border-white/10">
                <h3 class="text-xl font-bold text-[#194A63] dark:text-white font-headline">{{ __('admin.blog.article_list') }}</h3>
                <form action="{{ route('admin.blog') }}" method="GET" class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input name="search" value="{{ request('search') }}" class="pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-sky-500/50 w-64 font-body text-slate-800 dark:text-white placeholder:text-slate-400 shadow-sm" placeholder="{{ __('admin.blog.search_ph') }}" type="text"/>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse font-body">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-white/5 text-slate-500 dark:text-slate-300 text-sm font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">{{ __('admin.blog.col_thumb') }}</th>
                            <th class="px-6 py-4">{{ __('admin.blog.col_title') }}</th>
                            <th class="px-6 py-4">{{ __('admin.blog.col_date') }}</th>
                            <th class="px-6 py-4">{{ __('admin.blog.col_status') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('admin.blog.col_action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($articles as $article)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-16 h-12 rounded-lg bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-white/10 overflow-hidden flex items-center justify-center">
                                    @if($article->thumbnail)
                                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $article->thumbnail) }}" alt="Thumbnail"/>
                                    @else
                                        <span class="material-symbols-outlined text-slate-400">image</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="font-bold text-[#194A63] dark:text-white truncate">{{ $article->title }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $article->category ?? __('admin.blog.general') }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-sm font-medium">{{ $article->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($article->status === 'published')
                                    <span class="px-3 py-1 bg-green-100 dark:bg-emerald-500/20 text-green-700 dark:text-emerald-400 border border-green-200 dark:border-emerald-500/30 text-xs font-bold rounded-full">{{ __('admin.blog.published') }}</span>
                                @else
                                    <span class="px-3 py-1 bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-400 border border-orange-200 dark:border-orange-500/30 text-xs font-bold rounded-full">{{ __('admin.blog.draft') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    <a href="{{ route('blog.edit', $article->id) }}" class="p-2 text-slate-400 hover:text-[#35627C] dark:hover:text-sky-400 transition-colors"><span class="material-symbols-outlined text-xl">edit</span></a>
                                    <form action="{{ route('blog.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Yakin hapus artikel ini?');" class="inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors flex items-center"><span class="material-symbols-outlined text-xl">delete</span></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500 dark:text-slate-400 font-bold">{{ __('admin.blog.no_data') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 border-t border-slate-100 dark:border-white/10 flex justify-between items-center text-sm text-slate-500 dark:text-slate-400 font-body">
                <p>{{ __('admin.users.showing') }} {{ $articles->count() }} {{ strtolower(__('admin.blog.article_list')) }}</p>
                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-lg bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">{{ __('admin.users.prev') }}</button>
                    <button class="px-4 py-2 rounded-lg bg-[#35627C] dark:bg-sky-600 text-white shadow-sm border border-transparent">1</button>
                    <button class="px-4 py-2 rounded-lg bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">{{ __('admin.users.next') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
