<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandlingHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\EggCandlingDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        
        // Simpan gambar snapshot ke storage
        $imageName = 'ml_egg_prediction.png'; // default fallback
        $snapshotPath = null;
        if ($imageData && preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $data = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]);
            $data = base64_decode($data);
            if ($data !== false) {
                $imageName = 'snapshot_' . time() . '_' . Str::random(5) . '.' . $type;
                Storage::disk('public')->put('snapshots/' . $imageName, $data);
                $snapshotPath = 'snapshots/' . $imageName;
            }
        }

        // Simulasi hasil prediksi agregat
        $isFertilAgg = rand(0, 1) === 1;
        $resultText = $isFertilAgg ? 'Fertil' : 'Infertil';
        $scoreAgg = rand(80, 99);

        // Buat record baru di candling_histories
        $history = CandlingHistory::create([
            'snapshot_path' => $snapshotPath,
            'prediction_result' => $resultText,
            'confidence_score' => $scoreAgg,
            'admin_name' => auth()->user() ? auth()->user()->name : 'Admin Si-Tetas',
            'status' => 'Selesai',
        ]);

        // Looping otomatis untuk 88 butir telur
        for ($i = 1; $i <= 88; $i++) {
            $eggId = str_pad($i, 2, '0', STR_PAD_LEFT);
            
            // Simulasi hasil per telur (contoh: 5% kosong, sisanya random fertil/infertil)
            $rand = rand(1, 100);
            if ($rand <= 5) {
                $status = 'kosong';
                $eggScore = null;
            } else {
                $status = rand(0, 1) === 1 ? 'fertil' : 'infertil';
                $eggScore = rand(7000, 9999) / 100; // 70.00 to 99.99
            }

            EggCandlingDetail::create([
                'candling_id' => $history->id,
                'egg_id' => $eggId,
                'prediction_result' => $status,
                'confidence_score' => $eggScore,
                'notes' => $status == 'kosong' ? 'Tidak ada objek terdeteksi' : null,
            ]);
        }

        return response()->json([
            'success' => true,
            'prediction' => $resultText,
            'score' => $scoreAgg,
            'history' => $history
        ]);
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
        
        return $pdf->stream('laporan-riwayat-candling.pdf');
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
            
            // Tambahkan BOM UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Tulis header dengan delimiter titik koma
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
