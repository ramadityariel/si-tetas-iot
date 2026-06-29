<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'temperature',
        'humidity',
        'fan_status',
        'lamp_status',
        'humidifier_status',
        'rf_prediction',
        'if_prediction',
        'if_anomaly_score',
        'rule_status',
        'water_level',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::created(function ($sensorLog) {
            \App\Models\AnomalyLog::detectAndSave($sensorLog);
        });
    }
}
