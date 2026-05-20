<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class MonitoringController extends Controller
{
    public function index()
    {
        $latest_sensor = \App\Models\SensorLog::latest()->first();
        
        // 20 data for charts (reversed for chronological order)
        $chartLogs = \App\Models\SensorLog::latest()->take(20)->get()->reverse();
        
        $chart_labels = $chartLogs->pluck('created_at')->map(fn($date) => $date->format('H:i'))->toArray();
        $temp_data = $chartLogs->pluck('temperature')->toArray();
        $humid_data = $chartLogs->pluck('humidity')->toArray();
        
        // Paginated data for table
        $table_logs = \App\Models\SensorLog::latest()->paginate(10);
        
        return view('monitoring', compact('latest_sensor', 'chart_labels', 'temp_data', 'humid_data', 'table_logs'));
    }

    public function exportPDF()
    {
        $logs = \App\Models\SensorLog::latest()->take(20)->get();
        
        if (ob_get_length()) {
            ob_end_clean();
        }
        
        $pdf = Pdf::loadView('admin.monitoring.report_pdf', compact('logs'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->stream('Laporan-SiTetas.pdf');
    }
}
