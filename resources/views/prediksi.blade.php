@extends('layouts.admin')
@section('title', 'Prediksi & Kamera - Si-Tetas')

@section('content')
<section class="p-8 max-w-7xl mx-auto">
    <div class="mb-10">
        <h2 class="text-3xl font-extrabold text-primary tracking-tight mb-2 font-headline">Visualisasi &amp; Analisis Prediksi</h2>
        <p class="text-slate-600 max-w-2xl leading-relaxed">Pantau perkembangan embrio secara real-time melalui sensor optik dan sistem Computer Vision terintegrasi.</p>
    </div>
    
    <div class="grid grid-cols-1 gap-8">
        <div class="bg-surface-container-lowest rounded-lg shadow-[0_8px_24px_rgba(25,47,63,0.04)] overflow-hidden">
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border-b border-slate-100 gap-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">photo_camera</span>
                    <h3 class="text-lg font-bold text-primary font-headline">Prediksi Hasil Candling</h3>
                </div>
                
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <label for="tray_type" class="text-sm font-bold text-slate-700 whitespace-nowrap">Mode Analisis:</label>
                    <select id="tray_type" class="border border-slate-300 rounded-lg px-3 py-1.5 bg-slate-50 text-slate-800 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="42_butir" selected>🔲 Deteksi Massal (Model Rak Bawaan)</option>
                        <option value="1_butir">🥚 Deteksi Satuan (Model 1 Butir Bawaan)</option>
                    </select>
                </div>

                <button id="btn-snapshot" class="bg-[#35627C] text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg hover:opacity-90 active:scale-95 transition-all flex items-center gap-2 w-full sm:w-auto justify-center">
                    <span class="material-symbols-outlined text-sm">camera</span>
                    Ambil Foto
                </button>
            </div>
            
            <div class="relative bg-slate-900 flex justify-center items-center min-h-[400px]">
                <video id="camera-stream" autoplay playsinline class="w-full max-h-[500px] object-cover"></video>
                <canvas id="snapshot-canvas" class="hidden"></canvas>
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
                <div id="loading-overlay" class="hidden absolute inset-0 flex items-center justify-center bg-black/80 z-30 flex-col gap-4">
                    <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-white font-bold tracking-widest uppercase text-sm">Menganalisis Gambar...</p>
                </div>
            </div>
        </div>

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
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table id="history-table" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-slate-600 text-sm font-bold uppercase tracking-wider">
                            <th class="px-6 py-4 text-center">Tanggal/Waktu</th>
                            <th class="px-6 py-4 text-center">Nama Admin</th>
                            <th class="px-6 py-4 text-center">Hasil Prediksi</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-center">
                        @forelse($histories ?? [] as $history)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-600 text-sm font-medium">{{ $history->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $history->admin_name }}</td>
                            <td class="px-6 py-4">
                                @if($history->prediction_result == 'Fertil')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Fertil ({{ $history->confidence_score }}%)</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">Infertil ({{ $history->confidence_score }}%)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">{{ $history->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" data-image="{{ $history->snapshot_path ? asset('storage/' . $history->snapshot_path) : asset('ml_egg_prediction.png') }}" class="btn-view-image p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all border border-blue-100 shadow-sm cursor-pointer flex items-center justify-center" title="Lihat Gambar">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>

                                    <a href="{{ route('prediksi.export-data', $history->id) }}" class="p-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors border border-green-100 shadow-sm flex items-center justify-center" title="Ekspor Data Excel/CSV">
                                        <span class="material-symbols-outlined text-[18px]">text_snippet</span>
                                    </a>

                                    <a href="{{ route('prediksi.export-pdf', ['id' => $history->id]) }}" class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors border border-red-100 flex items-center justify-center" title="Ekspor PDF">
                                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                                    </a>

                                    @if(auth()->check() && auth()->user()->role == 'super_admin')
                                    <button type="button" data-id="{{ $history->id }}" class="btn-delete-history p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors border border-red-200 shadow-sm cursor-pointer flex items-center justify-center" title="Hapus Riwayat">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500 font-medium no-data-row">
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

<div id="imageViewerModal" class="hidden fixed inset-0 bg-black/80 z-[9999] flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="relative bg-white p-3 rounded-2xl max-w-3xl w-full shadow-2xl">
        <button id="btn-close-modal" type="button" class="absolute -top-12 right-0 sm:-right-12 text-white bg-black/40 hover:bg-black/80 w-10 h-10 rounded-full flex items-center justify-center transition-all cursor-pointer">
            <span class="material-symbols-outlined">close</span>
        </button>
        <div class="overflow-hidden rounded-xl bg-slate-100 max-h-[75vh] flex items-center justify-center">
            <img id="modalTargetImg" src="" alt="Preview Hasil Candling" class="w-full object-contain max-h-[73vh]" />
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Definisi Elemen Kamera
    const video = document.getElementById('camera-stream');
    const canvas = document.getElementById('snapshot-canvas');
    const btnSnapshot = document.getElementById('btn-snapshot');
    const predictionResult = document.getElementById('prediction-result');
    const snapshotImg = document.getElementById('snapshot-img');
    const predText = document.getElementById('pred-text');
    const predScore = document.getElementById('pred-score');
    const btnReset = document.getElementById('btn-reset');
    const loadingOverlay = document.getElementById('loading-overlay');
    const trayTypeSelect = document.getElementById('tray_type');

    // Definisi Elemen Modal
    const modal = document.getElementById('imageViewerModal');
    const modalImg = document.getElementById('modalTargetImg');
    const btnCloseModal = document.getElementById('btn-close-modal');
    const tableBody = document.querySelector('#history-table tbody');

    // 1. Aktivasi Kamera WebRTC
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            video.srcObject = stream;
            setTimeout(() => { video.play(); }, 150);
        })
        .catch(function(err) {
            console.error("Akses kamera ditolak/gagal: ", err);
        });
    }

    // 2. Handler untuk klik tombol "Lihat Gambar" dan "Hapus" menggunakan Event Delegation (Anti-Crash)
    if (tableBody) {
        tableBody.addEventListener('click', function(e) {
            // Deteksi klik Tombol Lihat Gambar
            const viewBtn = e.target.closest('.btn-view-image');
            if (viewBtn) {
                const src = viewBtn.getAttribute('data-image');
                if (src && modal && modalImg) {
                    modalImg.src = src;
                    modal.classList.remove('hidden');
                }
                return;
            }

            // Deteksi klik Tombol Hapus
            const deleteBtn = e.target.closest('.btn-delete-history');
            if (deleteBtn) {
                const id = deleteBtn.getAttribute('data-id');
                if (confirm('Apakah Anda yakin ingin menghapus riwayat ini? Foto fisik juga akan dihapus.')) {
                    const baseUrl = window.location.origin;
                    fetch(`${baseUrl}/admin/prediksi/destroy/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    })
                    .then(response => {
                        if (response.ok) {
                            const row = deleteBtn.closest('tr');
                            row.classList.add('opacity-0', 'scale-95', 'transition-all', 'duration-300');
                            setTimeout(() => { row.remove(); }, 300);
                        } else {
                            alert('Gagal menghapus data.');
                        }
                    })
                    .catch(err => console.error(err));
                }
            }
        });
    }

    // Close Modal Handler
    if (btnCloseModal && modal) {
        btnCloseModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            if (modalImg) modalImg.src = '';
        });
    }

    // 3. Ambil Foto & Analisis ML via AJAX
    if (btnSnapshot) {
        btnSnapshot.addEventListener('click', function() {
            if (!video.videoWidth) return;

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const dataURL = canvas.toDataURL('image/jpeg');
            loadingOverlay.classList.remove('hidden');
            snapshotImg.src = dataURL;

            fetch("{{ route('prediksi.snapshot') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    image: dataURL,
                    tray_type: trayTypeSelect.value
                })
            })
            .then(response => response.json())
            .then(data => {
                loadingOverlay.classList.add('hidden');
                if (data.success) {
                    predText.textContent = data.prediction;
                    predText.className = data.prediction.toLowerCase() === 'fertil' 
                        ? 'text-3xl font-black text-green-600 font-headline' 
                        : 'text-3xl font-black text-red-600 font-headline';
                    
                    predScore.textContent = data.score;
                    predictionResult.classList.remove('hidden');

                    if (data.annotated_image) {
                        snapshotImg.src = data.annotated_image; 
                    }

                    // Suntik baris baru ke tabel secara real-time
                    if (data.history && tableBody) {
                        if (tableBody.querySelector('.no-data-row')) {
                            tableBody.innerHTML = '';
                        }

                        const now = new Date();
                        const formattedDate = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }).replace(/\./g, ':');
                        
                        const badge = data.prediction === 'Fertil' 
                            ? `<span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Fertil (${data.score}%)</span>`
                            : `<span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">Infertil (${data.score}%)</span>`;

                        const baseUrl = window.location.origin;
                        const finalImg = data.annotated_image || (data.history.snapshot_path ? `${baseUrl}/storage/${data.history.snapshot_path}` : `${baseUrl}/ml_egg_prediction.png`);
                        
                        const isSuperAdmin = "{{ auth()->check() && auth()->user()->role == 'super_admin' ? true : false }}" === "1";
                        let delBtnHtml = '';
                        if (isSuperAdmin) {
                            delBtnHtml = `
                                <button type="button" data-id="${data.history.id}" class="btn-delete-history p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors border border-red-200 shadow-sm cursor-pointer flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            `;
                        }

                        const newRow = `
                            <tr class="hover:bg-slate-50/50 transition-colors bg-blue-50/30">
                                <td class="px-6 py-4 text-slate-600 text-sm font-medium">${formattedDate}</td>
                                <td class="px-6 py-4 font-bold text-slate-700">${data.history.admin_name || 'Admin'}</td>
                                <td class="px-6 py-4">${badge}</td>
                                <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">${data.history.status}</span></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" data-image="${finalImg}" class="btn-view-image p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all border border-blue-100 shadow-sm cursor-pointer flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </button>
                                        <a href="${baseUrl}/admin/prediksi/export-data/${data.history.id}" class="p-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors border border-green-100 shadow-sm flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">text_snippet</span>
                                        </a>
                                        ${delBtnHtml}
                                    </div>
                                </td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('afterbegin', newRow);
                    }
                } else {
                    alert("Gagal menganalisis gambar.");
                }
            })
            .catch(err => {
                loadingOverlay.classList.add('hidden');
                console.error(err);
            });
        });
    }

    if (btnReset) {
        btnReset.addEventListener('click', function() {
            predictionResult.classList.add('hidden');
            window.location.reload();
        });
    }
});
</script>
@endsection