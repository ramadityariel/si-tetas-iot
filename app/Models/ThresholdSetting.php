<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThresholdSetting extends Model
{
    protected $fillable = [
        'temp_min_ideal',
        'temp_max_ideal',
        'hum_min_ideal',
        'hum_max_ideal',
        'temp_min_ekstrem',
        'temp_max_ekstrem',
        'hum_min_ekstrem',
        'hum_max_ekstrem',
        'updated_by',
    ];

    /**
     * Get the active threshold settings or create default
     */
    public static function getActive()
    {
        return self::latest()->first() ?? self::firstOrCreate(
            [], // if no record exists, create the default
            [
                'temp_min_ideal' => 37.0,
                'temp_max_ideal' => 38.0,
                'hum_min_ideal' => 55.0,
                'hum_max_ideal' => 60.0,
                'temp_min_ekstrem' => 36.0,
                'temp_max_ekstrem' => 39.0,
                'hum_min_ekstrem' => 50.0,
                'hum_max_ekstrem' => 65.0,
                'updated_by' => 'system',
            ]
        );
    }
}
