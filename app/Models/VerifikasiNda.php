<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifikasiNda extends Model
{
    use HasFactory;

    protected $table = 'verifikasinda';
    
    protected $casts = [
        'masa_berlaku' => 'datetime'
    ];
    
    protected $fillable = [
        'id',
        'user_id',
        'file_path',
        'status',
        'signature',
        'signed_by',
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