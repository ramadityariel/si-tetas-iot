@extends('layouts.admin')
@section('title', __('admin.monitoring.title') . ' - Si-Tetas Admin')

@section('content')
<!-- Monitoring Content -->
<div class="p-8 max-w-[1440px] mx-auto space-y-8 relative z-10">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-[#194A63] dark:text-white tracking-tight font-headline drop-shadow-sm">{{ __('admin.monitoring.title') }}</h2>
            <p class="text-slate-500 dark:text-slate-400 font-body mt-1 font-medium">{{ __('admin.monitoring.subtitle') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('monitoring.export-pdf') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-full text-[#194A63] dark:text-white font-semibold hover:opacity-80 transition-all text-sm shadow-sm backdrop-blur-md">
                <span class="material-symbols-outlined text-sm" data-icon="picture_as_pdf">picture_as_pdf</span>
                {{ __('admin.monitoring.export_pdf') }}
            </a>
            <a href="{{ route('monitoring.export-excel') }}" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 rounded-full font-semibold hover:opacity-80 transition-all text-sm border border-emerald-200 dark:border-emerald-700/50 shadow-sm backdrop-blur-md">
                <span class="material-symbols-outlined text-sm" data-icon="table_view">table_view</span>
                {{ __('admin.monitoring.export_excel') }}
            </a>
            <a href="{{ url()->current() }}" class="flex items-center gap-2 px-5 py-2.5 bg-[#35627C] dark:bg-sky-600 text-white rounded-full font-semibold hover:opacity-90 active:scale-95 transition-all text-sm shadow-sm">
                <span class="material-symbols-outlined text-sm" data-icon="refresh">refresh</span>
                {{ __('admin.monitoring.refresh') }}
            </a>
        </div>
    </div>
    
    <!-- Summary Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl flex flex-col justify-between h-32 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(56,189,248,0.1)] dark:hover:border-sky-500/30 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-sky-50 dark:bg-sky-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex justify-between items-start">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 font-body">{{ __('admin.monitoring.current_temp') }}</span>
                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 group-hover:text-sky-500 dark:group-hover:text-sky-400 transition-colors duration-300">thermostat</span>
            </div>
            <div class="flex items-baseline gap-1 relative z-10 transform group-hover:translate-x-1 transition-transform duration-300">
                <span class="text-4xl font-extrabold text-[#194A63] dark:text-white font-headline">{{ $latest_sensor->temperature ?? 0 }}</span>
                <span class="text-xl font-bold text-[#35627C] dark:text-sky-400 font-headline">°C</span>
            </div>
        </div>
        
        <!-- Card 2 -->
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl flex flex-col justify-between h-32 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(52,211,153,0.1)] dark:hover:border-emerald-500/30 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex justify-between items-start">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 font-body">{{ __('admin.monitoring.humidity') }}</span>
                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors duration-300">humidity_percentage</span>
            </div>
            <div class="flex items-baseline gap-1 relative z-10 transform group-hover:translate-x-1 transition-transform duration-300">
                <span class="text-4xl font-extrabold text-[#194A63] dark:text-white font-headline">{{ $latest_sensor->humidity ?? 0 }}</span>
                <span class="text-xl font-bold text-[#35627C] dark:text-sky-400 font-headline">%</span>
            </div>
        </div>
        
        <!-- Card 3 -->
        <div class="group bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl flex flex-col justify-between h-32 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(244,63,94,0.1)] dark:hover:border-rose-500/30 relative overflow-hidden">
            <div class="absolute -left-4 -top-4 w-20 h-20 bg-rose-50 dark:bg-rose-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex justify-between items-start">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 font-body">{{ __('admin.monitoring.fan_status') }}</span>
                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 group-hover:text-rose-500 dark:group-hover:text-rose-400 group-hover:rotate-180 transition-all duration-700">mode_fan</span>
            </div>
            <div class="flex items-center gap-2 relative z-10 transform group-hover:translate-x-1 transition-transform duration-300">
                <span class="w-3 h-3 rounded-full {{ ($latest_sensor->fan_status ?? false) ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.4)] animate-pulse' : 'bg-slate-400 dark:bg-slate-600' }}"></span>
                <span class="text-2xl font-black text-[#194A63] dark:text-white font-headline">{{ ($latest_sensor->fan_status ?? false) ? __('admin.dashboard.active') : 'Mati' }}</span>
            </div>
        </div>
        
        <!-- Card 4 -->
        <div class="group bg-gradient-to-br from-white to-slate-50 dark:from-slate-900/40 dark:to-slate-900/80 backdrop-blur-md border-y border-r border-l-4 border-slate-100 dark:border-white/10 border-l-[#715B36] dark:border-l-sky-500 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl flex flex-col justify-between h-32 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(14,165,233,0.2)] dark:hover:border-sky-500/50 relative overflow-hidden">
            <div class="absolute right-0 bottom-0 w-32 h-32 bg-gradient-to-tl from-sky-100 to-transparent dark:from-sky-500/20 dark:to-transparent rounded-tl-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
            <div class="relative z-10 flex justify-between items-start">
                <span class="text-xs font-bold uppercase tracking-wider text-[#715B36] dark:text-sky-400 font-body">{{ __('admin.monitoring.incubation_status') }}</span>
                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 group-hover:text-[#715B36] dark:group-hover:text-sky-400 group-hover:animate-bounce transition-all duration-300">egg_alt</span>
            </div>
            <div class="relative z-10 transform group-hover:translate-x-1 transition-transform duration-300">
                <span class="text-3xl font-black text-[#194A63] dark:text-white font-headline">{{ __('admin.dashboard.day') }}-12</span>
            </div>
        </div>
    </div>
    
    <!-- Detailed Charts -->
    <div class="grid grid-cols-1 gap-8">
        
        <!-- Card 1: Riwayat Suhu -->
        <section class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden transition-all duration-300">
            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-[#194A63] dark:text-white font-headline">{{ __('admin.monitoring.temp_history') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-body">{{ __('admin.monitoring.temp_desc') }}</p>
                </div>
                <div class="flex items-center bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/5 rounded-full p-1 font-body">
                    <button class="px-4 py-1.5 text-xs font-bold bg-[#35627C] dark:bg-sky-500 text-white rounded-full shadow-sm">{{ __('admin.monitoring.today') }}</button>
                    <button class="px-4 py-1.5 text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-[#194A63] dark:hover:text-white transition-colors">{{ __('admin.monitoring.this_week') }}</button>
                    <button class="px-4 py-1.5 text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-[#194A63] dark:hover:text-white transition-colors">{{ __('admin.monitoring.custom') }}</button>
                </div>
            </div>
            
            <div class="px-6 pb-8 h-80 relative">
                <canvas id="temperatureChart"></canvas>
            </div>
        </section>
        
        <!-- Card 2: Riwayat Kelembapan -->
        <section class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden transition-all duration-300">
            <div class="p-6">
                <h3 class="text-xl font-bold text-[#194A63] dark:text-white font-headline">{{ __('admin.monitoring.humid_history') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-body">{{ __('admin.monitoring.humid_desc') }}</p>
            </div>
            
            <div class="px-6 pb-8 h-64">
                <canvas id="humidityChart"></canvas>
            </div>
        </section>
        
    </div>
    
    <!-- Monitoring Tables/Alerts -->
    <div id="table-container" class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden relative transition-all duration-300">
        <!-- Loading Overlay -->
        <div id="table-loader" class="hidden absolute inset-0 bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm z-10 flex items-center justify-center">
            <span class="material-symbols-outlined animate-spin text-4xl text-[#35627C] dark:text-sky-400" data-icon="progress_activity">progress_activity</span>
        </div>
        
        <div class="p-6 border-b border-slate-100 dark:border-white/10">
            <h3 class="text-xl font-bold text-[#194A63] dark:text-white font-headline">{{ __('admin.monitoring.log_title') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm font-body">
                <thead class="bg-slate-50 dark:bg-white/5 text-[#194A63] dark:text-slate-300 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">{{ __('admin.monitoring.time') }}</th>
                        <th class="px-6 py-4">{{ __('admin.monitoring.sensor') }}</th>
                        <th class="px-6 py-4">{{ __('admin.monitoring.parameter') }}</th>
                        <th class="px-6 py-4">{{ __('admin.monitoring.status') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('admin.monitoring.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($table_logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-400">{{ $log->created_at->format('H:i:s') }}</td>
                        <td class="px-6 py-4 font-semibold text-[#194A63] dark:text-white">DHT22 - Ruang Utama</td>
                        <td class="px-6 py-4 dark:text-slate-300">{{ $log->temperature }}°C / {{ $log->humidity }}%</td>
                        <td class="px-6 py-4">
                            @if($log->temperature > 38)
                            <span class="px-3 py-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 rounded-full text-xs font-bold">{{ __('admin.monitoring.warning') }}</span>
                            @else
                            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 rounded-full text-xs font-bold">{{ __('admin.monitoring.optimal') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-[#715B36] dark:text-sky-400 hover:underline font-bold">{{ __('admin.monitoring.detail') }}</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400 font-bold">{{ __('admin.monitoring.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-100 dark:border-white/10">
            {{ $table_logs->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- AJAX Pagination Logic ---
        const tableContainer = document.getElementById('table-container');
        
        tableContainer.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            
            // Check if it's a pagination link
            if (link && link.href && link.href.includes('page=')) {
                e.preventDefault();
                
                // Show loader
                const loader = document.getElementById('table-loader');
                if (loader) loader.classList.remove('hidden');
                
                fetch(link.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContainer = doc.getElementById('table-container');
                        
                        if (newContainer) {
                            tableContainer.innerHTML = newContainer.innerHTML;
                        }
                    })
                    .catch(err => console.error("Failed to fetch page:", err))
                    .finally(() => {
                        const loader = document.getElementById('table-loader');
                        if (loader) loader.classList.add('hidden');
                    });
            }
        });

        // --- Chart Logic ---
        const labels = {!! json_encode($chart_labels) !!};
        const isDark = document.documentElement.classList.contains('dark');
        
        const tempChartColor = isDark ? '#38bdf8' : '#194A63'; // Sky-400 vs Primary
        const humidChartColor = isDark ? '#34d399' : '#715B36'; // Emerald-400 vs Secondary
        
        const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
        const textColor = isDark ? '#94a3b8' : '#64748b';
        
        // Tooltip options
        const tooltipOptions = {
            backgroundColor: isDark ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.9)',
            titleColor: isDark ? '#f8fafc' : '#0f172a',
            bodyColor: isDark ? '#cbd5e1' : '#475569',
            borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
            borderWidth: 1,
            padding: 12,
            boxPadding: 6
        };

        const scalesOptions = {
            y: {
                beginAtZero: false,
                grid: { color: gridColor, borderDash: [4, 4], drawBorder: false },
                ticks: { color: textColor, font: {family: "'Plus Jakarta Sans', sans-serif", weight: '600'} }
            },
            x: {
                grid: { display: false, drawBorder: false },
                ticks: { color: textColor, font: {family: "'Plus Jakarta Sans', sans-serif", weight: '600'} }
            }
        };

        // Temperature Chart
        const tempCtx = document.getElementById('temperatureChart').getContext('2d');
        let tempGradient = tempCtx.createLinearGradient(0, 0, 0, 400);
        if(isDark) {
            tempGradient.addColorStop(0, 'rgba(56, 189, 248, 0.2)');
            tempGradient.addColorStop(1, 'rgba(56, 189, 248, 0)');
        } else {
            tempGradient.addColorStop(0, 'rgba(25, 74, 99, 0.2)');
            tempGradient.addColorStop(1, 'rgba(25, 74, 99, 0)');
        }

        new Chart(tempCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Suhu (°C)',
                    data: {!! json_encode($temp_data) !!},
                    borderColor: tempChartColor,
                    backgroundColor: tempGradient,
                    borderWidth: 3,
                    pointBackgroundColor: isDark ? '#0f172a' : '#fff',
                    pointBorderColor: tempChartColor,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipOptions },
                scales: scalesOptions
            }
        });

        // Humidity Chart
        const humidCtx = document.getElementById('humidityChart').getContext('2d');
        let humidGradient = humidCtx.createLinearGradient(0, 0, 0, 400);
        if(isDark) {
            humidGradient.addColorStop(0, 'rgba(52, 211, 153, 0.2)');
            humidGradient.addColorStop(1, 'rgba(52, 211, 153, 0)');
        } else {
            humidGradient.addColorStop(0, 'rgba(113, 91, 54, 0.2)');
            humidGradient.addColorStop(1, 'rgba(113, 91, 54, 0)');
        }

        new Chart(humidCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kelembapan (%)',
                    data: {!! json_encode($humid_data) !!},
                    borderColor: humidChartColor,
                    backgroundColor: humidGradient,
                    borderWidth: 3,
                    pointBackgroundColor: isDark ? '#0f172a' : '#fff',
                    pointBorderColor: humidChartColor,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipOptions },
                scales: scalesOptions
            }
        });
    });
</script>
@endsection
