@extends('layouts.admin')
@section('title', 'Si-Tetas Admin Dashboard')

@section('content')
<div class="px-8 py-6 max-w-[1440px] mx-auto">
    
    <!-- Header Section -->
    <div class="mb-10">
        <h2 class="font-headline text-3xl font-extrabold text-primary tracking-tight">Ikhtisar Dashboard</h2>
        <p class="text-slate-500 mt-1">Semuanya terpantau aman dan terkendali hari ini.</p>
    </div>
    
    <!-- Bento Grid Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Suhu Card -->
        <div class="bg-surface-container-lowest p-6 rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.04)] relative overflow-hidden group hover:translate-y-[-4px] transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-4xl" data-icon="thermostat">thermostat</span>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Rata-rata Suhu</p>
            <div class="flex items-baseline gap-1">
                <span class="font-headline text-3xl font-black text-primary">{{ $latest_sensor->temperature ?? 0 }}</span>
                <span class="text-primary font-bold">°C</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-xs font-medium text-emerald-600">Stabil</span>
            </div>
        </div>
        
        <!-- Kelembapan Card -->
        <div class="bg-surface-container-lowest p-6 rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.04)] relative overflow-hidden group hover:translate-y-[-4px] transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-4xl" data-icon="humidity_percentage">humidity_percentage</span>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Kelembapan</p>
            <div class="flex items-baseline gap-1">
                <span class="font-headline text-3xl font-black text-primary">{{ $latest_sensor->humidity ?? 0 }}</span>
                <span class="text-primary font-bold">%</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-xs font-medium text-emerald-600">Optimal</span>
            </div>
        </div>
        
        
        <!-- Status Card -->
        <div class="bg-surface-container-lowest p-6 rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.04)] relative overflow-hidden group hover:translate-y-[-4px] transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-4xl" data-icon="dns">dns</span>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Status Sistem</p>
            <div class="flex items-center gap-2 mt-1">
                <span class="font-headline text-xl font-bold text-primary">Aktif</span>
            </div>
            <div class="mt-8 flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                <span class="text-xs font-medium text-slate-500">Semua Node Terhubung</span>
            </div>
        </div>
    </div>
    
    <!-- Main Interactive Area: Trends & Status -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        
        <!-- Large Chart Card -->
        <div class="lg:col-span-3 bg-surface-container-lowest rounded-lg p-8 shadow-[0_8px_24px_rgba(25,47,63,0.04)]">
            <div class="flex justify-between items-start mb-10">
                <div>
                    <h3 class="font-headline text-xl font-bold text-primary">Tren Suhu Real-time</h3>
                    <p class="text-sm text-slate-500">Visualisasi fluktuasi suhu dalam 24 jam terakhir</p>
                </div>
                <div class="flex bg-surface-container-low rounded-full p-1">
                    <button onclick="alert('Fitur filter historis akan aktif setelah integrasi Firebase/IoT selesai.')" class="px-4 py-1 text-xs font-bold text-white bg-primary rounded-full shadow-sm">24 Jam</button>
                    <button onclick="alert('Fitur filter historis akan aktif setelah integrasi Firebase/IoT selesai.')" class="px-4 py-1 text-xs font-medium text-slate-500 hover:text-primary transition-colors">7 Hari</button>
                </div>
            </div>
            
            <!-- Visual Representation of Graph -->
            <div class="relative h-[400px] w-full mt-4">
                <canvas id="temperatureChart"></canvas>
            </div>
        </div>
        
        <!-- Secondary Column: Quick Logs -->
        <div class="space-y-6">
            <div class="bg-surface-container-lowest rounded-lg p-6 shadow-[0_8px_24px_rgba(25,47,63,0.04)]">
                <h4 class="font-headline text-lg font-bold text-primary mb-4">Log Aktivitas</h4>
                
                <div class="space-y-4">
                    @forelse($activity_logs as $log)
                    <div class="flex gap-4 items-start">
                        <div class="w-2 h-2 rounded-full {{ $log->temperature > 38 ? 'bg-error shadow-[0_0_8px_rgba(186,26,26,0.4)]' : 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]' }} mt-2 shrink-0"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-700">Pembaruan Sensor Tercatat</p>
                            <p class="text-xs text-slate-500">Suhu: {{ $log->temperature }}°C, Kelembapan: {{ $log->humidity }}%</p>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-500">Belum ada log aktivitas.</p>
                    @endforelse
                </div>
                
                <a href="{{ route('monitoring') }}" class="block text-center w-full mt-6 py-2 text-xs font-bold text-primary hover:bg-slate-50 rounded-full border border-slate-100 transition-colors">Lihat Semua Log</a>
            </div>
            
            <!-- Visual Callout: Radial Progress Integration -->
            <div class="bg-primary-container rounded-lg p-6 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-1">Masa Penetasan</p>
                    <h4 class="text-2xl font-black mb-4">Hari ke-18</h4>
                    
                    <div class="w-full h-2 bg-white/20 rounded-full overflow-hidden mb-2">
                        <div class="w-[85%] h-full bg-secondary"></div>
                    </div>
                    <p class="text-xs font-medium opacity-90">3 hari menuju penetasan masal.</p>
                </div>
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('temperatureChart').getContext('2d');
        
        // Gradient fill
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(25, 74, 99, 0.2)');   
        gradient.addColorStop(1, 'rgba(25, 74, 99, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_labels) !!},
                datasets: [{
                    label: 'Suhu (°C)',
                    data: {!! json_encode($chart_data) !!},
                    borderColor: '#194A63',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#194A63',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { borderDash: [4, 4] }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
