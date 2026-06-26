@extends('layouts.admin')
@section('title', __('admin.anomaly_logs.title') . ' - Si-Tetas Admin')

@section('content')
<div class="p-8 max-w-[1440px] mx-auto space-y-8 relative z-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-[#194A63] dark:text-white tracking-tight font-headline drop-shadow-sm">
                {{ __('admin.anomaly_logs.title') }}
            </h2>
            <p class="text-slate-500 dark:text-slate-400 font-body mt-1 font-medium">
                Riwayat data sensor dengan status <span class="font-bold text-red-500">Critical</span> yang terdeteksi dari Firebase Realtime Database.
            </p>
        </div>
        <div class="flex gap-3">
            @if(isset($anomaly_logs) && count($anomaly_logs) > 0)
            <a href="{{ route('anomaly-logs.export-excel') }}"
               class="flex items-center gap-2 px-5 py-2.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 rounded-full font-semibold hover:opacity-80 transition-all text-sm border border-emerald-200 dark:border-emerald-700/50 shadow-sm backdrop-blur-md">
                <span class="material-symbols-outlined text-sm">download</span>
                Export CSV
            </a>
            @endif
            <a href="{{ url()->current() }}"
               class="flex items-center gap-2 px-5 py-2.5 bg-[#35627C] dark:bg-sky-600 text-white rounded-full font-semibold hover:opacity-90 active:scale-95 transition-all text-sm shadow-sm">
                <span class="material-symbols-outlined text-sm">refresh</span>
                Refresh
            </a>
        </div>
    </div>

    {{-- Firebase Credentials Warning --}}
    @if(isset($credentials_missing) && $credentials_missing)
    <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-500/30 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">warning</span>
        </div>
        <div>
            <h4 class="font-bold text-amber-900 dark:text-amber-300 mb-1">Firebase Tidak Terhubung</h4>
            <p class="text-sm text-amber-800 dark:text-amber-400">
                File kredensial Firebase tidak ditemukan di <code class="bg-amber-100 dark:bg-amber-800/40 px-1 rounded text-xs">storage/app/firebase-credentials.json</code> atau koneksi gagal. Data anomali tidak dapat ditampilkan.
            </p>
        </div>
    </div>
    @endif

    {{-- Main Table --}}
    <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden transition-all duration-300">
        <div class="p-6 border-b border-slate-100 dark:border-white/10 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-[#194A63] dark:text-white font-headline">Riwayat Anomali</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Total <span class="font-bold text-red-500">{{ count($anomaly_logs) }}</span> data anomali ditemukan dari Firebase.
                </p>
            </div>
            <div class="w-10 h-10 bg-red-100 dark:bg-red-500/20 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400">crisis_alert</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm font-body">
                <thead class="bg-slate-50 dark:bg-white/5 text-[#194A63] dark:text-slate-300 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">WAKTU</th>
                        <th class="px-6 py-4">SUHU</th>
                        <th class="px-6 py-4">KELEMBABAN</th>
                        <th class="px-6 py-4">JENIS ANOMALI</th>
                        <th class="px-6 py-4">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($anomaly_logs as $log)
                    <tr class="hover:bg-red-50/30 dark:hover:bg-red-500/5 transition-colors">

                        {{-- Waktu --}}
                        <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-400">
                            <div class="flex flex-col">
                                <span>{{ $log['time'] }}</span>
                                <span class="text-xs text-slate-400 dark:text-slate-500">{{ $log['date'] }}</span>
                            </div>
                        </td>

                        {{-- Suhu --}}
                        <td class="px-6 py-4">
                            @php
                                $isTempAnomaly = str_contains($log['anomaly_type'], 'Suhu');
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border
                                {{ $isTempAnomaly
                                    ? 'bg-red-100/80 dark:bg-red-500/20 text-red-700 dark:text-red-300 border-red-200 dark:border-red-500/30'
                                    : 'bg-blue-100/60 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-500/30' }}">
                                {{ number_format($log['temperature'], 1, '.', '') }}°C
                            </span>
                        </td>

                        {{-- Kelembaban --}}
                        <td class="px-6 py-4">
                            @php
                                $isHumidAnomaly = str_contains($log['anomaly_type'], 'Kelembaban');
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border
                                {{ $isHumidAnomaly
                                    ? 'bg-red-100/80 dark:bg-red-500/20 text-red-700 dark:text-red-300 border-red-200 dark:border-red-500/30'
                                    : 'bg-cyan-100/60 dark:bg-cyan-500/20 text-cyan-700 dark:text-cyan-300 border-cyan-200 dark:border-cyan-500/30' }}">
                                {{ number_format($log['humidity'], 1, '.', '') }}%
                            </span>
                        </td>

                        {{-- Jenis Anomali --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-300 border border-orange-200 dark:border-orange-500/30 rounded-full text-xs font-bold">
                                <span class="material-symbols-outlined text-[13px]">warning</span>
                                {{ $log['anomaly_type'] }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-500/30 rounded-full text-xs font-bold">
                                {{ $log['status'] }}
                            </span>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400 dark:text-slate-500">
                                <span class="material-symbols-outlined text-4xl">check_circle</span>
                                <p class="font-bold text-sm">Tidak ada data anomali terdeteksi.</p>
                                <p class="text-xs">Semua pembacaan sensor berada dalam rentang threshold yang ditentukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-950/30 dark:to-orange-950/30 border border-red-200 dark:border-red-500/30 rounded-2xl p-6 backdrop-blur-md">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-red-100 dark:bg-red-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400">info</span>
            </div>
            <div>
                <h4 class="font-bold text-red-900 dark:text-red-300 mb-1">Tentang Halaman Ini</h4>
                <p class="text-sm text-red-800 dark:text-red-400">
                    Data diambil dari node <code class="bg-red-100 dark:bg-red-800/30 px-1 rounded text-xs">log_sensor</code> di Firebase dan difilter secara dinamis berdasarkan threshold dari node <code class="bg-red-100 dark:bg-red-800/30 px-1 rounded text-xs">settings/threshold</code>. Hanya data yang menyimpang dari batas ideal yang ditampilkan di sini.
                </p>
            </div>
        </div>
    </div>

</div>

<style>
    @media (prefers-reduced-motion: no-preference) {
        table tbody tr { transition: all 0.2s ease; }
    }
</style>
@endsection