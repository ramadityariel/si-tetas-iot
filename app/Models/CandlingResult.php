<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandlingResult extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'session_id', 'egg_position', 'status_deteksi_ai', 
        'confidence_score', 'image_path', 'is_manual_override', 'status_manual'
    ];

    protected $casts = [
        'is_manual_override' => 'boolean',
        'confidence_score' => 'float'
    ];

    public function session()
    {
        return $this->belongsTo(CandlingSession::class, 'session_id');
    }
}
