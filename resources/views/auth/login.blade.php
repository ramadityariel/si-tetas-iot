@extends('layouts.guest')
@section('title', 'Masuk - Si-Tetas')

@section('content')
<div class="flex flex-col items-center justify-center w-full max-w-[440px] mx-auto z-10 relative">
    
    <!-- Tombol Kembali -->
    <div class="w-full mb-8">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[#194A63] font-semibold hover:opacity-75 transition-opacity group">
            <span class="material-symbols-outlined text-sm transition-transform group-hover:-translate-x-1">arrow_back</span>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <!-- Brand Logo Area -->
    <div class="text-center mb-6 flex flex-col items-center justify-center">
        <h1 class="font-headline font-bold text-[#194A63] text-[32px] tracking-tight">Si-Tetas</h1>
        <p class="font-body text-[#B59B71] text-[13px] font-medium mt-1">Smart Incubator System</p>
    </div>

    <!-- Central Login Card -->
    <div class="w-full bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 sm:p-10 relative">
        <header class="mb-8">
            <h2 class="font-headline font-bold text-[#191C1E] text-[17px]">Masuk untuk memulai sesi Anda</h2>
            <div class="w-8 h-[3px] bg-[#B59B71] mt-3 rounded-full"></div>
        </header>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            
            <!-- Email Input -->
            <div class="space-y-1.5">
                <label class="block text-[13px] font-semibold text-[#41484D] ml-1" for="email">Alamat Email</label>
                <div class="relative flex items-center group">
                    <input class="w-full px-4 py-3.5 bg-[#F2F4F7] border-none rounded-xl text-sm text-[#191C1E] placeholder:text-[#71787D] focus:ring-2 focus:ring-[#35627C]/20 transition-all outline-none" id="email" name="email" placeholder="contoh@email.com" type="email" required />
                    <span class="material-symbols-outlined absolute right-4 text-[#71787D] group-focus-within:text-[#35627C] transition-colors pointer-events-none" style="font-variation-settings: 'wght' 300">mail</span>
                </div>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Input -->
            <div class="space-y-1.5">
                <label class="block text-[13px] font-semibold text-[#41484D] ml-1" for="password">Kata Sandi</label>
                <div class="relative flex items-center group">
                    <input class="w-full px-4 py-3.5 bg-[#F2F4F7] border-none rounded-xl text-sm text-[#191C1E] placeholder:text-[#71787D] focus:ring-2 focus:ring-[#35627C]/20 transition-all outline-none" id="password" name="password" placeholder="••••••••" type="password" required />
                    <span class="material-symbols-outlined absolute right-4 text-[#71787D] group-focus-within:text-[#35627C] transition-colors pointer-events-none" style="font-variation-settings: 'wght' 300">lock</span>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="remember" class="peer h-[18px] w-[18px] appearance-none rounded-[4px] border-2 border-[#C1C7CD] bg-white checked:bg-[#35627C] checked:border-[#35627C] transition-all cursor-pointer" />
                        <span class="material-symbols-outlined absolute text-white text-[14px] opacity-0 peer-checked:opacity-100 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none" style="font-variation-settings: 'wght' 700">check</span>
                    </div>
                    <span class="text-[13px] font-semibold text-[#41484D]">Ingat Saya</span>
                </label>
                <a href="#" class="text-[13px] font-bold text-[#35627C] hover:underline decoration-[#35627C]/30">Lupa kata sandi saya</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full py-3.5 bg-[#35627C] text-white text-[14px] font-bold rounded-full shadow-[0_6px_15px_rgba(53,98,124,0.25)] hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-4">
                Masuk
                <span class="material-symbols-outlined text-[18px]">login</span>
            </button>
        </form>
    </div>

    <!-- Legal Footer -->
    <div class="mt-8 text-center w-full">
        <p class="text-[10px] font-medium text-[#71787D] mb-2.5">© {{ date('Y') }} Si-Tetas Smart Incubator. Membina Kehidupan Digital.</p>
        <div class="flex items-center justify-center gap-3 text-[10px] font-semibold text-[#71787D]">
            <a href="#" class="hover:text-[#41484D] transition-colors">Kebijakan Privasi</a>
            <div class="w-1 h-1 bg-[#C1C7CD] rounded-full"></div>
            <a href="#" class="hover:text-[#41484D] transition-colors">Syarat &amp; Ketentuan</a>
            <div class="w-1 h-1 bg-[#C1C7CD] rounded-full"></div>
            <a href="#" class="hover:text-[#41484D] transition-colors">Hubungi Kami</a>
        </div>
    </div>
</div>

<!-- Warning Modal -->
<div id="adminWarningModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="p-6 sm:p-8">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                    <span class="material-symbols-outlined text-[28px]">warning</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold font-headline text-[#191C1E]">Akses Terbatas</h3>
                </div>
            </div>
            <p class="text-sm font-body text-[#41484D] leading-relaxed mb-8">
                Dashboard ini khusus untuk memonitoring sistem inkubator dan hanya bisa diakses oleh Admin Si-Tetas.
            </p>
            <div class="flex items-center justify-end gap-3">
                <a href="{{ url('/') }}" class="px-5 py-2.5 rounded-xl border-2 border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">
                    Kembali
                </a>
                <button id="btnContinueLogin" type="button" class="px-5 py-2.5 rounded-xl bg-[#194A63] text-white font-bold text-sm hover:opacity-90 shadow-lg transition-opacity">
                    Ya, tetap login
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script to Handle Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('adminWarningModal');
        const btnContinue = document.getElementById('btnContinueLogin');

        if (btnContinue && modal) {
            btnContinue.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
    });
</script>
@endsection
