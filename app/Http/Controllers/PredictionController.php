<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandlingHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
        
        // Simulasi hasil prediksi
        $isFertil = rand(0, 1) === 1;
        $resultText = $isFertil ? 'Fertil' : 'Infertil';
        $score = rand(80, 99);

        // Buat record baru di candling_histories
        $history = CandlingHistory::create([
            'snapshot_path' => null, // Simulasi tanpa simpan file fisik untuk saat ini
            'prediction_result' => $resultText,
            'confidence_score' => $score,
            'admin_name' => auth()->user() ? auth()->user()->name : 'Admin Si-Tetas',
            'status' => 'Selesai',
        ]);

        return response()->json([
            'success' => true,
            'prediction' => $resultText,
            'score' => $score,
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
}
