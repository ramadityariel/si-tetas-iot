<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandlingSession;

class CandlingController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil session_id, default 101 (Top Tray)
        $session_id = $request->get('session_id', 101);
        
        // Menentukan tray berdasarkan session_id (hanya untuk UI active state)
        if ($session_id == 101) $tray_id = 'Atas';
        elseif ($session_id == 102) $tray_id = 'Tengah';
        else $tray_id = 'Bawah';

        // 2. Fetch session berdasarkan ID = 101, 102, atau 103
        $session = CandlingSession::with(['results' => function ($query) {
            $query->orderBy('egg_position', 'asc');
        }])
        ->where('id', $session_id)
        ->first();

        // 3. Calculate statistics & Prepare 36 Grid slots
        $stats = [
            'ai' => ['Fertil Hidup' => 0, 'Fertil Mati' => 0, 'Infertil' => 0, 'Kosong' => 0, 'Total' => 0],
            'manual' => ['Fertil Hidup' => 0, 'Fertil Mati' => 0, 'Infertil' => 0, 'Kosong' => 0, 'Total' => 0]
        ];

        $resultsMap = [];
        if ($session && $session->results) {
            foreach ($session->results as $result) {
                $resultsMap[$result->egg_position] = clone $result; // Gunakan clone untuk menghindari modifikasi referensi
                // Tambahkan URL gambar untuk keperluan JSON
                $resultsMap[$result->egg_position]->image_url = $result->image_path ? asset('storage/' . $result->image_path) : '';
                
                $ai_status = $result->status_deteksi_ai ?? 'Infertil';
                $manual_status = $result->is_manual_override ? $result->status_manual : $ai_status;

                if (isset($stats['ai'][$ai_status])) {
                    $stats['ai'][$ai_status]++;
                    $stats['ai']['Total']++;
                }
                
                if (isset($stats['manual'][$manual_status])) {
                    $stats['manual'][$manual_status]++;
                    $stats['manual']['Total']++;
                }
            }
        }

        // Pastikan selalu ada 36 slot array (Grid 6x6) agar tampilan rapi meski kosong
        $finalResults = [];
        for ($i = 1; $i <= 36; $i++) {
            if (isset($resultsMap[$i])) {
                $finalResults[] = $resultsMap[$i];
            } else {
                // Placeholder telur kosong
                $finalResults[] = (object) [
                    'id' => null,
                    'egg_position' => $i,
                    'status_deteksi_ai' => 'Kosong',
                    'status_manual' => 'Kosong',
                    'is_manual_override' => false,
                    'confidence_score' => 0,
                    'image_url' => ''
                ];
                $stats['ai']['Kosong']++;
                $stats['manual']['Kosong']++;
            }
        }

        // 4. Return JSON jika request datang dari AJAX/Fetch API
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'session_exists' => $session ? true : false,
                'session_id' => $session_id,
                'tray_id' => $tray_id,
                'stats' => $stats,
                'results' => $finalResults
            ]);
        }

        // 5. Default return view (untuk first load browser)
        return view('candling.index', compact('tray_id', 'stats', 'session_id', 'finalResults', 'session'));
    }
}
