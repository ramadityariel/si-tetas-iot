<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel sensor_data.
 * Menyimpan data suhu dan kelembaban dari ESP32 via API.
 */
class SensorData extends Model
{
    protected $table = 'sensor_data';

    protected $fillable = [
        'suhu',       // Suhu dalam °C dari DHT22 / sensor simulasi
        'kelembaban', // Kelembaban dalam % dari DHT22 / sensor simulasi
    ];

    protected $casts = [
        'suhu'       => 'float',
        'kelembaban' => 'float',
    ];
}
