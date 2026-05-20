<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HatchPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'estimated_date',
        'confidence_score',
        'status',
    ];
}
