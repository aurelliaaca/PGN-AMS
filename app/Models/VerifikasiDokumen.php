<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifikasiDokumen extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_dokumen';
    
    protected $casts = [
        'masa_berlaku' => 'datetime',
        'signed_at' => 'datetime'
    ];
    

    protected $fillable = [
        'user_id',
        'nama_dokumen',
        'file_path',
        'status',
        'catatan',
        'masa berlaku',
        'signature',
        'signed_by',
        'signed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
