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

    <!-- TOP NAV (Back & Toggles) -->
    <div class="absolute top-0 left-0 w-full px-6 sm:px-10 py-6 flex justify-between items-center z-50">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sky-600 dark:text-sky-400 font-bold hover:opacity-75 transition-opacity group">
            <span class="material-symbols-outlined text-lg transition-transform group-hover:-translate-x-1">arrow_back</span>
            <span class="hidden sm:inline">{{ __('admin.login.back') }}</span>
        </a>
        
        <div class="flex items-center gap-3">
            <!-- Theme Toggle Icon -->
            <button id="login-theme-toggle" class="w-9 h-9 flex items-center justify-center rounded-full bg-white dark:bg-slate-800 text-slate-500 dark:text-sky-300 shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors" title="Toggle Theme">
                <span id="login-theme-icon" class="material-symbols-outlined text-[20px]">dark_mode</span>
            </button>
            
            <!-- Language Toggle Capsule -->
            <a href="{{ route('lang.switch', app()->getLocale() == 'id' ? 'en' : 'id') }}" class="relative flex items-center w-[72px] h-9 rounded-full bg-slate-200 dark:bg-slate-700 shadow-inner border border-slate-300 dark:border-slate-600 transition-colors overflow-hidden font-bold text-[11px]">
                <div class="absolute w-1/2 h-full bg-white dark:bg-slate-600 rounded-full shadow transition-transform duration-300 {{ app()->getLocale() == 'id' ? 'translate-x-full' : 'translate-x-0' }}"></div>
                <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'en' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">EN</span>
                <span class="w-1/2 text-center z-10 {{ app()->getLocale() == 'id' ? 'text-sky-600 dark:text-sky-400' : 'text-slate-500 dark:text-slate-400' }}">ID</span>
            </a>
        </div>
    </div>

    <div class="w-full max-w-[440px] z-10 relative mt-8 sm:mt-12">

        

        <!-- CONTAINER UNTUK CARD + MASKOT (Agar relative satu sama lain) -->
        <div class="relative w-full max-w-[440px] mx-auto mt-24 z-10">

                                    <!-- =============================================
                 MASKOT AYAM INTERAKTIF (KEPALA & BADAN DI BELAKANG CARD)
            ============================================= -->
            <div class="absolute -top-[160px] left-1/2 -translate-x-1/2 w-[220px] h-[200px] z-0 pointer-events-none">
                
                <!-- Telur yang dilempar (tersembunyi default) -->
                <div id="egg-projectile" class="absolute pointer-events-none z-30" style="display:none; left:50%; top:50%; transform:translate(-50%,-50%);">
                    <svg width="28" height="36" viewBox="0 0 28 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="14" cy="20" rx="11" ry="14" fill="#FFF9C4" stroke="#E8D44D" stroke-width="1.5"/>
                        <ellipse cx="10" cy="16" rx="3" ry="2" fill="white" opacity="0.5"/>
                    </svg>
                </div>

                <!-- Rooster SVG -->
                <svg id="chicken-mascot" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" class="w-full h-full overflow-visible" style="cursor:default;">
                    <!-- Badan / Leher yang lebar (Bukan tabung) -->
                    <path d="M 20 160 C 30 100, 130 100, 140 160 Z" fill="#FFFDE7"/>
                    
                    <!-- Jengger Besar -->
                    <path d="M 70 30 C 60 -10, 90 -10, 80 25 Z" fill="#D32F2F"/>
                    <path d="M 60 40 C 40 0, 70 0, 70 30 Z" fill="#D32F2F"/>
                    <path d="M 90 30 C 100 -5, 70 -10, 80 25 Z" fill="#D32F2F"/>
                    <path d="M 100 40 C 120 5, 90 0, 90 30 Z" fill="#D32F2F"/>
                    <path d="M 50 50 C 30 20, 60 10, 65 40 Z" fill="#D32F2F"/>

                    <!-- Kepala Bulat -->
                    <circle cx="80" cy="85" r="45" fill="#FFFDE7"/>

                    <!-- Red Mask around eyes -->
                    <path d="M 50 80 C 50 50, 110 50, 110 80 C 110 110, 80 115, 80 115 C 80 115, 50 110, 50 80 Z" fill="#D32F2F"/>

                    <!-- Eye Whites -->
                    <g id="eye-left">
                        <ellipse cx="64" cy="80" rx="11" ry="14" fill="white"/>
                        <g id="pupil-left">
                            <ellipse cx="64" cy="80" rx="5" ry="6.5" fill="#1565C0"/>
                            <ellipse cx="64" cy="80" rx="2.5" ry="3" fill="#0D1B2A"/>
                            <circle cx="62" cy="78" r="1.5" fill="white" opacity="0.9"/>
                        </g>
                        <ellipse id="eyelid-left" cx="64" cy="80" rx="11.5" ry="0" fill="#D32F2F" stroke="#D32F2F" stroke-width="0"/>
                    </g>

                    <g id="eye-right">
                        <ellipse cx="96" cy="80" rx="11" ry="14" fill="white"/>
                        <g id="pupil-right">
                            <ellipse cx="96" cy="80" rx="5" ry="6.5" fill="#1565C0"/>
                            <ellipse cx="96" cy="80" rx="2.5" ry="3" fill="#0D1B2A"/>
                            <circle cx="94" cy="78" r="1.5" fill="white" opacity="0.9"/>
                        </g>
                        <ellipse id="eyelid-right" cx="96" cy="80" rx="11.5" ry="0" fill="#D32F2F" stroke="#D32F2F" stroke-width="0"/>
                    </g>

                    <!-- Alis -->
                    <path id="brow-left"  d="M 54 62 Q 64 56 74 62" stroke="#B71C1C" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <path id="brow-right" d="M 86 62 Q 96 56 106 62" stroke="#B71C1C" stroke-width="3" fill="none" stroke-linecap="round"/>

                    <!-- Paruh Bawah -->
                    <path d="M 68 98 C 74 125, 86 125, 92 98 Z" fill="#F57F17"/>
                    <!-- Lidah -->
                    <path d="M 74 106 C 80 118, 80 118, 86 106 Z" fill="#D32F2F"/>

                    <!-- Paruh Atas -->
                    <path d="M 58 98 C 80 85, 98 92, 110 110 C 80 116, 68 110, 58 98 Z" fill="#FFB300"/>
                    <path d="M 58 98 C 80 85, 98 92, 110 110 C 80 110, 68 104, 58 98 Z" fill="#FFCA28"/> 
                    
                    <!-- Lubang Hidung -->
                    <ellipse cx="74" cy="95" rx="2" ry="3" fill="#F57F17" transform="rotate(-20 74 95)"/>

                    <!-- Pial dagu -->
                    <path d="M 68 110 C 56 135, 74 145, 80 116 Z" fill="#D32F2F"/>
                    <path d="M 92 110 C 104 135, 86 145, 80 116 Z" fill="#B71C1C"/>
                </svg>

                
            </div>

            <!-- TANGAN KIRI MEMEGANG SAMPING -->
            <div class="absolute top-1/2 -left-[30px] -translate-y-1/2 w-[60px] h-[130px] z-20 pointer-events-none drop-shadow-lg">
                <svg viewBox="0 0 70 140" class="w-full h-full overflow-visible">
                    <g fill="white" stroke="#9E9E9E" stroke-width="3" stroke-linejoin="round">
                        <!-- Jari 2 (Telunjuk) -->
                        <rect x="-10" y="45" width="70" height="26" rx="13" />
                        <!-- Jari 3 (Tengah) -->
                        <rect x="-10" y="71" width="65" height="26" rx="13" />
                        <!-- Jari 4 (Kelingking) -->
                        <rect x="-10" y="97" width="55" height="26" rx="13" />
                        <!-- Jempol (Jari 1) -->
                        <rect x="-10" y="19" width="60" height="26" rx="13" />
                        
                        <!-- Base palm -->
                        <path d="M 0 19 L 0 123 C -20 123, -20 19, 0 19 Z" fill="white" stroke="none"/>
                        <path d="M 0 19 C -20 19, -20 123, 0 123" fill="none" stroke="#9E9E9E" stroke-linecap="round"/>
                    </g>
                </svg>
            </div>
            
            <!-- TANGAN KANAN MEMEGANG SAMPING -->
            <div class="absolute top-1/2 -right-[30px] -translate-y-1/2 w-[60px] h-[130px] z-20 pointer-events-none drop-shadow-lg scale-x-[-1]">
                <svg viewBox="0 0 70 140" class="w-full h-full overflow-visible">
                    <g fill="white" stroke="#9E9E9E" stroke-width="3" stroke-linejoin="round">
                        <rect x="-10" y="45" width="70" height="26" rx="13" />
                        <rect x="-10" y="71" width="65" height="26" rx="13" />
                        <rect x="-10" y="97" width="55" height="26" rx="13" />
                        <rect x="-10" y="19" width="60" height="26" rx="13" />
                        <path d="M 0 19 L 0 123 C -20 123, -20 19, 0 19 Z" fill="white" stroke="none"/>
                        <path d="M 0 19 C -20 19, -20 123, 0 123" fill="none" stroke="#9E9E9E" stroke-linecap="round"/>
                    </g>
                </svg>
            </div>

            <!-- Central Login Card (Glassmorphism) -->
            <div class="w-full bg-white dark:bg-slate-900/90 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-slate-700 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl p-8 sm:p-10 relative z-10 transition-all duration-500">
                <header class="mb-8 text-center">
                    <h2 class="font-headline font-bold text-slate-800 dark:text-white text-[20px]">{{ __('admin.login.title') }}</h2>
                    <div class="w-8 h-[3px] bg-[#FF8F00] mt-3 rounded-full mx-auto"></div>
                </header>

                @if(session('error'))
                    <div id="login-error-banner" class="mb-4 px-4 py-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500/30 rounded-xl text-red-700 dark:text-red-300 text-xs font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ session('error') }}
                    </div>
                @endif

                <form id="login-form" action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="space-y-1.5">
                        <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 ml-1" for="email">{{ __('admin.login.email') }}</label>
                        <div class="relative flex items-center group">
                            <input class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-2 focus:ring-[#FF8F00]/30 focus:border-[#FF8F00] transition-all outline-none shadow-sm" id="email" name="email" placeholder="{{ __('admin.login.email_ph') }}" type="email" required />
                            <span class="material-symbols-outlined absolute right-4 text-slate-400 group-focus-within:text-[#FF8F00] transition-colors pointer-events-none">mail</span>
                        </div>
                        @error('email')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-1 ml-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 ml-1" for="password">{{ __('admin.login.password') }}</label>
                        <div class="relative flex items-center group">
                            <input class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-2 focus:ring-[#FF8F00]/30 focus:border-[#FF8F00] transition-all outline-none shadow-sm" id="password" name="password" placeholder="{{ __('admin.login.password_ph') }}" type="password" required />
                            <span class="material-symbols-outlined absolute right-4 text-slate-400 group-focus-within:text-[#FF8F00] transition-colors pointer-events-none">lock</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-2">
                        <a href="{{ app()->getLocale() == 'id' ? 'https://wa.me/6281586219748?text=Halo%20Admin%20Si-Tetas%2C%20saya%20lupa%20password%20akun%20saya.%20Mohon%20bantuannya%20untuk%20melakukan%20reset%20password.' : 'https://wa.me/6281586219748?text=Hello%20Si-Tetas%20Admin%2C%20I%20forgot%20my%20account%20password.%20Please%20help%20me%20reset%20it.' }}" target="_blank" rel="noopener noreferrer" class="text-[13px] font-bold text-sky-600 dark:text-sky-400 hover:underline decoration-sky-400/30">{{ __('admin.login.forgot') }}</a>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-[#FF8F00] hover:bg-[#E65100] text-white text-[15px] font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 mt-4 active:scale-[0.99]">
                        {{ __('admin.login.btn') }}
                        <span class="material-symbols-outlined text-[18px]">login</span>
                    </button>

                </form>
            </div>

        </div>

        <!-- Legal Footer -->
        <div class="mt-12 text-center w-full relative z-10">
            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-2.5">© {{ date('Y') }} Si-Tetas Smart Incubator. {{ __('admin.footer.rights') }}</p>
        </div>
    </div>
</div>

<style>
    /* Animasi kelopak mata tutup */
    @keyframes blinkClose {
        0%   { ry: 0; }
        100% { ry: 9.5; }
    }
    @keyframes blinkOpen {
        0%   { ry: 9.5; }
        100% { ry: 0; }
    }

    /* Animasi telur dilempar */
    @keyframes throwEgg {
        0%   { transform: translate(-50%, -50%) scale(1) rotate(0deg);   opacity: 1; }
        60%  { transform: translate(120px, -80px) scale(1.1) rotate(40deg); opacity: 1; }
        100% { transform: translate(220px, 30px) scale(0.6) rotate(80deg);  opacity: 0; }
    }

    /* Animasi ayam marah (gemetar) */
    @keyframes angryShake {
        0%,100% { transform: translateX(0); }
        20%     { transform: translateX(-4px) rotate(-2deg); }
        40%     { transform: translateX(4px)  rotate(2deg); }
        60%     { transform: translateX(-3px) rotate(-1deg); }
        80%     { transform: translateX(3px)  rotate(1deg); }
    }

    #chicken-mascot.angry {
        animation: angryShake 0.5s ease-in-out;
    }

    

    /* Telur animasi */
    #egg-projectile.throwing {
        animation: throwEgg 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* ===================================================
           1. THEME TOGGLE
        =================================================== */
        const toggleBtn = document.getElementById('login-theme-toggle');
        const themeIcon = document.getElementById('login-theme-icon');
        const html      = document.documentElement;

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
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                html.classList.toggle('dark');
                updateIcon();
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            });
        }

        /* ===================================================
           2. MASKOT AYAM — REFERENSI ELEMEN
        =================================================== */
        const mascot      = document.getElementById('chicken-mascot');
        const pupilL      = document.getElementById('pupil-left');
        const pupilR      = document.getElementById('pupil-right');
        const eyelidL     = document.getElementById('eyelid-left');
        const eyelidR     = document.getElementById('eyelid-right');
        const browL       = document.getElementById('brow-left');
        const browR       = document.getElementById('brow-right');
        const eggEl       = document.getElementById('egg-projectile');
        const passwordInput = document.getElementById('password');
        const emailInput    = document.getElementById('email');

        // Posisi mata default (center of eye whites)
        const EYE_L = { baseX: 64, baseY: 80, maxR: 5 };
        const EYE_R = { baseX: 96, baseY: 80, maxR: 5 };

        let isPasswordFocused = false;
        let bubbleTimer       = null;

        /* ===================================================
           3. FUNGSI BANTU — BALON KOMENTAR
        =================================================== */
        function showBubble(text, durationMs = 2500) {
            // function disabled
        }

        /* ===================================================
           4. FUNGSI BANTU — GERAK PUPIL
           Pupil bergerak max 3.5px dari pusat mata
        =================================================== */
        function movePupils(targetX, targetY) {
            if (isPasswordFocused) return; // mata tetap tutup saat isi password

            const svgRect = mascot.getBoundingClientRect();
            const svgW    = svgRect.width;
            const svgH    = svgRect.height;
            const vbW     = 160; // viewBox width
            const vbH     = 160; // viewBox height

            // Konversi koordinat layar → koordinat SVG viewBox
            const mouseXvb = ((targetX - svgRect.left) / svgW) * vbW;
            const mouseYvb = ((targetY - svgRect.top)  / svgH) * vbH;

            // Fungsi hitung offset pupil untuk satu mata
            function calcOffset(eye) {
                const dx    = mouseXvb - eye.baseX;
                const dy    = mouseYvb - eye.baseY;
                const dist  = Math.sqrt(dx * dx + dy * dy);
                const angle = Math.atan2(dy, dx);
                const move  = Math.min(dist * 0.18, eye.maxR);
                return {
                    x: eye.baseX + Math.cos(angle) * move,
                    y: eye.baseY + Math.sin(angle) * move
                };
            }

            const posL = calcOffset(EYE_L);
            const posR = calcOffset(EYE_R);

            // Update semua child dari group pupil (iris, pupil hitam, kilap)
            const childrenL = pupilL.children;
            childrenL[0].setAttribute('cx', posL.x); childrenL[0].setAttribute('cy', posL.y);
            childrenL[1].setAttribute('cx', posL.x); childrenL[1].setAttribute('cy', posL.y);
            childrenL[2].setAttribute('cx', posL.x - 1.5); childrenL[2].setAttribute('cy', posL.y - 1.5);

            const childrenR = pupilR.children;
            childrenR[0].setAttribute('cx', posR.x); childrenR[0].setAttribute('cy', posR.y);
            childrenR[1].setAttribute('cx', posR.x); childrenR[1].setAttribute('cy', posR.y);
            childrenR[2].setAttribute('cx', posR.x - 1.5); childrenR[2].setAttribute('cy', posR.y - 1.5);
        }

        /* ===================================================
           5. TRACKING KURSOR (mouse + touch)
        =================================================== */
        document.addEventListener('mousemove', (e) => movePupils(e.clientX, e.clientY));
        document.addEventListener('touchmove', (e) => {
            const t = e.touches[0];
            movePupils(t.clientX, t.clientY);
        }, { passive: true });

        /* ===================================================
           6. TUTUP MATA SAAT ISI PASSWORD
        =================================================== */
        function closEyes() {
            isPasswordFocused = true;
            // Animasi kelopak turun: ry dari 0 → 9.5
            let ry = 0;
            const interval = setInterval(() => {
                ry = Math.min(ry + 1.5, 14.5);
                eyelidL.setAttribute('ry', ry);
                eyelidR.setAttribute('ry', ry);
                if (ry >= 14.5) clearInterval(interval);
            }, 16);

            // Alis sedikit turun (ekspresi malu/private)
            browL.setAttribute('d', 'M 54 66 Q 64 60 74 66');
            browR.setAttribute('d', 'M 86 66 Q 96 60 106 66');
            showBubble('🔒 Saya tidak melihat!', 99999);
        }

        function openEyes() {
            isPasswordFocused = false;
            let ry = 14.5;
            const interval = setInterval(() => {
                ry = Math.max(ry - 1.5, 0);
                eyelidL.setAttribute('ry', ry);
                eyelidR.setAttribute('ry', ry);
                if (ry <= 0) clearInterval(interval);
            }, 16);

            // Kembalikan alis normal
            browL.setAttribute('d', 'M 54 62 Q 64 56 74 62');
            browR.setAttribute('d', 'M 86 62 Q 96 56 106 62');
            
        }

        if (passwordInput) {
            passwordInput.addEventListener('focus',  closEyes);
            passwordInput.addEventListener('blur',   openEyes);
        }

        /* ===================================================
           7. BALON SAMBUTAN SAAT FOKUS EMAIL
        =================================================== */
        if (emailInput) {
            emailInput.addEventListener('focus', () => {
                showBubble('✉️ Masukkan email kamu!');
            });
        }

        /* ===================================================
           8. ANIMASI LEMPAR TELUR SAAT LOGIN GAGAL
        =================================================== */
        function throwEgg() {
            // Reset & tampilkan telur
            eggEl.style.display = 'block';
            eggEl.classList.remove('throwing');
            void eggEl.offsetWidth; // reflow trick

            // Alis marah
            browL.setAttribute('d', 'M 54 62 Q 64 56 74 66');
            browR.setAttribute('d', 'M 86 66 Q 96 56 106 62');

            // Gemetar ayam
            mascot.classList.add('angry');
            mascot.addEventListener('animationend', () => mascot.classList.remove('angry'), { once: true });

            // Lempar!
            eggEl.classList.add('throwing');
            showBubble('😠 Password salah! 🥚', 3000);

            // Sembunyikan telur setelah animasi selesai
            setTimeout(() => {
                eggEl.style.display = 'none';
                eggEl.classList.remove('throwing');
                // Kembalikan alis normal setelah beberapa detik
                setTimeout(() => {
                    browL.setAttribute('d', 'M 54 62 Q 64 56 74 62');
                    browR.setAttribute('d', 'M 86 62 Q 96 56 106 62');
                }, 2000);
            }, 900);
        }

        /* ===================================================
           9. CEK APAKAH ADA ERROR LOGIN (dari session)
              — Trigger lempar telur otomatis
        =================================================== */
        const errorBanner = document.getElementById('login-error-banner');
        if (errorBanner) {
            // Delay sedikit agar halaman sudah render sempurna
            setTimeout(throwEgg, 500);
        }

        /* ===================================================
           10. INTERCEPT FORM SUBMIT
               Jika ada validasi client-side gagal, lempar juga
        =================================================== */
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', function() {
                // Reset mata sebelum submit (tampakkan kembali)
                openEyes();
                showBubble('🚀 Logging in...', 9999);
            });
        }

        /* ===================================================
           11. SALAM AWAL
        =================================================== */
        setTimeout(() => showBubble('Halo! 👋 Selamat datang!', 2500), 800);

    });
</script>
@endsection