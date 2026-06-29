<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandlingSession extends Model
{
    use HasFactory;
    
    protected $fillable = ['tray_id', 'status'];

    public function results()
    {
        return $this->hasMany(CandlingResult::class, 'session_id');
    }
}
