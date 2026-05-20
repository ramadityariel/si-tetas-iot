<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $latest_sensor = \App\Models\SensorLog::latest()->first();
        
        $historical_sensors = \App\Models\SensorLog::latest()->take(20)->get()->reverse();
        
        $chart_labels = $historical_sensors->pluck('created_at')->map(fn($date) => $date->format('H:i'))->toArray();
        $chart_data = $historical_sensors->pluck('temperature')->toArray();
        
        $activity_logs = \App\Models\SensorLog::latest()->take(3)->get();
        
        return view('dashboard', compact('latest_sensor', 'chart_labels', 'chart_data', 'activity_logs'));
    }
}
