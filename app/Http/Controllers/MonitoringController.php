<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class MonitoringController extends Controller
{
    public function index()
    {
        $latest_sensor = \App\Models\SensorLog::latest()->first();
        $anomaly_logs = \App\Models\AnomalyLog::latest()->paginate(10);
        
        // 20 data for charts (reversed for chronological order)
        $chartLogs = \App\Models\SensorLog::latest()->take(20)->get()->reverse();
        
        $chart_labels = $chartLogs->pluck('created_at')->map(fn($date) => $date->format('H:i'))->toArray();
        $temp_data = $chartLogs->pluck('temperature')->toArray();
        $humid_data = $chartLogs->pluck('humidity')->toArray();
        
        // Paginated data for table
        $table_logs = \App\Models\SensorLog::latest()->paginate(10);
        
        return view('monitoring', compact('latest_sensor', 'chart_labels', 'temp_data', 'humid_data', 'table_logs', 'anomaly_logs'));
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

    public function exportExcel()
    {
        $logs = \App\Models\SensorLog::latest()->get();
        
        $filename = 'Laporan-Sensor-SiTetas-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];
        
        $columns = ['ID', 'Suhu (°C)', 'Kelembapan (%)', 'Status Kipas', 'Waktu Tercatat'];
        
        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->temperature,
                    $log->humidity,
                    $log->fan_status ? 'Aktif' : 'Mati',
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
