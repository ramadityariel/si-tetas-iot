@extends('layouts.admin')
@section('title', __('admin.sensor_logs.title') . ' - Si-Tetas Admin')

@section('content')
<!-- Sensor Logs Content -->
<div class="p-8 max-w-[1440px] mx-auto space-y-8 relative z-10">
    
    <!-- Firebase Credentials Warning Alert -->
    @if(isset($credentials_missing) && $credentials_missing)
    <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-500/30 rounded-2xl p-5 flex items-start gap-4 backdrop-blur-md">
        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0 text-amber-600 dark:text-amber-400">
            <span class="material-symbols-outlined">warning</span>
        </div>
        <div>
            <h4 class="font-bold text-amber-900 dark:text-amber-300 mb-1">Firebase Credentials Missing / Connection Failed</h4>
            <p class="text-sm text-amber-800 dark:text-amber-400">
                File kredensial Firebase tidak ditemukan di <code>storage/app/firebase-credentials.json</code> atau koneksi ke Firebase Database gagal. Silakan unggah file kredensial JSON Anda atau periksa koneksi internet Anda.
            </p>
        </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-[#194A63] dark:text-white tracking-tight font-headline drop-shadow-sm">{{ __('admin.sensor_logs.title') }}</h2>
            <p class="text-slate-500 dark:text-slate-400 font-body mt-1 font-medium">Realtime database logs from Firebase Realtime Database</p>
        </div>
        <div class="flex gap-3">
            @if(isset($sensor_logs) && count($sensor_logs) > 0)
            <a href="{{ route('sensor-logs.export-excel') }}" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 rounded-full font-semibold hover:opacity-80 transition-all text-sm border border-emerald-200 dark:border-emerald-700/50 shadow-sm backdrop-blur-md">
                <span class="material-symbols-outlined text-sm" data-icon="download">download</span>
                {{ __('admin.sensor_logs.export_excel') }}
            </a>
            @endif
            <a href="{{ url()->current() }}" class="flex items-center gap-2 px-5 py-2.5 bg-[#35627C] dark:bg-sky-600 text-white rounded-full font-semibold hover:opacity-90 active:scale-95 transition-all text-sm shadow-sm">
                <span class="material-symbols-outlined text-sm" data-icon="refresh">refresh</span>
                {{ __('admin.sensor_logs.refresh') }}
            </a>
        </div>
    </div>
    
    <!-- Main Table -->
    <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden relative transition-all duration-300">
        <div class="p-6 border-b border-slate-100 dark:border-white/10">
            <h3 class="text-xl font-bold text-[#194A63] dark:text-white font-headline">{{ __('admin.sensor_logs.log_title') }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Showing up to 15 latest logs retrieved from Firebase Realtime Database</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm font-body">
                <thead class="bg-slate-50 dark:bg-white/5 text-[#194A63] dark:text-slate-300 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">TIME</th>
                        <th class="px-6 py-4">TEMPERATURE</th>
                        <th class="px-6 py-4">HUMIDITY</th>
                        <th class="px-6 py-4">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($sensor_logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-400">{{ $log['time'] }}</td>
                        <td class="px-6 py-4 dark:text-slate-300">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-100/60 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 text-xs font-semibold border border-blue-200 dark:border-blue-500/30">
                                {{ number_format($log['temperature'], 1, '.', '') }}°C
                            </span>
                        </td>
                        <td class="px-6 py-4 dark:text-slate-300">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-cyan-100/60 dark:bg-cyan-500/20 text-cyan-700 dark:text-cyan-300 text-xs font-semibold border border-cyan-200 dark:border-cyan-500/30">
                                {{ number_format($log['humidity'], 1, '.', '') }}%
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($log['status'] === 'Normal')
                            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 rounded-full text-xs font-bold">{{ $log['status'] }}</span>
                            @else
                            <span class="px-3 py-1 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-500/30 rounded-full text-xs font-bold">{{ $log['status'] }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400 font-bold">{{ __('admin.sensor_logs.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-950/30 dark:to-cyan-950/30 border border-blue-200 dark:border-blue-500/30 rounded-2xl p-6 backdrop-blur-md">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
            </div>
            <div>
                <h4 class="font-bold text-blue-900 dark:text-blue-300 mb-1">{{ __('admin.sensor_logs.info_title') }}</h4>
                <p class="text-sm text-blue-800 dark:text-blue-400">{{ __('admin.sensor_logs.info_description') }}</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media (prefers-reduced-motion: no-preference) {
        table tbody tr {
            transition: all 0.3s ease;
        }
    }
</style>
@endsection
