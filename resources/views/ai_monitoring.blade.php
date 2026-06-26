@extends('layouts.admin')
@section('title', __('admin.ai_monitoring.title') . ' - Si-Tetas Admin')

@section('content')
<!-- AI Monitoring Content -->
<div class="p-8 max-w-[1440px] mx-auto relative z-10">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-[#194A63] dark:text-white tracking-tight font-headline drop-shadow-sm">{{ __('admin.ai_monitoring.title') }}</h2>
            <p class="text-slate-500 dark:text-slate-400 font-body mt-1 font-medium">{{ __('admin.ai_monitoring.subtitle') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ url()->current() }}" class="flex items-center gap-2 px-5 py-2.5 bg-[#35627C] dark:bg-sky-600 text-white rounded-full font-semibold hover:opacity-90 active:scale-95 transition-all text-sm shadow-sm">
                <span class="material-symbols-outlined text-sm" data-icon="refresh">refresh</span>
                {{ __('admin.ai_monitoring.refresh') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards - Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Today's Logs -->
        <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('admin.ai_monitoring.today_logs') }}</p>
                    <p class="text-3xl font-extrabold text-[#194A63] dark:text-white mt-2" id="todayLogsCount">-</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">assessment</span>
                </div>
            </div>
        </div>

        <!-- Latest Status -->
        <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('admin.ai_monitoring.latest_status') }}</p>
                    <p class="text-3xl font-extrabold text-[#194A63] dark:text-white mt-2" id="latestStatus">-</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" id="latestTempHumid">-</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400" id="statusIcon">check_circle</span>
                </div>
            </div>
        </div>

        <!-- Today's Anomalies -->
        <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('admin.ai_monitoring.today_anomalies') }}</p>
                    <p class="text-3xl font-extrabold text-rose-600 dark:text-rose-400 mt-2" id="todayAnomaliesCount">-</p>
                </div>
                <div class="w-12 h-12 bg-rose-100 dark:bg-rose-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400">error_outline</span>
                </div>
            </div>
        </div>

        <!-- Week Anomalies -->
        <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('admin.ai_monitoring.week_anomalies') }}</p>
                    <p class="text-3xl font-extrabold text-orange-600 dark:text-orange-400 mt-2" id="weekAnomaliesCount">-</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">warning_amber</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Load summary cards data
    document.addEventListener('DOMContentLoaded', function() {
        loadSummaryData();

        // Refresh data every 30 seconds
        setInterval(function() {
            loadSummaryData();
        }, 30000);
    });

    /**
     * Load summary cards data
     */
    function loadSummaryData() {
        fetch('{{ route("ai-monitoring.summary") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('todayLogsCount').textContent = data.today_logs;
                document.getElementById('latestStatus').textContent = data.latest_status;
                document.getElementById('latestTempHumid').textContent = `${data.latest_temp}°C / ${data.latest_humidity}%`;
                document.getElementById('todayAnomaliesCount').textContent = data.today_anomalies;
                document.getElementById('weekAnomaliesCount').textContent = data.week_anomalies;

                // Update status icon color
                const statusIcon = document.getElementById('statusIcon');
                if (data.latest_status === 'Baik') {
                    statusIcon.parentElement.className = 'w-12 h-12 bg-emerald-100 dark:bg-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0';
                    statusIcon.className = 'material-symbols-outlined text-emerald-600 dark:text-emerald-400';
                } else if (data.latest_status === 'Perhatian') {
                    statusIcon.parentElement.className = 'w-12 h-12 bg-amber-100 dark:bg-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0';
                    statusIcon.className = 'material-symbols-outlined text-amber-600 dark:text-amber-400';
                } else {
                    statusIcon.parentElement.className = 'w-12 h-12 bg-red-100 dark:bg-red-500/20 rounded-xl flex items-center justify-center flex-shrink-0';
                    statusIcon.className = 'material-symbols-outlined text-red-600 dark:text-red-400';
                }
            })
            .catch(error => console.error('Error loading summary:', error));
    }
</script>
@endsection
