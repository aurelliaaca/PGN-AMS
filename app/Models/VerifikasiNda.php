<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifikasiNda extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_nda';
    
    protected $casts = [
        'masa_berlaku' => 'datetime'
    ];
    
    protected $fillable = [
        'user_id',
        'nda_id',
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
        return $this->belongsTo(Nda::class);
    }
} 