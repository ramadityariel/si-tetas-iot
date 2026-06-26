@extends('layouts.admin')

@section('title', 'Pengaturan Threshold - Si-Tetas Admin')

@section('content')
<div class="p-8 max-w-[1440px] mx-auto space-y-8 relative z-10">

    {{-- Header --}}
    <div>
        <h2 class="text-3xl font-extrabold text-[#194A63] dark:text-white tracking-tight font-headline drop-shadow-sm">Pengaturan Threshold</h2>
        <p class="text-slate-500 dark:text-slate-400 font-body mt-1 font-medium">
            Pusat konfigurasi batas suhu dan kelembaban. Nilai ini disinkronkan ke Firebase dan ESP32.
        </p>
    </div>

    {{-- Session: Success --}}
    @if(session('success'))
    <div class="bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 p-4 rounded-xl flex items-center gap-3 border border-emerald-200 dark:border-emerald-500/30">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    {{-- Session: Error --}}
    @if(session('error'))
    <div class="bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 p-4 rounded-xl flex items-center gap-3 border border-red-200 dark:border-red-500/30">
        <span class="material-symbols-outlined">error</span>
        {{ session('error') }}
    </div>
    @endif

    {{-- Warning Box: ML Validation --}}
    <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-500/30 rounded-2xl p-5 flex items-start gap-4 backdrop-blur-md">
        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0 text-amber-600 dark:text-amber-400">
            <span class="material-symbols-outlined">warning</span>
        </div>
        <div>
            <h4 class="font-bold text-amber-900 dark:text-amber-300 mb-1">Catatan Akademis &amp; Akurasi Model</h4>
            <p class="text-sm text-amber-800 dark:text-amber-400">
                Model Machine Learning (Random Forest &amp; Isolation Forest) dilatih menggunakan asumsi nilai ideal tetap. Oleh karena itu, form ini memiliki <strong>validasi sangat ketat</strong>. Mengubah nilai secara ekstrem di luar rentang pelatihan akan menyebabkan prediksi (Baik/Perhatian/Critical) menjadi tidak valid.
            </p>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden transition-all duration-300">
        <div class="p-6 border-b border-slate-100 dark:border-white/10">
            <h3 class="text-xl font-bold text-[#194A63] dark:text-white font-headline">Konfigurasi Nilai Threshold (Firebase)</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Data disimpan ke node <code class="bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded text-xs">settings/threshold</code> di Firebase Realtime Database.</p>
        </div>

        <div class="p-6">
            <form action="{{ route('settings.threshold.store') }}" method="POST">
                @csrf

                {{-- ── SUHU ──────────────────────────────────────────────── --}}
                <h4 class="text-base font-semibold text-[#194A63] dark:text-slate-200 mb-4 pb-2 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#35627C] dark:text-sky-400 text-lg">thermostat</span>
                    Konfigurasi Suhu (°C)
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Suhu Bawah --}}
                    <div class="space-y-1.5">
                        <label for="suhu_bawah" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Suhu Bawah (Batas Minimal Ideal)
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                id="suhu_bawah"
                                name="suhu_bawah"
                                step="0.1"
                                value="{{ old('suhu_bawah', number_format($thresholds['suhu_bawah'] ?? 37.0, 1, '.', '')) }}"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/60 dark:text-white px-4 py-2.5 pr-12 text-sm focus:ring-2 focus:ring-[#35627C] focus:border-transparent outline-none transition-all @error('suhu_bawah') border-red-400 ring-2 ring-red-400/30 @enderror"
                                placeholder="37.0"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">°C</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Suhu minimum agar kondisi dianggap <span class="text-emerald-600 font-semibold">Baik</span>.</p>
                        @error('suhu_bawah')
                            <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Suhu Atas --}}
                    <div class="space-y-1.5">
                        <label for="suhu_atas" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Suhu Atas (Batas Maksimal Ideal)
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                id="suhu_atas"
                                name="suhu_atas"
                                step="0.1"
                                value="{{ old('suhu_atas', number_format($thresholds['suhu_atas'] ?? 38.0, 1, '.', '')) }}"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/60 dark:text-white px-4 py-2.5 pr-12 text-sm focus:ring-2 focus:ring-[#35627C] focus:border-transparent outline-none transition-all @error('suhu_atas') border-red-400 ring-2 ring-red-400/30 @enderror"
                                placeholder="38.0"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">°C</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Suhu maksimum agar kondisi dianggap <span class="text-emerald-600 font-semibold">Baik</span>.</p>
                        @error('suhu_atas')
                            <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ── KELEMBABAN ────────────────────────────────────────── --}}
                <h4 class="text-base font-semibold text-[#194A63] dark:text-slate-200 mb-4 pb-2 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#35627C] dark:text-sky-400 text-lg">water_drop</span>
                    Konfigurasi Kelembaban (%)
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Humid Bawah --}}
                    <div class="space-y-1.5">
                        <label for="humid_bawah" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Kelembaban Bawah (Batas Minimal Ideal)
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                id="humid_bawah"
                                name="humid_bawah"
                                step="0.1"
                                value="{{ old('humid_bawah', number_format($thresholds['humid_bawah'] ?? 55.0, 1, '.', '')) }}"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/60 dark:text-white px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-[#35627C] focus:border-transparent outline-none transition-all @error('humid_bawah') border-red-400 ring-2 ring-red-400/30 @enderror"
                                placeholder="55.0"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">%</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kelembaban minimum agar kondisi dianggap <span class="text-emerald-600 font-semibold">Baik</span>.</p>
                        @error('humid_bawah')
                            <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Humid Atas --}}
                    <div class="space-y-1.5">
                        <label for="humid_atas" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Kelembaban Atas (Batas Maksimal Ideal)
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                id="humid_atas"
                                name="humid_atas"
                                step="0.1"
                                value="{{ old('humid_atas', number_format($thresholds['humid_atas'] ?? 60.0, 1, '.', '')) }}"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/60 dark:text-white px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-[#35627C] focus:border-transparent outline-none transition-all @error('humid_atas') border-red-400 ring-2 ring-red-400/30 @enderror"
                                placeholder="60.0"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">%</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kelembaban maksimum agar kondisi dianggap <span class="text-emerald-600 font-semibold">Baik</span>.</p>
                        @error('humid_atas')
                            <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-white/10">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-semibold py-2.5 px-6 rounded-xl transition-all flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Simpan Pengaturan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
