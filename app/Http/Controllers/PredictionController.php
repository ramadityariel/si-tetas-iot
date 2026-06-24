<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandlingHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\EggCandlingDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class PredictionController extends Controller
{
    public function index(Request $request)
    {
        $query = CandlingHistory::query();

        if ($request->filled('start_date') || $request->filled('end_date')) {
            if ($request->filled('start_date')) {
                $query->where('created_at', '>=', Carbon::parse($request->start_date)->startOfDay());
            }
            if ($request->filled('end_date')) {
                $query->where('created_at', '<=', Carbon::parse($request->end_date)->endOfDay());
            }
        } elseif ($request->filled('quick_filter')) {
            switch ($request->quick_filter) {
                case '1_hari':
                    $query->where('created_at', '>=', Carbon::now()->subDay());
                    break;
                case '1_minggu':
                    $query->where('created_at', '>=', Carbon::now()->subWeek());
                    break;
                case '1_bulan':
                    $query->where('created_at', '>=', Carbon::now()->subMonth());
                    break;
                case '3_bulan':
                    $query->where('created_at', '>=', Carbon::now()->subMonths(3));
                    break;
            }
        }

        $histories = $query->orderBy('created_at', 'desc')->paginate(10);
        $histories->appends($request->all());

        return view('prediksi', compact('histories'));
    }

    public function snapshot(Request $request)
    {
        $imageData = $request->input('image');
        $trayType = $request->input('tray_type', '42_butir'); // Ambil pilihan dari blade
        
        $imageName = 'ml_egg_prediction.png'; 
        $snapshotPath = null;
        $binaryData = null;

        if ($imageData && preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $data = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]);
            $binaryData = base64_decode($data);
            if ($binaryData !== false) {
                $imageName = 'snapshot_' . time() . '_' . Str::random(5) . '.' . $type;
                Storage::disk('public')->put('snapshots/' . $imageName, $binaryData);
                $snapshotPath = 'snapshots/' . $imageName;
            }
        }

        if (!$binaryData) {
            return response()->json(['success' => false, 'message' => 'Format gambar kamera tidak valid.'], 400);
        }

        try {
            // LOGIKA PILIHAN ENDPOINT TANPA UTAC-ATIK PYTHON
            if ($trayType === '1_butir') {
                // Tembak endpoint satuan bawaan Python
                $response = Http::attach('file', $binaryData, $imageName)
                                ->timeout(60)
                                ->post('http://127.0.0.1:8000/predict/single');
            } else {
                // Tembak endpoint rak bawaan Python
                $response = Http::attach('file', $binaryData, $imageName)
                                ->timeout(120)
                                ->post('http://127.0.0.1:8000/predict/tray');
            }

            if ($response->failed()) {
                return response()->json(['success' => false, 'message' => 'Gagal terhubung ke service ML.'], 500);
            }

            $mlResult = $response->json();

            // SINKRONISASI DATA HASIL NYATA
            $eggsData = [];
            $scoreAgg = 0;
            $resultText = 'Infertil';

            if ($trayType === '1_butir') {
                // Jika 1 butir, format JSON Python berupa predicted_class & confidence
                $confidence = $mlResult['confidence'] ?? 0;
                $scoreAgg = round($confidence * 100, 2);
                
                $rawClass = strtolower($mlResult['predicted_class'] ?? 'infertil');
                
                if ($scoreAgg < 70) {
                    $resultText = 'Eror karena tidak terdeteksi telurnya';
                    $rawClass = 'uncertain';
                } else {
                    $resultText = ($rawClass == 'fertil_hidup' || $rawClass == 'fertil_mati') ? 'Fertil' : 'Eror karena tidak terdeteksi telurnya';
                }
                
                $eggsData[] = [
                    'class' => $rawClass,
                    'confidence' => $confidence,
                    'position' => 'Satuan'
                ];
            } else {
                // Jika rak (massal)
                $eggsData = $mlResult['results'] ?? [];
                $totalConfidence = 0;
                $eggCount = count($eggsData);
                
                if ($eggCount > 0) {
                    foreach ($eggsData as $egg) {
                        $totalConfidence += ($egg['confidence'] ?? 0);
                    }
                    $scoreAgg = round(($totalConfidence / $eggCount) * 100, 2);
                }

                $summary = $mlResult['summary'] ?? [];
                $fertilCount = ($summary['fertil_hidup'] ?? 0) + ($summary['fertil_mati'] ?? 0);
                $infertilCount = $summary['infertil'] ?? 0;
                
                if ($fertilCount == 0 && $infertilCount == 0) {
                    $resultText = 'Eror karena tidak terdeteksi telurnya';
                } else {
                    $resultText = ($fertilCount >= $infertilCount) ? 'Fertil' : 'Eror karena tidak terdeteksi telurnya';
                }
            }

            // Simpan Ringkasan ke Database
            $history = CandlingHistory::create([
                'snapshot_path' => $snapshotPath,
                'prediction_result' => $resultText,
                'confidence_score' => $scoreAgg,
                'admin_name' => auth()->user() ? auth()->user()->name : 'Admin Si-Tetas',
                'status' => 'Selesai',
            ]);

            // Simpan Detail Butir ke Database dengan proteksi ENUM kamu
            foreach ($eggsData as $index => $egg) {
                $rawStatus = strtolower($egg['class'] ?? 'kosong');
                if ($rawStatus == 'fertil_hidup' || $rawStatus == 'fertil_mati' || $rawStatus == 'fertil') {
                    $dbStatus = 'fertil';
                } elseif ($rawStatus == 'infertil') {
                    $dbStatus = 'infertil';
                } else {
                    $dbStatus = 'kosong'; // Pengaman status uncertain
                }

                EggCandlingDetail::create([
                    'candling_id' => $history->id,
                    'egg_id' => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                    'prediction_result' => $dbStatus, 
                    'confidence_score' => isset($egg['confidence']) ? round($egg['confidence'] * 100, 2) : null,
                    'notes' => 'Mode: ' . ($egg['position'] ?? 'N/A'),
                ]);
            }

            $annotatedBase64 = null;
            if (isset($mlResult['annotated_image_base64'])) {
                $annotatedBase64 = 'data:image/png;base64,' . $mlResult['annotated_image_base64'];
            }

            return response()->json([
                'success' => true,
                'prediction' => $resultText,
                'score' => $scoreAgg . '%',
                'history' => $history,
                'annotated_image' => $annotatedBase64 
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        // Pastikan hanya super_admin yang bisa mengeksekusi ini di backend
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus data ini.');
        }

        $history = CandlingHistory::findOrFail($id); // Sesuaikan nama Model riwayatmu

        // Hapus file fisik gambar di folder storage agar tidak memenuhkan disk penyimpanan
        if ($history->snapshot_path && Storage::disk('public')->exists($history->snapshot_path)) {
            Storage::disk('public')->delete($history->snapshot_path);
        }

        // Hapus data dari database
        $history->delete();

        return redirect()->back()->with('success', 'Riwayat candling dan foto berhasil dihapus permanen.');
    }

    public function exportPDF(Request $request)
    {
        $query = CandlingHistory::query();

        if ($request->filled('start_date') || $request->filled('end_date')) {
            if ($request->filled('start_date')) {
                $query->where('created_at', '>=', Carbon::parse($request->start_date)->startOfDay());
            }
            if ($request->filled('end_date')) {
                $query->where('created_at', '<=', Carbon::parse($request->end_date)->endOfDay());
            }
        } elseif ($request->filled('quick_filter')) {
            switch ($request->quick_filter) {
                case '1_hari':
                    $query->where('created_at', '>=', Carbon::now()->subDay());
                    break;
                case '1_minggu':
                    $query->where('created_at', '>=', Carbon::now()->subWeek());
                    break;
                case '1_bulan':
                    $query->where('created_at', '>=', Carbon::now()->subMonth());
                    break;
                case '3_bulan':
                    $query->where('created_at', '>=', Carbon::now()->subMonths(3));
                    break;
            }
        }

        $histories = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('pdf.candling-history', compact('histories'));
        
        return $pdf->download('laporan-riwayat-candling.pdf');
    }

    public function exportData($id)
    {
        $history = CandlingHistory::with('eggCandlingDetails')->findOrFail($id);
        
        $filename = 'laporan_telur_snapshot_' . $history->id . '_' . $history->created_at->format('Ymd_His') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        
        $columns = ['ID Telur', 'Username Akses', 'Hasil Prediksi', 'Akurasi (%)', 'Keterangan'];
        
        $callback = function() use($history, $columns) {
            $file = fopen('php://output', 'w');
            
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns, ';');
            
            foreach ($history->eggCandlingDetails as $detail) {
                $row = [
                    $detail->egg_id,
                    $history->admin_name,
                    ucfirst($detail->prediction_result),
                    $detail->confidence_score !== null ? $detail->confidence_score . '%' : 'N/A',
                    $detail->notes ?? '-'
                ];
                fputcsv($file, $row, ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}