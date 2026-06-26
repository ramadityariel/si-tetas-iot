<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use App\Models\AnomalyLog;
use App\Models\ThresholdSetting;
use App\Helpers\MonitoringHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AIMonitoringController extends Controller
{
    /**
     * Display AI Monitoring dashboard
     */
    public function index()
    {
        $threshold = ThresholdSetting::getActive();
        
        return view('ai_monitoring', compact('threshold'));
    }

    /**
     * Get daily status distribution chart data.
     * Uses cached rule_status column — no per-row PHP computation.
     */
    public function getDailyStatusDistribution()
    {
        $today = Carbon::today();

        // Single aggregated query using stored rule_status
        $counts = SensorLog::whereDate('created_at', $today)
            ->whereNotNull('rule_status')
            ->select('rule_status', DB::raw('count(*) as total'))
            ->groupBy('rule_status')
            ->pluck('total', 'rule_status')
            ->toArray();

        $statusCounts = [
            'Baik'      => $counts['Baik']      ?? 0,
            'Perhatian' => $counts['Perhatian']  ?? 0,
            'Critical'  => $counts['Critical']   ?? 0,
        ];

        $total = array_sum($statusCounts) ?: 1;
        $percentages = [
            'Baik'      => round(($statusCounts['Baik']      / $total) * 100, 1),
            'Perhatian' => round(($statusCounts['Perhatian'] / $total) * 100, 1),
            'Critical'  => round(($statusCounts['Critical']  / $total) * 100, 1),
        ];

        return response()->json([
            'labels' => ['Baik', 'Perhatian', 'Critical'],
            'data'   => [
                $percentages['Baik'],
                $percentages['Perhatian'],
                $percentages['Critical'],
            ],
            'backgroundColor' => ['#10b981', '#f59e0b', '#ef4444'],
            'counts' => $statusCounts,
            'total'  => $total,
        ]);
    }

    /**
     * Get realtime temperature & humidity line chart data with zone backgrounds
     */
    public function getRealtimeZoneChart()
    {
        $threshold = ThresholdSetting::getActive();

        $logs = SensorLog::latest()->limit(48)->get()->reverse();

        $labels = [];
        $tempData = [];
        $humidData = [];
        $tempColors = [];
        $humidColors = [];

        foreach ($logs as $log) {
            $labels[]     = $log->created_at->format('H:i');
            $tempData[]   = round($log->temperature, 1);
            $humidData[]  = round($log->humidity, 1);

            // Use stored rule_status if available, otherwise fall back to helper
            $status = $log->rule_status ?? MonitoringHelper::getStatusLabel($log->temperature, $log->humidity);
            $color  = match($status) {
                'Baik'      => '#10b981',
                'Perhatian' => '#f59e0b',
                default     => '#ef4444',
            };
            $tempColors[]  = $color;
            $humidColors[] = $color;
        }

        return response()->json([
            'labels'      => $labels,
            'temperature' => [
                'data'        => $tempData,
                'colors'      => $tempColors,
                'good_min'    => $threshold->temp_min_ideal,
                'good_max'    => $threshold->temp_max_ideal,
                'warning_min' => $threshold->temp_min_ekstrem,
                'warning_max' => $threshold->temp_max_ekstrem,
            ],
            'humidity' => [
                'data'        => $humidData,
                'colors'      => $humidColors,
                'good_min'    => $threshold->hum_min_ideal,
                'good_max'    => $threshold->hum_max_ideal,
                'warning_min' => $threshold->hum_min_ekstrem,
                'warning_max' => $threshold->hum_max_ekstrem,
            ],
        ]);
    }

    /**
     * Get status summary cards data
     */
    public function getStatusSummary()
    {
        $today = Carbon::today();

        $todayLogs      = SensorLog::whereDate('created_at', $today)->count();
        $todayAnomalies = AnomalyLog::whereDate('created_at', $today)->count();

        $latest       = SensorLog::latest()->first();
        $latestStatus = $latest
            ? ($latest->rule_status ?? MonitoringHelper::getStatusLabel($latest->temperature, $latest->humidity))
            : 'N/A';

        $weekAnomalies = AnomalyLog::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        return response()->json([
            'today_logs'       => $todayLogs,
            'today_anomalies'  => $todayAnomalies,
            'latest_status'    => $latestStatus,
            'latest_temp'      => $latest ? round($latest->temperature, 1) : 0,
            'latest_humidity'  => $latest ? round($latest->humidity, 1) : 0,
            'week_anomalies'   => $weekAnomalies,
        ]);
    }

    /**
     * Get hourly status trend for last 24 hours.
     * Uses a SINGLE grouped SQL query instead of 24 separate queries.
     */
    public function getHourlyTrend()
    {
        $from = Carbon::now()->subHours(23)->startOfHour();

        // One query: group by hour and rule_status
        $rows = SensorLog::where('created_at', '>=', $from)
            ->whereNotNull('rule_status')
            ->select(
                DB::raw("strftime('%H', created_at) as hour"),
                'rule_status',
                DB::raw('count(*) as total')
            )
            ->groupBy('hour', 'rule_status')
            ->orderBy('hour')
            ->get();

        // Build a lookup: [ '08' => ['Baik' => 5, 'Perhatian' => 2, ...], ... ]
        $lookup = [];
        foreach ($rows as $row) {
            $lookup[$row->hour][$row->rule_status] = (int) $row->total;
        }

        $hours    = [];
        $statuses = ['Baik' => [], 'Perhatian' => [], 'Critical' => []];

        for ($i = 23; $i >= 0; $i--) {
            $h = Carbon::now()->subHours($i)->startOfHour()->format('H');
            $hours[]               = $h . ':00';
            $statuses['Baik'][]      = $lookup[$h]['Baik']      ?? 0;
            $statuses['Perhatian'][] = $lookup[$h]['Perhatian'] ?? 0;
            $statuses['Critical'][]  = $lookup[$h]['Critical']  ?? 0;
        }

        return response()->json([
            'labels'    => $hours,
            'Baik'      => $statuses['Baik'],
            'Perhatian' => $statuses['Perhatian'],
            'Critical'  => $statuses['Critical'],
        ]);
    }
}
