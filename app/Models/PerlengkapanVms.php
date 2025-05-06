<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerlengkapanVms extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_vms_id',
        'nama',
        'jumlah',
        'keterangan',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranVms::class, 'pendaftaran_vms_id');
    }
}