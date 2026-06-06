@extends('layouts.guest')
@section('title', __('admin.login.title') . ' - Si-Tetas')

@section('content')
<!-- Script untuk Tema -->
<script>
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<div class="min-h-screen w-full flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-950 transition-colors duration-500 relative px-4 py-12 overflow-hidden">
    
    <!-- Ambient Background -->
    <div class="absolute inset-0 z-0 pointer-events-none opacity-30 dark:opacity-100 transition-opacity duration-500">
        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Bg" class="w-full h-full object-cover" />
    </div>
    <div class="absolute inset-0 bg-white/80 dark:bg-black/70 z-0 transition-colors duration-500"></div>
    <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-sky-200/50 dark:bg-sky-500/20 rounded-full blur-[100px] pointer-events-none z-0"></div>

    <div class="w-full max-w-[440px] z-10 relative">
        
        <!-- Toggles & Back -->
        <div class="flex justify-between items-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sky-600 dark:text-sky-400 font-bold hover:opacity-75 transition-opacity group">
                <span class="material-symbols-outlined text-sm transition-transform group-hover:-translate-x-1">arrow_back</span>
                <span>{{ __('admin.login.back') }}</span>
            </a>
            
            <div class="flex items-center gap-2">
                <!-- Theme Toggle Icon -->
                <button id="login-theme-toggle" class="w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-slate-800 text-slate-500 dark:text-sky-300 shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors" title="Toggle Theme">
                    <span id="login-theme-icon" class="material-symbols-outlined text-[18px]">dark_mode</span>
                </button>
                
                <!-- Language Toggle Capsule -->
                <a href="{{ route('lang.switch', app()->getLocale() == 'id' ? 'en' : 'id') }}" class="relative flex items-center w-16 h-8 rounded-full bg-slate-200 dark:bg-slate-700 shadow-inner border border-slate-300 dark:border-slate-600 transition-colors overflow-hidden font-bold text-[10px]">
                    <div class="absolute w-1/2 h-full bg-white dark:bg-slate-600 rounded-full shadow transition-transform duration-300 {{ app()->getLocale() == 'id' ? 'translate-x-full' : 'translate-x-0' }}"></div>
                    <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'en' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">EN</span>
                    <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'id' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">ID</span>
                </a>
            </div>
        </div>

        <!-- Brand Logo Area -->
        <div class="text-center mb-8 flex flex-col items-center justify-center">
            <h1 class="font-headline font-black text-slate-900 dark:text-white text-[36px] tracking-wider uppercase drop-shadow-md" style="font-family: 'Brush Script MT', 'Dancing Script', cursive;">Si-Tetas</h1>
            <p class="font-body text-sky-600 dark:text-sky-400 text-[12px] font-bold tracking-widest uppercase mt-1">Smart Incubator System</p>
        </div>

        <!-- Central Login Card (Glassmorphism) -->
        <div class="w-full bg-white/60 dark:bg-white/5 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl p-8 sm:p-10 relative transition-all duration-500">
            <header class="mb-8 text-center sm:text-left">
                <h2 class="font-headline font-bold text-slate-800 dark:text-white text-[16px]">{{ __('admin.login.title') }}</h2>
                <div class="w-8 h-[3px] bg-sky-500 mt-3 rounded-full mx-auto sm:mx-0"></div>
            </header>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 ml-1" for="email">{{ __('admin.login.email') }}</label>
                    <div class="relative flex items-center group">
                        <input class="w-full px-4 py-3.5 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all outline-none shadow-sm" id="email" name="email" placeholder="{{ __('admin.login.email_ph') }}" type="email" required />
                        <span class="material-symbols-outlined absolute right-4 text-slate-400 group-focus-within:text-sky-500 transition-colors pointer-events-none">mail</span>
                    </div>
                    @error('email')
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1 ml-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 ml-1" for="password">{{ __('admin.login.password') }}</label>
                    <div class="relative flex items-center group">
                        <input class="w-full px-4 py-3.5 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all outline-none shadow-sm" id="password" name="password" placeholder="{{ __('admin.login.password_ph') }}" type="password" required />
                        <span class="material-symbols-outlined absolute right-4 text-slate-400 group-focus-within:text-sky-500 transition-colors pointer-events-none">lock</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="remember" class="peer h-[18px] w-[18px] appearance-none rounded-[4px] border-2 border-slate-300 dark:border-white/20 bg-white dark:bg-transparent checked:bg-sky-500 checked:border-sky-500 transition-all cursor-pointer" />
                            <span class="material-symbols-outlined absolute text-white text-[14px] opacity-0 peer-checked:opacity-100 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none" style="font-variation-settings: 'wght' 700">check</span>
                        </div>
                        <span class="text-[13px] font-semibold text-slate-700 dark:text-slate-300">{{ __('admin.login.remember') }}</span>
                    </label>
                    <a href="#" class="text-[13px] font-bold text-sky-600 dark:text-sky-400 hover:underline decoration-sky-400/30">{{ __('admin.login.forgot') }}</a>
                </div>

                <button type="submit" class="w-full py-3.5 bg-[#35627C] hover:bg-[#194A63] text-white text-[14px] font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 mt-4 active:scale-[0.99]">
                    {{ __('admin.login.btn') }}
                    <span class="material-symbols-outlined text-[18px]">login</span>
                </button>
                
                <div class="text-center pt-4 border-t border-slate-200 dark:border-white/10 mt-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-light">
                        {{ __('admin.login.no_account') }} 
                        <a href="{{ url('/register') }}" class="text-sky-600 dark:text-sky-400 font-bold hover:underline ml-1">{{ __('admin.login.register') }}</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Legal Footer -->
        <div class="mt-8 text-center w-full">
            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-2.5">© {{ date('Y') }} Si-Tetas Smart Incubator. {{ __('admin.footer.rights') }}</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('login-theme-toggle');
        const themeIcon = document.getElementById('login-theme-icon');
        const html = document.documentElement;

        function updateIcon() {
            if (html.classList.contains('dark')) {
                themeIcon.textContent = 'light_mode';
                themeIcon.classList.add('text-amber-400');
                themeIcon.classList.remove('text-slate-500');
            } else {
                themeIcon.textContent = 'dark_mode';
                themeIcon.classList.remove('text-amber-400');
                themeIcon.classList.add('text-slate-500');
            }
        }
        updateIcon();

        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                html.classList.toggle('dark');
                updateIcon();
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            });
        }
    });
</script>
@endsection