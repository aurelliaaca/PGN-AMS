<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifikasiDokumen extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_dokumen';
    
    protected $casts = [
        'nda_masa_berlaku' => 'datetime',
        'dcaf_masa_berlaku' => 'datetime'
    ];
    

    protected $fillable = [
        'user_id',
        'nda_file_path',
        'dcaf_file_path',
        'nda_status',
        'dcaf_status',
        'catatan',
        'nda_masa_berlaku',
        'dcaf_masa_berlaku'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
