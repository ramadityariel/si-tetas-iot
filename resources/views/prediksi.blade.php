@extends('layouts.admin')
@section('title', __('admin.sidebar.prediction') . ' - Si-Tetas')

@section('content')
<section class="p-8 max-w-[1440px] mx-auto relative z-10">
    <div class="mb-10">
        <h2 class="text-3xl font-extrabold text-[#194A63] dark:text-white tracking-tight mb-2 font-headline drop-shadow-sm">{{ __('admin.prediction.title') }}</h2>
        <p class="text-slate-500 dark:text-slate-400 font-medium max-w-2xl leading-relaxed">{{ __('admin.prediction.subtitle') }}</p>
    </div>
    
    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden transition-all duration-300">
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 dark:border-white/10 gap-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#194A63] dark:text-sky-400">photo_camera</span>
                    <h3 class="text-lg font-bold text-[#194A63] dark:text-white font-headline">{{ __('admin.prediction.camera_title') }}</h3>
                </div>
                
                <input type="hidden" id="tray_type" value="42_butir">

                <button id="btn-snapshot" class="bg-[#35627C] dark:bg-sky-600 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg hover:opacity-90 active:scale-95 transition-all flex items-center gap-2 w-full sm:w-auto justify-center">
                    <span class="material-symbols-outlined text-sm">camera</span>
                    {{ __('admin.prediction.take_photo') }}
                </button>
            </div>
            
            <div class="relative bg-slate-900 flex justify-center items-center min-h-[400px]">
                <video id="camera-stream" autoplay playsinline muted class="w-full max-h-[500px] object-cover"></video>
                <canvas id="snapshot-canvas" class="hidden"></canvas>
                <div id="prediction-result" class="hidden absolute inset-0 flex items-center justify-center bg-black/60 z-20 backdrop-blur-sm">
                    <div class="text-center">
                        <img id="snapshot-img" class="max-w-xs md:max-w-md border-4 border-white/20 dark:border-slate-800 rounded-xl shadow-2xl mb-6 mx-auto object-cover"/>
                        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-md px-8 py-4 rounded-xl inline-block shadow-xl border border-white/20">
                            <h4 class="text-3xl font-black text-[#194A63] dark:text-sky-400 font-headline" id="pred-text">{{ __('admin.prediction.fertil') }}</h4>
                            <p class="text-slate-500 dark:text-slate-400 font-bold">{{ __('admin.prediction.confidence') }} <span id="pred-score" class="text-[#35627C] dark:text-sky-300">95</span>%</p>
                        </div>
                        <br>
                        <button id="btn-reset" class="mt-6 border border-white/40 text-white px-6 py-2 rounded-full text-sm font-bold hover:bg-white hover:text-black transition-all shadow-lg">{{ __('admin.prediction.close_result') }}</button>
                    </div>
                </div>
                <div id="loading-overlay" class="hidden absolute inset-0 flex items-center justify-center bg-black/80 z-30 flex-col gap-4 backdrop-blur-sm">
                    <style>
                        @keyframes hatch {
                            0% { transform: rotate(0deg) scale(1); }
                            15% { transform: rotate(15deg) scale(1.05); }
                            30% { transform: rotate(-10deg) scale(1); }
                            45% { transform: rotate(5deg) scale(1.02); }
                            60% { transform: rotate(-5deg) scale(1); }
                            75% { transform: rotate(2deg) scale(1.01); }
                            100% { transform: rotate(0deg) scale(1); }
                        }
                        .animate-hatch {
                            animation: hatch 1.5s ease-in-out infinite;
                            transform-origin: bottom center;
                        }
                    </style>
                    <div class="w-16 h-16 animate-hatch">
                        <svg viewBox="0 0 100 100" class="w-full h-full drop-shadow-[0_0_15px_rgba(254,240,138,0.4)]" xmlns="http://www.w3.org/2000/svg">
                            <!-- Egg body -->
                            <path fill="#fef08a" d="M50 5C30 5 20 40 20 65C20 85 30 95 50 95C70 95 80 85 80 65C80 40 70 5 50 5Z" />
                            <!-- Egg inner shading -->
                            <path fill="#fde047" d="M50 15C38 15 30 45 30 65C30 80 38 88 50 88C62 88 70 80 70 65C70 45 62 15 50 15Z" opacity="0.6"/>
                            <!-- Crack line -->
                            <path fill="none" stroke="#ca8a04" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M 35 45 L 48 53 L 42 66 L 58 73" opacity="0.8"/>
                        </svg>
                    </div>
                    <p class="text-white font-bold tracking-widest uppercase text-sm">{{ __('admin.prediction.analyzing') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden transition-all duration-300">
            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 dark:border-white/10 gap-4">
                <h3 class="text-xl font-bold text-[#194A63] dark:text-white font-headline">{{ __('admin.prediction.history_title') }}</h3>
                
                <form action="{{ route('prediksi') }}" method="GET" class="flex flex-wrap gap-2 items-center">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer shadow-sm"/>
                    <span class="text-slate-400">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer shadow-sm"/>
                    
                    <select name="quick_filter" onchange="this.form.submit()" class="px-4 py-2 bg-slate-100 dark:bg-white/5 text-[#194A63] dark:text-sky-400 text-sm font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-white/10 transition-colors border border-transparent dark:border-white/10 focus:ring-0 cursor-pointer text-center ml-2 shadow-sm">
                        <option value="">{{ __('admin.prediction.filter') }}</option>
                        <option value="1_hari" {{ request('quick_filter') == '1_hari' ? 'selected' : '' }}>{{ __('admin.prediction.1_day') }}</option>
                        <option value="1_minggu" {{ request('quick_filter') == '1_minggu' ? 'selected' : '' }}>{{ __('admin.prediction.1_week') }}</option>
                        <option value="1_bulan" {{ request('quick_filter') == '1_bulan' ? 'selected' : '' }}>{{ __('admin.prediction.1_month') }}</option>
                        <option value="3_bulan" {{ request('quick_filter') == '3_bulan' ? 'selected' : '' }}>{{ __('admin.prediction.3_months') }}</option>
                    </select>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table id="history-table" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-white/5 text-slate-600 dark:text-slate-300 text-sm font-bold uppercase tracking-wider">
                            <th class="px-6 py-4 text-center">{{ __('admin.prediction.col_datetime') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('admin.prediction.col_admin') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('admin.prediction.col_result') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('admin.prediction.col_status') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('admin.prediction.col_action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-center">
                        @forelse($histories ?? [] as $history)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-sm font-medium">{{ $history->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">{{ $history->admin_name }}</td>
                            <td class="px-6 py-4">
                                @if($history->prediction_result == 'Fertil')
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-500/30 text-xs font-bold rounded-full">{{ __('admin.prediction.fertil') }} ({{ $history->confidence_score }}%)</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/30 text-xs font-bold rounded-full">{{ __('admin.prediction.infertil') }} ({{ $history->confidence_score }}%)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10 text-xs font-bold rounded-full">{{ $history->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" data-image="{{ $history->snapshot_path ? asset('storage/' . $history->snapshot_path) : asset('ml_egg_prediction.png') }}" class="btn-view-image p-2 text-blue-600 dark:text-sky-400 bg-blue-50 dark:bg-sky-500/10 hover:bg-blue-100 dark:hover:bg-sky-500/20 rounded-lg transition-all border border-blue-100 dark:border-sky-500/30 shadow-sm cursor-pointer flex items-center justify-center" title="{{ __('admin.prediction.view_img') }}">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>

                                    <a href="{{ route('prediksi.export-data', $history->id) }}" class="p-2 text-green-600 dark:text-emerald-400 bg-green-50 dark:bg-emerald-500/10 hover:bg-green-100 dark:hover:bg-emerald-500/20 rounded-lg transition-colors border border-green-100 dark:border-emerald-500/30 shadow-sm flex items-center justify-center" title="{{ __('admin.prediction.export_csv') }}">
                                        <span class="material-symbols-outlined text-[18px]">text_snippet</span>
                                    </a>

                                    <a href="{{ route('prediksi.export-pdf', ['id' => $history->id]) }}" class="p-2 text-red-600 dark:text-rose-400 bg-red-50 dark:bg-rose-500/10 hover:bg-red-100 dark:hover:bg-rose-500/20 rounded-lg transition-colors border border-red-100 dark:border-rose-500/30 flex items-center justify-center" title="{{ __('admin.prediction.export_pdf') }}">
                                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                                    </a>

                                    @if(auth()->check() && auth()->user()->role == 'super_admin')
                                    <button type="button" data-id="{{ $history->id }}" class="btn-delete-history p-2 text-red-600 dark:text-rose-400 bg-red-50 dark:bg-rose-500/10 hover:bg-red-100 dark:hover:bg-rose-500/20 rounded-lg transition-colors border border-red-200 dark:border-rose-500/30 shadow-sm cursor-pointer flex items-center justify-center" title="{{ __('admin.prediction.delete') }}">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500 dark:text-slate-400 font-medium no-data-row">
                                @if(request('start_date') || request('end_date') || request('quick_filter'))
                                    {{ __('admin.prediction.no_data_date') }}
                                @else
                                    {{ __('admin.prediction.no_history') }}
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($histories) && $histories instanceof \Illuminate\Pagination\LengthAwarePaginator && $histories->hasPages())
            <div class="p-6 border-t border-slate-100 dark:border-white/10">
                {{ $histories->links('pagination::tailwind') }}
            </div>
            @endif
        </div>
    </div>
</section>

<div id="imageViewerModal" class="hidden fixed inset-0 bg-black/80 z-[9999] flex items-center justify-center p-4 backdrop-blur-md transition-opacity">
    <div class="relative bg-white/10 border border-white/20 p-3 rounded-2xl max-w-3xl w-full shadow-2xl backdrop-blur-xl">
        <button id="btn-close-modal" type="button" class="absolute -top-12 right-0 sm:-right-12 text-white bg-black/40 hover:bg-white/20 w-10 h-10 rounded-full flex items-center justify-center transition-all cursor-pointer border border-white/10">
            <span class="material-symbols-outlined">close</span>
        </button>
        <div class="overflow-hidden rounded-xl bg-slate-900/50 max-h-[75vh] flex items-center justify-center">
            <img id="modalTargetImg" src="" alt="Preview Hasil Candling" class="w-full object-contain max-h-[73vh] shadow-inner" />
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
            video.muted = true;
            video.play().catch(e => console.error("Autoplay failed:", e));
        })
        .catch(function(err) {
            console.error("Akses kamera ditolak/gagal: ", err);
        });
    }

    // 2. Handler untuk klik tombol
    if (tableBody) {
        tableBody.addEventListener('click', function(e) {
            const viewBtn = e.target.closest('.btn-view-image');
            if (viewBtn) {
                const src = viewBtn.getAttribute('data-image');
                if (src && modal && modalImg) {
                    modalImg.src = src;
                    modal.classList.remove('hidden');
                }
                return;
            }

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
                        ? 'text-3xl font-black text-green-600 dark:text-green-400 font-headline' 
                        : 'text-3xl font-black text-red-600 dark:text-red-400 font-headline';
                    
                    predScore.textContent = data.score;
                    predictionResult.classList.remove('hidden');

                    if (data.annotated_image) {
                        snapshotImg.src = data.annotated_image; 
                    }

                    if (data.history && tableBody) {
                        if (tableBody.querySelector('.no-data-row')) {
                            tableBody.innerHTML = '';
                        }

                        const now = new Date();
                        const formattedDate = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }).replace(/\./g, ':');
                        
                        const badge = data.prediction === 'Fertil' 
                            ? `<span class="px-3 py-1 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-500/30 text-xs font-bold rounded-full">Fertil (${data.score}%)</span>`
                            : `<span class="px-3 py-1 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/30 text-xs font-bold rounded-full">Infertil (${data.score}%)</span>`;

                        const baseUrl = window.location.origin;
                        const finalImg = data.annotated_image || (data.history.snapshot_path ? `${baseUrl}/storage/${data.history.snapshot_path}` : `${baseUrl}/ml_egg_prediction.png`);
                        
                        const isSuperAdmin = "{{ auth()->check() && auth()->user()->role == 'super_admin' ? true : false }}" === "1";
                        let delBtnHtml = '';
                        if (isSuperAdmin) {
                            delBtnHtml = `
                                <button type="button" data-id="${data.history.id}" class="btn-delete-history p-2 text-red-600 dark:text-rose-400 bg-red-50 dark:bg-rose-500/10 hover:bg-red-100 dark:hover:bg-rose-500/20 rounded-lg transition-colors border border-red-200 dark:border-rose-500/30 shadow-sm cursor-pointer flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            `;
                        }

                        const newRow = `
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors bg-sky-50/30 dark:bg-sky-900/20">
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-sm font-medium">${formattedDate}</td>
                                <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">${data.history.admin_name || 'Admin'}</td>
                                <td class="px-6 py-4">${badge}</td>
                                <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10 text-xs font-bold rounded-full">${data.history.status}</span></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" data-image="${finalImg}" class="btn-view-image p-2 text-blue-600 dark:text-sky-400 bg-blue-50 dark:bg-sky-500/10 hover:bg-blue-100 dark:hover:bg-sky-500/20 rounded-lg transition-all border border-blue-100 dark:border-sky-500/30 shadow-sm cursor-pointer flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </button>
                                        <a href="${baseUrl}/admin/prediksi/export-data/${data.history.id}" class="p-2 text-green-600 dark:text-emerald-400 bg-green-50 dark:bg-emerald-500/10 hover:bg-green-100 dark:hover:bg-emerald-500/20 rounded-lg transition-colors border border-green-100 dark:border-emerald-500/30 shadow-sm flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">text_snippet</span>
                                        </a>
                                        <a href="${baseUrl}/admin/prediksi/export-pdf/${data.history.id}" class="p-2 text-red-600 dark:text-rose-400 bg-red-50 dark:bg-rose-500/10 hover:bg-red-100 dark:hover:bg-rose-500/20 rounded-lg transition-colors border border-red-100 dark:border-rose-500/30 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
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