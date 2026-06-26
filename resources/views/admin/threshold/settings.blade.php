@extends('layouts.admin')

@section('title', 'Pengaturan Threshold Dinamis')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Threshold (SSOT)</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
            Pusat konfigurasi batas suhu dan kelembaban. Nilai ini akan disinkronkan ke ESP32 dan digunakan oleh model Machine Learning.
        </p>
    </div>

    @if (session('success'))
    <div class="bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 p-4 rounded-lg mb-6 flex items-center gap-3">
        <span class="material-icons">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 p-4 rounded-lg mb-6 flex items-start gap-3 border border-amber-200 dark:border-amber-500/30">
        <span class="material-icons mt-0.5">warning</span>
        <div>
            <h4 class="font-semibold">Catatan Akademis & Akurasi Model</h4>
            <p class="text-sm mt-1">
                Model Machine Learning (Random Forest & Isolation Forest) dilatih menggunakan asumsi nilai ideal tetap. Oleh karena itu, form ini memiliki <strong>validasi sangat ketat</strong>. Mengubah nilai secara ekstrem di luar rentang pelatihan akan menyebabkan prediksi (Baik/Perhatian/Critical) menjadi tidak valid.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('settings.threshold.store') }}" method="POST">
                @csrf
                
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Konfigurasi Suhu (°C)</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Suhu Ekstrem Bawah
                        </label>
                        <input type="number" step="0.1" name="temp_min_ekstrem" 
                            value="{{ old('temp_min_ekstrem', $threshold->temp_min_ekstrem ?? 36.0) }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 @error('temp_min_ekstrem') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">(&lt; Ideal Bawah)</p>
                        @error('temp_min_ekstrem')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Suhu Ideal Bawah
                        </label>
                        <input type="number" step="0.1" name="temp_min_ideal" 
                            value="{{ old('temp_min_ideal', $threshold->temp_min_ideal ?? 37.0) }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 @error('temp_min_ideal') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">(Batas Baik Bawah)</p>
                        @error('temp_min_ideal')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Suhu Ideal Atas
                        </label>
                        <input type="number" step="0.1" name="temp_max_ideal" 
                            value="{{ old('temp_max_ideal', $threshold->temp_max_ideal ?? 38.0) }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 @error('temp_max_ideal') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">(&gt; Ideal Bawah)</p>
                        @error('temp_max_ideal')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Suhu Ekstrem Atas
                        </label>
                        <input type="number" step="0.1" name="temp_max_ekstrem" 
                            value="{{ old('temp_max_ekstrem', $threshold->temp_max_ekstrem ?? 39.0) }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 @error('temp_max_ekstrem') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">(&gt; Ideal Atas)</p>
                        @error('temp_max_ekstrem')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Konfigurasi Kelembaban (%)</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Kelembaban Ekstrem Bawah
                        </label>
                        <input type="number" step="0.1" name="hum_min_ekstrem" 
                            value="{{ old('hum_min_ekstrem', $threshold->hum_min_ekstrem ?? 50.0) }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 @error('hum_min_ekstrem') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">(&lt; Ideal Bawah)</p>
                        @error('hum_min_ekstrem')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Kelembaban Ideal Bawah
                        </label>
                        <input type="number" step="0.1" name="hum_min_ideal" 
                            value="{{ old('hum_min_ideal', $threshold->hum_min_ideal ?? 55.0) }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 @error('hum_min_ideal') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">(Batas Baik Bawah)</p>
                        @error('hum_min_ideal')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Kelembaban Ideal Atas
                        </label>
                        <input type="number" step="0.1" name="hum_max_ideal" 
                            value="{{ old('hum_max_ideal', $threshold->hum_max_ideal ?? 60.0) }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 @error('hum_max_ideal') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">(&gt; Ideal Bawah)</p>
                        @error('hum_max_ideal')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Kelembaban Ekstrem Atas
                        </label>
                        <input type="number" step="0.1" name="hum_max_ekstrem" 
                            value="{{ old('hum_max_ekstrem', $threshold->hum_max_ekstrem ?? 65.0) }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 @error('hum_max_ekstrem') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">(&gt; Ideal Atas)</p>
                        @error('hum_max_ekstrem')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t dark:border-gray-700">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded-lg transition-colors flex items-center gap-2">
                        <span class="material-icons text-sm">save</span>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
