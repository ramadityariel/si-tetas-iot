@extends('layouts.admin')
@section('title', __('admin.sidebar.dashboard') . ' - Si-Tetas Admin')

@section('content')
<div class="px-8 py-6 max-w-[1440px] mx-auto">
    
    <!-- Header Section -->
    <div class="mb-10 relative z-10">
        <h2 class="font-headline text-3xl font-extrabold text-[#194A63] dark:text-white tracking-tight drop-shadow-sm">{{ __('admin.dashboard.title') }}</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ __('admin.dashboard.subtitle') }}</p>
    </div>
    <!-- NEW CARDS ROW -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 relative z-10">
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl relative overflow-hidden transition-all duration-500 hover:-translate-y-2">
            <div class="relative z-10 flex flex-col justify-center h-full">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">{{ __('admin.dashboard.welcome_admin') }}</p>
                <div class="flex items-baseline gap-1">
                    <span class="font-headline text-3xl font-black text-[#194A63] dark:text-white">{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </div>
        </div>
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl relative overflow-hidden transition-all duration-500 hover:-translate-y-2">
            <div class="relative z-10 flex flex-col justify-center h-full">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">{{ __('admin.dashboard.published_articles') }}</p>
                <div class="flex items-baseline gap-1">
                    <span class="font-headline text-3xl font-black text-[#194A63] dark:text-white" data-target="{{ $published_articles_count ?? 0 }}" data-decimals="0">0</span>
                    <span class="text-slate-500 dark:text-slate-400 text-sm ml-2 font-bold">{{ __('admin.dashboard.articles') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bento Grid Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 relative z-10">
        <!-- Suhu Card -->
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl relative overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(56,189,248,0.1)] dark:hover:border-sky-500/30">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-sky-50 dark:bg-sky-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 p-5 opacity-20 dark:opacity-10 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 group-hover:text-sky-500 dark:group-hover:text-sky-400 group-hover:animate-pulse" data-icon="thermostat">thermostat</span>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider group-hover:text-[#194A63] dark:group-hover:text-sky-300 transition-colors">{{ __('admin.dashboard.current_temp') }}</p>
                <div class="flex items-baseline gap-1 transform group-hover:translate-x-1 transition-transform duration-300">
                    <span id="count-temp" class="font-headline text-4xl font-black text-[#194A63] dark:text-white" data-target="{{ $latest_sensor->temperature ?? 0 }}" data-decimals="1">0</span>
                    <span class="text-[#194A63] dark:text-sky-400 font-bold text-xl">°C</span>
                </div>
                <div class="mt-4 flex items-center gap-2 transform group-hover:translate-x-1 transition-transform duration-300 delay-75">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] animate-pulse"></div>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ __('admin.dashboard.stable') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Kelembapan Card -->
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl relative overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(52,211,153,0.1)] dark:hover:border-emerald-500/30">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 p-5 opacity-20 dark:opacity-10 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 group-hover:scale-110 transition-all" data-icon="humidity_percentage">humidity_percentage</span>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider group-hover:text-[#194A63] dark:group-hover:text-emerald-300 transition-colors">{{ __('admin.dashboard.humidity') }}</p>
                <div class="flex items-baseline gap-1 transform group-hover:translate-x-1 transition-transform duration-300">
                    <span id="count-humid" class="font-headline text-4xl font-black text-[#194A63] dark:text-white" data-target="{{ $latest_sensor->humidity ?? 0 }}" data-decimals="0">0</span>
                    <span class="text-[#194A63] dark:text-sky-400 font-bold text-xl">%</span>
                </div>
                <div class="mt-4 flex items-center gap-2 transform group-hover:translate-x-1 transition-transform duration-300 delay-75">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] animate-pulse"></div>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ __('admin.dashboard.optimal') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Status Card -->
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl relative overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(14,165,233,0.1)] dark:hover:border-sky-500/30">
            <div class="absolute -left-4 -top-4 w-24 h-24 bg-sky-50 dark:bg-sky-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 p-5 opacity-20 dark:opacity-10 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 group-hover:text-sky-500 dark:group-hover:text-sky-400 group-hover:rotate-12 transition-all" data-icon="dns">dns</span>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider group-hover:text-[#194A63] dark:group-hover:text-sky-300 transition-colors">{{ __('admin.dashboard.sys_status') }}</p>
                <div class="flex items-center gap-2 mt-1 transform group-hover:translate-x-1 transition-transform duration-300">
                    <span class="font-headline text-3xl font-black text-[#194A63] dark:text-white">{{ __('admin.dashboard.active') }}</span>
                </div>
                <div class="mt-5 flex items-center gap-2 transform group-hover:translate-x-1 transition-transform duration-300 delay-75">
                    <div class="w-2 h-2 rounded-full bg-sky-500 shadow-[0_0_8px_rgba(14,165,233,0.5)]"></div>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('admin.dashboard.all_nodes') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Water Level Card -->
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl relative overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(6,182,212,0.1)] dark:hover:border-cyan-500/30">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-cyan-50 dark:bg-cyan-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 p-5 opacity-20 dark:opacity-10 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 group-hover:text-cyan-500 dark:group-hover:text-cyan-400 group-hover:scale-110 transition-all" data-icon="waves">waves</span>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider group-hover:text-[#194A63] dark:group-hover:text-cyan-300 transition-colors">Water Level</p>
                <div class="flex items-baseline gap-1 transform group-hover:translate-x-1 transition-transform duration-300">
                    <span id="count-water" class="font-headline text-4xl font-black text-[#194A63] dark:text-white" data-target="{{ $latest_sensor->water_level ?? 0 }}" data-decimals="0">0</span>
                    <span class="text-[#194A63] dark:text-cyan-400 font-bold text-xl">%</span>
                </div>
                <div class="mt-4 flex items-center gap-2 transform group-hover:translate-x-1 transition-transform duration-300 delay-75">
                    <div class="w-2 h-2 rounded-full bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.5)] animate-pulse"></div>
                    <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400">Monitoring</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Interactive Area: Trends & Status -->
    <div class="grid grid-cols-1 gap-8 items-start relative z-10">
        
        <!-- Large Chart Card -->
        <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl transition-all duration-300">
            <div class="flex justify-between items-start mb-10">
                <div>
                    <h3 class="font-headline text-xl font-bold text-[#194A63] dark:text-white">{{ __('admin.dashboard.trend_title') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('admin.dashboard.trend_desc') }}</p>
                </div>
                @php
                    $currentRange = request('range', '24h');
                @endphp
                <div class="flex bg-slate-100 dark:bg-white/5 rounded-full p-1 border border-slate-200 dark:border-white/5">
                    <a href="{{ route('dashboard', ['range' => '24h']) }}" class="px-4 py-1.5 text-xs font-bold rounded-full transition-colors {{ $currentRange === '24h' ? 'text-white bg-[#35627C] dark:bg-sky-500 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-[#194A63] dark:hover:text-white' }}">{{ __('admin.dashboard.btn_24h') }}</a>
                    <a href="{{ route('dashboard', ['range' => '7d']) }}" class="px-4 py-1.5 text-xs font-bold rounded-full transition-colors {{ $currentRange === '7d' ? 'text-white bg-[#35627C] dark:bg-sky-500 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-[#194A63] dark:hover:text-white' }}">{{ __('admin.dashboard.btn_7d') }}</a>
                </div>
            </div>
            
            <!-- Visual Representation of Graph -->
            <div class="relative h-[400px] w-full mt-4">
                <canvas id="temperatureChart"></canvas>
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* ============================================
       COUNT-UP ANIMATION UNTUK KARTU STATISTIK
    ============================================ */
    function animateCountUp(element, target, decimals, duration) {
        const start = 0;
        const startTime = performance.now();

        // Easing: easeOutQuart — cepat di awal, melambat di akhir (terasa premium)
        function easeOutQuart(t) {
            return 1 - Math.pow(1 - t, 4);
        }

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easedProgress = easeOutQuart(progress);
            const current = start + (target - start) * easedProgress;

            element.textContent = current.toFixed(decimals);

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                element.textContent = target.toFixed(decimals);
            }
        }

        requestAnimationFrame(update);
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Jalankan animasi count-up untuk semua elemen [data-target]
        document.querySelectorAll('[data-target]').forEach(function(el) {
            const target   = parseFloat(el.dataset.target) || 0;
            const decimals = parseInt(el.dataset.decimals)  || 0;
            animateCountUp(el, target, decimals, 1800); // 1800ms durasi
        });
        const ctx = document.getElementById('temperatureChart').getContext('2d');
        const isDark = document.documentElement.classList.contains('dark');
        
        // Gradient fill
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        if (isDark) {
            gradient.addColorStop(0, 'rgba(14, 165, 233, 0.2)');   
            gradient.addColorStop(1, 'rgba(14, 165, 233, 0)');
        } else {
            gradient.addColorStop(0, 'rgba(25, 74, 99, 0.2)');   
            gradient.addColorStop(1, 'rgba(25, 74, 99, 0)');
        }

        const chartColor = isDark ? '#38bdf8' : '#194A63'; // Sky-400 for dark, primary for light
        const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_labels) !!},
                datasets: [{
                    label: 'Suhu (°C)',
                    data: {!! json_encode($chart_data) !!},
                    borderColor: chartColor,
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: isDark ? '#0f172a' : '#fff',
                    pointBorderColor: chartColor,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                        titleColor: isDark ? '#f8fafc' : '#0f172a',
                        bodyColor: isDark ? '#cbd5e1' : '#475569',
                        borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { 
                            color: gridColor,
                            borderDash: [4, 4],
                            drawBorder: false
                        },
                        ticks: { color: textColor, font: {family: "'Plus Jakarta Sans', sans-serif", weight: '600'} }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: textColor, font: {family: "'Plus Jakarta Sans', sans-serif", weight: '600'} }
                    }
                }
            }
        });
    });
</script>
@endsection
