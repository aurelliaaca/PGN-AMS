<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukVms extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_vms_id',
        'nama',
        'berat',
        'jumlah',
        'keterangan',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranVms::class, 'pendaftaran_vms_id');
    }
}