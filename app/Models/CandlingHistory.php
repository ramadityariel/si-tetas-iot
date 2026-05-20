<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandlingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'snapshot_path',
        'prediction_result',
        'confidence_score',
        'admin_name',
        'status',
    ];
}
