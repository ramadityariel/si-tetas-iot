<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnomalyLog extends Model
{
    protected $fillable = [
        'temperature',
        'humidity',
        'anomaly_type',
        'description',
    ];
}
