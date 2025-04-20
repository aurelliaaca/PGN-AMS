<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifikasiDcaf extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_dcaf';
    
    protected $casts = [
        'masa_berlaku' => 'datetime'
    ];
    
    protected $fillable = [
        'user_id',
        'verifikasi_nda_id',
        'file_path',
        'status',
        'catatan',
        'masa_berlaku'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nda()
    {
        return $this->belongsTo(VerifikasiNda::class, 'verifikasi_nda_id');
    }
} 