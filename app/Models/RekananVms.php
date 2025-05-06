<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekananVms extends Model
{
    protected $table = 'rekanan_vms';

    protected $fillable = [
        'pendaftaran_vms_id',
        'nama',
        'perusahaan',
        'no_ktp',
        'no_telepon'
    ];

    public $timestamps = false;  // Disable timestamps
    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranVms::class, 'pendaftaran_vms_id');
    }
}