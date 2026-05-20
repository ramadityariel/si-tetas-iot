@extends('layouts.admin')
@section('title', 'Prediksi & Kamera - Si-Tetas Admin')

@section('content')
<section class="p-8 max-w-7xl mx-auto">
    <div class="mb-10">
        <h2 class="text-3xl font-extrabold text-primary tracking-tight mb-2 font-headline">Visualisasi &amp; Analisis Prediksi</h2>
        <p class="text-slate-600 max-w-2xl leading-relaxed">Pantau perkembangan embrio secara real-time melalui sensor optik dan sistem Computer Vision terintegrasi.</p>
    </div>
    
    <!-- Dashboard Grid (Sekarang 1 Kolom Penuh untuk Kamera & Riwayat) -->
    <div class="grid grid-cols-1 gap-8">
        <!-- Kamera WebRTC & Snapshot -->
        <div class="bg-surface-container-lowest rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.04)] overflow-hidden">
            <div class="p-6 flex justify-between items-center bg-white border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">photo_camera</span>
                    <h3 class="text-lg font-bold text-primary font-headline">Snapshot Kamera & Prediksi ML</h3>
                </div>
                <button id="btn-snapshot" class="bg-[#35627C] text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg hover:opacity-90 active:scale-95 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">camera</span>
                    Ambil Foto
                </button>
            </div>
            
            <div class="relative bg-slate-900 flex justify-center items-center min-h-[400px]">
                <!-- Video Stream -->
                <video id="camera-stream" autoplay playsinline class="w-full max-h-[500px] object-cover"></video>
                <!-- Canvas (Hidden) -->
                <canvas id="snapshot-canvas" class="hidden"></canvas>
                <!-- Prediksi Overlay -->
                <div id="prediction-result" class="hidden absolute inset-0 flex items-center justify-center bg-black/60 z-20 backdrop-blur-sm">
                    <div class="text-center">
                        <img id="snapshot-img" class="max-w-xs md:max-w-md border-4 border-white rounded-lg shadow-2xl mb-6 mx-auto object-cover"/>
                        <div class="bg-white px-8 py-4 rounded-xl inline-block shadow-lg">
                            <h4 class="text-3xl font-black text-primary font-headline" id="pred-text">Fertil</h4>
                            <p class="text-slate-500 font-bold">Confidence Score: <span id="pred-score" class="text-primary">95</span>%</p>
                        </div>
                        <br>
                        <button id="btn-reset" class="mt-6 border border-white/40 text-white px-6 py-2 rounded-full text-sm font-bold hover:bg-white hover:text-black transition-all">Tutup Hasil</button>
                    </div>
                </div>
                <!-- Loading -->
                <div id="loading-overlay" class="hidden absolute inset-0 flex items-center justify-center bg-black/80 z-30 flex-col gap-4">
                    <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-white font-bold tracking-widest uppercase text-sm">Menganalisis Gambar...</p>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Candling -->
        <div class="bg-surface-container-lowest rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.04)] overflow-hidden">
            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center bg-white border-b border-slate-100 gap-4">
                <h3 class="text-xl font-bold text-primary font-headline">Riwayat Candling</h3>
                
                <form action="{{ route('prediksi') }}" method="GET" class="flex flex-wrap gap-2 items-center">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 cursor-pointer"/>
                    <span class="text-slate-400">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 cursor-pointer"/>
                    
                    <select name="quick_filter" onchange="this.form.submit()" class="px-4 py-2 bg-surface-container-low text-primary text-sm font-bold rounded-lg hover:bg-surface-container-high transition-colors border-none focus:ring-0 cursor-pointer text-center ml-2">
                        <option value="">Filter</option>
                        <option value="1_hari" {{ request('quick_filter') == '1_hari' ? 'selected' : '' }}>1 hari terakhir</option>
                        <option value="1_minggu" {{ request('quick_filter') == '1_minggu' ? 'selected' : '' }}>1 minggu terakhir</option>
                        <option value="1_bulan" {{ request('quick_filter') == '1_bulan' ? 'selected' : '' }}>1 bulan terakhir</option>
                        <option value="3_bulan" {{ request('quick_filter') == '3_bulan' ? 'selected' : '' }}>3 bulan terakhir</option>
                    </select>
                    
                    <a href="{{ route('prediksi.export-pdf', request()->all()) }}" class="px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-lg hover:bg-red-100 transition-colors flex items-center gap-2 border border-red-100 ml-2">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Ekspor Data
                    </a>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-slate-600 text-sm font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">Tanggal/Waktu</th>
                            <th class="px-6 py-4">Nama Admin</th>
                            <th class="px-6 py-4">Hasil Prediksi</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($histories ?? [] as $history)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-600 text-sm font-medium">{{ $history->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $history->admin_name }}</td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                @if($history->prediction_result == 'Fertil')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Fertil ({{ $history->confidence_score }}%)</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">Infertil ({{ $history->confidence_score }}%)</span>
                                @endif
                                <a href="{{ asset('ml_egg_prediction.png') }}" target="_blank" class="inline-flex items-center gap-1 text-primary hover:text-blue-600 text-xs font-bold underline">
                                    <span class="material-symbols-outlined text-[14px]">image</span> Lihat Gambar
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">{{ $history->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-slate-500 font-medium">
                                @if(request('start_date') || request('end_date') || request('quick_filter'))
                                    Tidak ada data pada tanggal tersebut
                                @else
                                    Belum ada data riwayat candling.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($histories) && $histories instanceof \Illuminate\Pagination\LengthAwarePaginator && $histories->hasPages())
            <div class="p-6 bg-white border-t border-slate-100">
                {{ $histories->links() }}
            </div>
            @endif
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const video = document.getElementById('camera-stream');
    const canvas = document.getElementById('snapshot-canvas');
    const btnSnapshot = document.getElementById('btn-snapshot');
    const predictionResult = document.getElementById('prediction-result');
    const snapshotImg = document.getElementById('snapshot-img');
    const predText = document.getElementById('pred-text');
    const predScore = document.getElementById('pred-score');
    const btnReset = document.getElementById('btn-reset');
    const loadingOverlay = document.getElementById('loading-overlay');

    // Akses Kamera WebRTC
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
            video.srcObject = stream;
            video.play();
        }).catch(function(err) {
            alert("Gagal mengakses kamera: " + err);
        });
    }

    // Ambil Foto & Kirim AJAX
    btnSnapshot.addEventListener('click', function() {
        if (!video.videoWidth) return; // kamera belum siap

        // Atur ukuran canvas sama dengan video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Gambar frame dari video ke canvas
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert ke base64
        const dataURL = canvas.toDataURL('image/jpeg');
        
        // Tampilkan loading
        loadingOverlay.classList.remove('hidden');
        
        // Set gambar preview (menimpa/overlay di atas video)
        snapshotImg.src = dataURL;

        // Kirim via AJAX
        fetch("{{ route('prediksi.snapshot') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ image: dataURL })
        })
        .then(response => response.json())
        .then(data => {
            loadingOverlay.classList.add('hidden');
            if (data.success) {
                // Tampilkan hasil
                predText.textContent = data.prediction;
                predText.className = data.prediction === 'Fertil' ? 'text-3xl font-black text-green-600 font-headline' : 'text-3xl font-black text-red-600 font-headline';
                predScore.textContent = data.score;
                predictionResult.classList.remove('hidden');
            } else {
                alert("Gagal menganalisis.");
            }
        })
        .catch(err => {
            loadingOverlay.classList.add('hidden');
            alert("Terjadi kesalahan sistem.");
            console.error(err);
        });
    });

    // Reset Hasil
    btnReset.addEventListener('click', function() {
        predictionResult.classList.add('hidden');
        // Reload page untuk memunculkan history terbaru
        window.location.reload();
    });
});
</script>
@endsection
