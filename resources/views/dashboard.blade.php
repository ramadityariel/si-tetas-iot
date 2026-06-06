@extends('layouts.admin')
@section('title', __('admin.sidebar.dashboard') . ' - Si-Tetas Admin')

@section('content')
<div class="px-8 py-6 max-w-[1440px] mx-auto">
    
    <!-- Header Section -->
    <div class="mb-10 relative z-10">
        <h2 class="font-headline text-3xl font-extrabold text-[#194A63] dark:text-white tracking-tight drop-shadow-sm">{{ __('admin.dashboard.title') }}</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ __('admin.dashboard.subtitle') }}</p>
    </div>
    
    <!-- Bento Grid Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 relative z-10">
        <!-- Suhu Card -->
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl relative overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(56,189,248,0.1)] dark:hover:border-sky-500/30">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-sky-50 dark:bg-sky-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
            <div class="absolute top-0 right-0 p-5 opacity-20 dark:opacity-10 group-hover:opacity-100 transition-opacity duration-300">
                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 group-hover:text-sky-500 dark:group-hover:text-sky-400 group-hover:animate-pulse" data-icon="thermostat">thermostat</span>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider group-hover:text-[#194A63] dark:group-hover:text-sky-300 transition-colors">{{ __('admin.dashboard.avg_temp') }}</p>
                <div class="flex items-baseline gap-1 transform group-hover:translate-x-1 transition-transform duration-300">
                    <span class="font-headline text-4xl font-black text-[#194A63] dark:text-white">{{ $latest_sensor->temperature ?? 0 }}</span>
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
                    <span class="font-headline text-4xl font-black text-[#194A63] dark:text-white">{{ $latest_sensor->humidity ?? 0 }}</span>
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
    </div>
    
    <!-- Main Interactive Area: Trends & Status -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start relative z-10">
        
        <!-- Large Chart Card -->
        <div class="lg:col-span-3 bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl transition-all duration-300">
            <div class="flex justify-between items-start mb-10">
                <div>
                    <h3 class="font-headline text-xl font-bold text-[#194A63] dark:text-white">{{ __('admin.dashboard.trend_title') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('admin.dashboard.trend_desc') }}</p>
                </div>
                <div class="flex bg-slate-100 dark:bg-white/5 rounded-full p-1 border border-slate-200 dark:border-white/5">
                    <button onclick="alert('{{ __('admin.dashboard.alert_filter') }}')" class="px-4 py-1.5 text-xs font-bold text-white bg-[#35627C] dark:bg-sky-500 rounded-full shadow-sm">{{ __('admin.dashboard.btn_24h') }}</button>
                    <button onclick="alert('{{ __('admin.dashboard.alert_filter') }}')" class="px-4 py-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-[#194A63] dark:hover:text-white transition-colors">{{ __('admin.dashboard.btn_7d') }}</button>
                </div>
            </div>
            
            <!-- Visual Representation of Graph -->
            <div class="relative h-[400px] w-full mt-4">
                <canvas id="temperatureChart"></canvas>
            </div>
        </div>
        
        <!-- Secondary Column: Quick Logs -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl transition-all duration-300">
                <h4 class="font-headline text-lg font-bold text-[#194A63] dark:text-white mb-4">{{ __('admin.dashboard.log_title') }}</h4>
                
                <div class="space-y-4">
                    @forelse($activity_logs as $log)
                    <div class="flex gap-4 items-start group">
                        <div class="w-2 h-2 rounded-full {{ $log->temperature > 38 ? 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]' : 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' }} mt-2 shrink-0"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover:text-sky-500 transition-colors">{{ __('admin.dashboard.log_update') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">{{ __('admin.dashboard.log_temp') }}: {{ $log->temperature }}°C, {{ __('admin.dashboard.log_humid') }}: {{ $log->humidity }}%</p>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider block mt-1">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 text-center py-4">{{ __('admin.dashboard.log_empty') }}</p>
                    @endforelse
                </div>
                
                <a href="{{ route('monitoring') }}" class="block text-center w-full mt-6 py-2.5 text-xs font-bold text-[#194A63] dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 rounded-xl border border-slate-200 dark:border-white/10 transition-colors">{{ __('admin.dashboard.view_all') }}</a>
            </div>
            
            <!-- Visual Callout: Radial Progress Integration -->
            <div class="bg-gradient-to-br from-[#35627C] to-[#194A63] dark:from-sky-900/80 dark:to-slate-900/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 text-white relative overflow-hidden shadow-xl">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest text-sky-200 mb-1">{{ __('admin.dashboard.hatch_period') }}</p>
                    <h4 class="text-3xl font-black font-headline mb-5">{{ __('admin.dashboard.day') }}-18</h4>
                    
                    <div class="w-full h-2.5 bg-black/20 dark:bg-black/40 rounded-full overflow-hidden mb-3">
                        <div class="w-[85%] h-full bg-gradient-to-r from-sky-400 to-white rounded-full shadow-[0_0_10px_rgba(255,255,255,0.5)]"></div>
                    </div>
                    <p class="text-xs font-bold text-sky-100">3 {{ __('admin.dashboard.days_left') }}</p>
                </div>
                <!-- Decorative Elements -->
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-sky-400/20 rounded-full blur-2xl"></div>
                <div class="absolute -left-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
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
