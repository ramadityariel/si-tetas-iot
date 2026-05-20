@extends('layouts.admin')
@section('title', 'Monitoring IoT - Si-Tetas Admin')

@section('content')
<!-- Monitoring Content -->
<div class="p-8 max-w-6xl mx-auto space-y-8">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-primary tracking-tight font-headline">Monitoring IoT</h2>
            <p class="text-slate-500 font-body mt-1">Data real-time kondisi inkubator pintar Anda.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('monitoring.export-pdf') }}" class="flex items-center gap-2 px-5 py-2.5 bg-surface-container-high rounded-full text-primary font-semibold hover:opacity-80 transition-all text-sm">
                <span class="material-symbols-outlined text-sm" data-icon="download">download</span>
                Ekspor Laporan
            </a>
            <a href="{{ url()->current() }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full font-semibold hover:opacity-90 active:scale-95 transition-all text-sm">
                <span class="material-symbols-outlined text-sm" data-icon="refresh">refresh</span>
                Refresh Data
            </a>
        </div>
    </div>
    
    <!-- Summary Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-surface-container-lowest p-6 rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.06)] flex flex-col justify-between h-32">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-body">Suhu Saat Ini</span>
            <div class="flex items-baseline gap-1">
                <span class="text-4xl font-extrabold text-primary font-headline">{{ $latest_sensor->temperature ?? 0 }}</span>
                <span class="text-xl font-bold text-secondary font-headline">°C</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-6 rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.06)] flex flex-col justify-between h-32">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-body">Kelembapan</span>
            <div class="flex items-baseline gap-1">
                <span class="text-4xl font-extrabold text-primary font-headline">{{ $latest_sensor->humidity ?? 0 }}</span>
                <span class="text-xl font-bold text-secondary font-headline">%</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-6 rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.06)] flex flex-col justify-between h-32">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-body">Status Kipas</span>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full {{ ($latest_sensor->fan_status ?? false) ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.4)]' : 'bg-slate-400' }}"></span>
                <span class="text-xl font-bold text-primary font-headline">{{ ($latest_sensor->fan_status ?? false) ? 'Aktif' : 'Mati' }}</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-6 rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.06)] flex flex-col justify-between h-32 border-l-4 border-secondary">
            <span class="text-xs font-bold uppercase tracking-wider text-secondary font-body">Status Inkubasi</span>
            <span class="text-xl font-bold text-primary font-headline">Hari ke-12</span>
        </div>
    </div>
    
    <!-- Detailed Charts -->
    <div class="grid grid-cols-1 gap-8">
        
        <!-- Card 1: Riwayat Suhu -->
        <section class="bg-surface-container-lowest rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.06)] overflow-hidden">
            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-primary font-headline">Riwayat Suhu</h3>
                    <p class="text-sm text-slate-500 font-body">Stabilitas suhu dalam 24 jam terakhir</p>
                </div>
                <div class="flex items-center bg-surface-container-low rounded-lg p-1 font-body">
                    <button class="px-4 py-1.5 text-xs font-bold bg-white text-primary rounded-md shadow-sm">Hari Ini</button>
                    <button class="px-4 py-1.5 text-xs font-medium text-slate-500 hover:text-primary transition-colors">Minggu Ini</button>
                    <button class="px-4 py-1.5 text-xs font-medium text-slate-500 hover:text-primary transition-colors">Kustom</button>
                </div>
            </div>
            
            <div class="px-6 pb-8 h-80 relative">
                <canvas id="temperatureChart"></canvas>
            </div>
        </section>
        
        <!-- Card 2: Riwayat Kelembapan -->
        <section class="bg-surface-container-lowest rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.06)] overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-bold text-primary font-headline">Riwayat Kelembapan</h3>
                <p class="text-sm text-slate-500 font-body">Persentase kelembapan udara relatif</p>
            </div>
            
            <div class="px-6 pb-8 h-64">
                <canvas id="humidityChart"></canvas>
            </div>
        </section>
        
    </div>
    
    <!-- Monitoring Tables/Alerts -->
    <div id="table-container" class="bg-surface-container-lowest rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.06)] overflow-hidden relative">
        <!-- Loading Overlay -->
        <div id="table-loader" class="hidden absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex items-center justify-center">
            <span class="material-symbols-outlined animate-spin text-4xl text-primary" data-icon="progress_activity">progress_activity</span>
        </div>
        
        <div class="p-6 border-b border-slate-50">
            <h3 class="text-xl font-bold text-primary font-headline">Log Aktivitas Sensor</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm font-body">
                <thead class="bg-surface-container-low text-primary uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Sensor</th>
                        <th class="px-6 py-4">Parameter</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($table_logs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-600">{{ $log->created_at->format('H:i:s') }}</td>
                        <td class="px-6 py-4 font-semibold text-primary">DHT22 - Ruang Utama</td>
                        <td class="px-6 py-4">{{ $log->temperature }}°C / {{ $log->humidity }}%</td>
                        <td class="px-6 py-4">
                            @if($log->temperature > 38)
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Peringatan</span>
                            @else
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Optimal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-secondary hover:underline font-bold">Detail</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-slate-500">Belum ada data sensor.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-50">
            {{ $table_logs->links('pagination::tailwind') }}
        </div>
    </div>
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
        
        // Temperature Chart
        const tempCtx = document.getElementById('temperatureChart').getContext('2d');
        new Chart(tempCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Suhu (°C)',
                    data: {!! json_encode($temp_data) !!},
                    borderColor: '#194A63',
                    backgroundColor: 'rgba(25, 74, 99, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#194A63',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: false } }
            }
        });

        // Humidity Chart
        const humidCtx = document.getElementById('humidityChart').getContext('2d');
        new Chart(humidCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kelembapan (%)',
                    data: {!! json_encode($humid_data) !!},
                    borderColor: '#715B36',
                    backgroundColor: 'rgba(113, 91, 54, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#715B36',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: false } }
            }
        });
    });
</script>
@endsection
