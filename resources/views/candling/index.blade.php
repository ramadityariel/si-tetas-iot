@extends('layouts.admin')
@section('title', __('admin.candling.title') . ' | ' . __('admin.sidebar.title'))
@section('content')

<div class="p-4 sm:p-8 max-w-7xl mx-auto min-h-screen pt-20 sm:pt-24 lg:pt-8 transition-colors duration-500">
    <div class="mb-8 relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-[#194A63] dark:text-white tracking-tight drop-shadow-sm font-headline">
                {{ __('admin.candling.title') }}
            </h2>
            <p class="text-slate-500 dark:text-sky-200/70 mt-2 font-medium font-['Plus_Jakarta_Sans'] text-sm max-w-xl">
                {{ __('admin.candling.subtitle') }}
            </p>
        </div>
    </div>

    <!-- Tray Tabs -->
    <div class="mb-6 bg-white dark:bg-slate-900/50 p-2 rounded-2xl shadow-sm border border-slate-100 dark:border-white/10 flex overflow-x-auto" id="tray-tabs">
        <button type="button" onclick="fetchTrayData(101)" id="btn-tray-101" class="tray-btn flex-1 text-center py-3 px-4 rounded-xl font-bold text-sm transition-all whitespace-nowrap {{ $session_id == 101 ? 'bg-[#35627C] text-white shadow-md' : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-white/5' }}">
            {{ __('admin.candling.tray_top') }}
        </button>
        <button type="button" onclick="fetchTrayData(102)" id="btn-tray-102" class="tray-btn flex-1 text-center py-3 px-4 rounded-xl font-bold text-sm transition-all whitespace-nowrap {{ $session_id == 102 ? 'bg-[#35627C] text-white shadow-md' : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-white/5' }}">
            {{ __('admin.candling.tray_mid') }}
        </button>
        <button type="button" onclick="fetchTrayData(103)" id="btn-tray-103" class="tray-btn flex-1 text-center py-3 px-4 rounded-xl font-bold text-sm transition-all whitespace-nowrap {{ $session_id == 103 ? 'bg-[#35627C] text-white shadow-md' : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-white/5' }}">
            {{ __('admin.candling.tray_bot') }}
        </button>
    </div>

    <div id="session-content" class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative transition-all">
        <!-- Loading Overlay -->
        <div id="loading-overlay" class="absolute inset-0 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm z-20 hidden flex-col items-center justify-center rounded-2xl">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#194A63] dark:border-sky-400"></div>
            <p class="mt-4 font-bold text-[#194A63] dark:text-sky-400">Memuat Data...</p>
        </div>

        <!-- Peringatan Sesi Kosong -->
        <div id="no-session-warning" class="{{ $session ? 'hidden' : '' }} lg:col-span-3 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 p-4 rounded-xl flex items-center gap-3 mb-4 shadow-sm">
            <span class="material-symbols-outlined">warning</span>
            <div>
                <p class="font-bold text-sm">Data belum direkam!</p>
                <p class="text-xs opacity-90">Sistem hardware AI belum mengirimkan data untuk Sesi ini. Menampilkan grid kosong (0).</p>
            </div>
        </div>

        <!-- 6x6 Egg Grid -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900/40 p-6 rounded-2xl border border-slate-100 dark:border-white/10 shadow-xl relative overflow-hidden">
            <h3 class="font-bold text-[#194A63] dark:text-white mb-6 text-lg border-b border-slate-100 dark:border-white/10 pb-4">Grid 6x6 Tray <span id="tray-name-title">{{ $tray_id ?? 'Atas' }}</span></h3>
            
            <div class="overflow-x-auto pb-4">
                <div class="grid grid-cols-6 gap-2 sm:gap-3 min-w-[500px]" id="egg-grid-container">
                    @foreach($finalResults as $egg)
                        @php
                            $status = $egg->is_manual_override ? $egg->status_manual : $egg->status_deteksi_ai;
                            $bgColor = 'bg-slate-100 dark:bg-slate-800';
                            $textColor = 'text-slate-400 dark:text-slate-500'; // Untuk kosong
                            
                            if ($status == 'Fertil Hidup') {
                                $bgColor = 'bg-emerald-100 dark:bg-emerald-500/20';
                                $textColor = 'text-emerald-700 dark:text-emerald-400';
                            } elseif ($status == 'Fertil Mati') {
                                $bgColor = 'bg-rose-100 dark:bg-rose-500/20';
                                $textColor = 'text-rose-700 dark:text-rose-400';
                            } elseif ($status == 'Infertil') {
                                $bgColor = 'bg-slate-200 dark:bg-slate-700';
                                $textColor = 'text-slate-600 dark:text-slate-300';
                            }
                            if ($egg->is_manual_override) {
                                $bgColor .= ' opacity-80 ring-2 ring-amber-400 dark:ring-amber-500';
                            }
                            $isClickable = $egg->id ? 'cursor-pointer hover:scale-105' : 'opacity-60 cursor-not-allowed';
                        @endphp

                        <div @if($egg->id) onclick="openEggModal({{ $egg->id }}, {{ $egg->egg_position }}, '{{ $status }}', '{{ $egg->confidence_score }}', '{{ $egg->image_url }}', '{{ $tray_id ?? 'Atas' }}')" @endif 
                             class="aspect-square rounded-xl {{ $bgColor }} border border-slate-200 dark:border-white/10 flex flex-col items-center justify-center {{ $isClickable }} transition-transform relative group shadow-sm">
                            <span class="font-black {{ $textColor }} text-lg sm:text-2xl">{{ $egg->egg_position }}</span>
                            @if($egg->is_manual_override)
                            <div class="absolute top-1 right-1 bg-amber-400 text-white rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center shadow-md" title="{{ __('admin.candling.override_indicator') }}">
                                <span class="material-symbols-outlined text-[10px] sm:text-[12px]">edit</span>
                            </div>
                            @endif
                            @if($egg->id)
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-3xl">zoom_in</span>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Statistics Panel -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900/40 p-6 rounded-2xl border border-slate-100 dark:border-white/10 shadow-lg">
                <h3 class="font-bold text-[#194A63] dark:text-white mb-4 text-sm uppercase tracking-wider">{{ __('admin.candling.stats_ai') }}</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20">
                        <span class="font-medium text-emerald-700 dark:text-emerald-400">{{ __('admin.candling.fertil_hidup') }}</span>
                        <span class="font-black text-xl text-emerald-700 dark:text-emerald-400" id="ai-hidup">{{ $stats['ai']['Fertil Hidup'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-lg bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20">
                        <span class="font-medium text-rose-700 dark:text-rose-400">{{ __('admin.candling.fertil_mati') }}</span>
                        <span class="font-black text-xl text-rose-700 dark:text-rose-400" id="ai-mati">{{ $stats['ai']['Fertil Mati'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                        <span class="font-medium text-slate-600 dark:text-slate-400">{{ __('admin.candling.infertil') }}</span>
                        <span class="font-black text-xl text-slate-600 dark:text-slate-400" id="ai-infertil">{{ $stats['ai']['Infertil'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-lg bg-slate-100 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800">
                        <span class="font-medium text-slate-400 dark:text-slate-500">Kosong</span>
                        <span class="font-black text-xl text-slate-400 dark:text-slate-500" id="ai-kosong">{{ $stats['ai']['Kosong'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#194A63] to-[#35627C] dark:from-slate-800 dark:to-slate-900 p-6 rounded-2xl shadow-xl text-white">
                <h3 class="font-bold text-sky-200 mb-4 text-sm uppercase tracking-wider">{{ __('admin.candling.stats_manual') }}</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 rounded-lg bg-white/10 backdrop-blur-sm border border-white/10">
                        <span class="font-medium text-emerald-300">{{ __('admin.candling.fertil_hidup') }}</span>
                        <span class="font-black text-xl text-emerald-300" id="manual-hidup">{{ $stats['manual']['Fertil Hidup'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-lg bg-white/10 backdrop-blur-sm border border-white/10">
                        <span class="font-medium text-rose-300">{{ __('admin.candling.fertil_mati') }}</span>
                        <span class="font-black text-xl text-rose-300" id="manual-mati">{{ $stats['manual']['Fertil Mati'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-lg bg-white/10 backdrop-blur-sm border border-white/10">
                        <span class="font-medium text-slate-300">{{ __('admin.candling.infertil') }}</span>
                        <span class="font-black text-xl text-slate-300" id="manual-infertil">{{ $stats['manual']['Infertil'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-lg bg-white/5 backdrop-blur-sm border border-white/5 opacity-80">
                        <span class="font-medium text-slate-400">Kosong</span>
                        <span class="font-black text-xl text-slate-400" id="manual-kosong">{{ $stats['manual']['Kosong'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Manual Override -->
<div id="eggModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="eggModalContent">
        <div class="relative h-64 bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
            <img id="modalImage" src="" alt="Egg Candling" class="w-full h-full object-cover">
            <button onclick="closeEggModal()" class="absolute top-4 right-4 bg-black/50 hover:bg-black/80 text-white rounded-full p-2 backdrop-blur-md transition-colors">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
            <div class="absolute bottom-4 left-4 bg-black/60 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20">
                <span class="text-white font-bold text-sm uppercase tracking-wider mr-2">{{ __('admin.candling.tray') }} <span id="modalTrayId" class="text-sky-400 ml-1"></span></span>
                <span class="text-white font-bold text-sm uppercase tracking-wider">{{ __('admin.candling.egg_position') }} <span id="modalPos" class="text-2xl text-emerald-400 ml-1"></span></span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <div class="flex justify-between items-end mb-2">
                    <span class="font-bold text-sm text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('admin.candling.confidence') }}</span>
                    <span id="modalConfidenceText" class="font-black text-[#194A63] dark:text-white text-xl"></span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden shadow-inner">
                    <div id="modalConfidenceBar" class="bg-gradient-to-r from-sky-400 to-[#35627C] h-3 rounded-full shadow-md" style="width: 0%"></div>
                </div>
            </div>

            <form id="overrideForm" class="space-y-4">
                <input type="hidden" id="modalResultId">
                <div>
                    <label class="block font-bold text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('admin.candling.manual_override') }}</label>
                    <select id="modalStatusSelect" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-[#194A63] dark:text-white rounded-xl px-4 py-3 font-semibold focus:ring-2 focus:ring-sky-500 outline-none transition-all">
                        <option value="Fertil Hidup">{{ __('admin.candling.fertil_hidup') }}</option>
                        <option value="Fertil Mati">{{ __('admin.candling.fertil_mati') }}</option>
                        <option value="Infertil">{{ __('admin.candling.infertil') }}</option>
                    </select>
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeEggModal()" class="flex-1 py-3 px-4 rounded-xl font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        {{ __('admin.candling.close') }}
                    </button>
                    <button type="button" onclick="submitOverride()" class="flex-1 py-3 px-4 rounded-xl font-bold text-white bg-gradient-to-r from-sky-500 to-[#35627C] shadow-lg shadow-sky-500/30 hover:shadow-sky-500/50 transition-all hover:-translate-y-0.5">
                        {{ __('admin.candling.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // AJAX Fetch Function untuk load data tanpa reload
    function fetchTrayData(sessionId) {
        // Tampilkan loading & Update Button States
        document.getElementById('loading-overlay').classList.remove('hidden');
        document.getElementById('loading-overlay').classList.add('flex');
        
        document.querySelectorAll('.tray-btn').forEach(btn => {
            btn.classList.remove('bg-[#35627C]', 'text-white', 'shadow-md');
            btn.classList.add('text-slate-500', 'hover:bg-slate-100', 'dark:text-slate-400', 'dark:hover:bg-white/5');
        });

        let activeBtn = document.getElementById('btn-tray-' + sessionId);
        if(activeBtn) {
            activeBtn.classList.add('bg-[#35627C]', 'text-white', 'shadow-md');
            activeBtn.classList.remove('text-slate-500', 'hover:bg-slate-100', 'dark:text-slate-400', 'dark:hover:bg-white/5');
        }

        fetch(`{{ route('admin.candling') }}?session_id=${sessionId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading-overlay').classList.add('hidden');
            document.getElementById('loading-overlay').classList.remove('flex');
            
            if (data.results) {
                // Tampilkan atau sembunyikan peringatan jika session_exists = false
                if (data.session_exists) {
                    document.getElementById('no-session-warning').classList.add('hidden');
                } else {
                    document.getElementById('no-session-warning').classList.remove('hidden');
                }
                
                document.getElementById('tray-name-title').innerText = data.tray_id;
                
                // Update AI Stats
                document.getElementById('ai-hidup').innerText = data.stats.ai['Fertil Hidup'];
                document.getElementById('ai-mati').innerText = data.stats.ai['Fertil Mati'];
                document.getElementById('ai-infertil').innerText = data.stats.ai['Infertil'];
                document.getElementById('ai-kosong').innerText = data.stats.ai['Kosong'];

                // Update Manual Stats
                document.getElementById('manual-hidup').innerText = data.stats.manual['Fertil Hidup'];
                document.getElementById('manual-mati').innerText = data.stats.manual['Fertil Mati'];
                document.getElementById('manual-infertil').innerText = data.stats.manual['Infertil'];
                document.getElementById('manual-kosong').innerText = data.stats.manual['Kosong'];

                // Render Grid HTML
                renderGrid(data.results, data.tray_id);
                
                // Update URL parameter without reloading
                const url = new URL(window.location);
                url.searchParams.set('session_id', sessionId);
                window.history.pushState({}, '', url);
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('loading-overlay').classList.add('hidden');
            document.getElementById('loading-overlay').classList.remove('flex');
        });
    }

    // Render 6x6 Grid HTML via JS
    function renderGrid(results, trayId) {
        const container = document.getElementById('egg-grid-container');
        container.innerHTML = ''; // Kosongkan
        
        let html = '';
        results.forEach(egg => {
            let status = egg.is_manual_override ? egg.status_manual : egg.status_deteksi_ai;
            
            let bgColor = 'bg-slate-100 dark:bg-slate-800';
            let textColor = 'text-slate-400 dark:text-slate-500'; // Default kosong
            
            if (status == 'Fertil Hidup') {
                bgColor = 'bg-emerald-100 dark:bg-emerald-500/20';
                textColor = 'text-emerald-700 dark:text-emerald-400';
            } else if (status == 'Fertil Mati') {
                bgColor = 'bg-rose-100 dark:bg-rose-500/20';
                textColor = 'text-rose-700 dark:text-rose-400';
            } else if (status == 'Infertil') {
                bgColor = 'bg-slate-200 dark:bg-slate-700';
                textColor = 'text-slate-600 dark:text-slate-300';
            }

            if (egg.is_manual_override) {
                bgColor += ' opacity-80 ring-2 ring-amber-400 dark:ring-amber-500';
            }

            let isClickable = egg.id ? 'cursor-pointer hover:scale-105' : 'opacity-60 cursor-not-allowed';
            let onclickFn = egg.id ? `onclick="openEggModal(${egg.id}, ${egg.egg_position}, '${status}', '${egg.confidence_score}', '${egg.image_url}', '${trayId}')"` : '';

            let editIcon = egg.is_manual_override ? 
                `<div class="absolute top-1 right-1 bg-amber-400 text-white rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center shadow-md">
                    <span class="material-symbols-outlined text-[10px] sm:text-[12px]">edit</span>
                </div>` : '';

            let hoverIcon = egg.id ? 
                `<div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-3xl">zoom_in</span>
                </div>` : '';

            html += `
                <div ${onclickFn} 
                     class="aspect-square rounded-xl ${bgColor} border border-slate-200 dark:border-white/10 flex flex-col items-center justify-center ${isClickable} transition-transform relative group shadow-sm">
                    <span class="font-black ${textColor} text-lg sm:text-2xl">${egg.egg_position}</span>
                    ${editIcon}
                    ${hoverIcon}
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    function openEggModal(id, pos, status, confidence, image, trayId) {
        document.getElementById('modalResultId').value = id;
        document.getElementById('modalPos').innerText = pos;
        document.getElementById('modalTrayId').innerText = trayId;
        
        let confPercent = confidence && confidence !== 'null' ? (parseFloat(confidence) * 100).toFixed(1) : 0;
        document.getElementById('modalConfidenceText').innerText = confPercent + '%';
        document.getElementById('modalConfidenceBar').style.width = confPercent + '%';
        document.getElementById('modalImage').src = image;
        
        let select = document.getElementById('modalStatusSelect');
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === status) {
                select.selectedIndex = i;
                break;
            }
        }

        const modal = document.getElementById('eggModal');
        const content = document.getElementById('eggModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeEggModal() {
        const modal = document.getElementById('eggModal');
        const content = document.getElementById('eggModalContent');
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function submitOverride() {
        const id = document.getElementById('modalResultId').value;
        const newStatus = document.getElementById('modalStatusSelect').value;

        fetch('{{ route("api.update-egg-status") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                result_id: id,
                new_status: newStatus
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                }).then(() => {
                    // Daripada reload, kita bisa panggil fetchTrayData dengan session_id aktif
                    let urlParams = new URLSearchParams(window.location.search);
                    let activeSession = urlParams.get('session_id') || 101;
                    fetchTrayData(activeSession);
                    closeEggModal();
                });
            } else {
                Swal.fire('Error', 'Terjadi kesalahan.', 'error');
            }
        });
    }
</script>

@endsection
