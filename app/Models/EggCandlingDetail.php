<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggCandlingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'candling_id',
        'egg_id',
        'prediction_result',
        'confidence_score',
        'notes',
    ];

    public function candlingHistory()
    {
        return $this->belongsTo(CandlingHistory::class, 'candling_id');
    }
}
