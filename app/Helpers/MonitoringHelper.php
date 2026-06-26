<?php

namespace App\Helpers;

use App\Models\ThresholdSetting;

class MonitoringHelper
{
    /**
     * Get status label berdasarkan temperature dan humidity
     * 
     * Status: 'Baik' | 'Perhatian' | 'Critical'
     */
    public static function getStatusLabel($temperature, $humidity)
    {
        $threshold = ThresholdSetting::getActive();

        // TIER 1: Cek CRITICAL dulu (prioritas tertinggi)
        $isCritical = $temperature < $threshold->temp_min_ekstrem 
                   || $temperature > $threshold->temp_max_ekstrem
                   || $humidity < $threshold->hum_min_ekstrem 
                   || $humidity > $threshold->hum_max_ekstrem;
        
        if ($isCritical) {
            return 'Critical';
        }

        // TIER 2: Cek BAIK (hanya sampai sini jika TIDAK Critical)
        $isBaik = $temperature >= $threshold->temp_min_ideal 
               && $temperature <= $threshold->temp_max_ideal
               && $humidity >= $threshold->hum_min_ideal 
               && $humidity <= $threshold->hum_max_ideal;

        if ($isBaik) {
            return 'Baik';
        }

        // TIER 3: Sisanya otomatis Perhatian
        return 'Perhatian';
    }

    /**
     * Get status color for UI display
     */
    public static function getStatusColor($status)
    {
        return match($status) {
            'Baik' => '#10b981',      // Green
            'Perhatian' => '#f59e0b', // Amber/Yellow
            'Critical' => '#ef4444',  // Red
            default => '#6b7280',     // Gray
        };
    }

    /**
     * Get status badge class (Tailwind)
     */
    public static function getStatusBadgeClass($status)
    {
        return match($status) {
            'Baik' => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30',
            'Perhatian' => 'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30',
            'Critical' => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-500/30',
            default => 'bg-slate-100 dark:bg-slate-500/20 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-500/30',
        };
    }

    /**
     * Get status icon
     */
    public static function getStatusIcon($status)
    {
        return match($status) {
            'Baik' => 'check_circle',
            'Perhatian' => 'warning',
            'Critical' => 'error',
            default => 'help',
        };
    }
}
