<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranVms extends Model
{
    protected $table = 'dcaf';
    protected $fillable = [
        'nama_pemohon',
        'no_hp_pemohon',
        'pengawas',
        'no_hp_pengawas',
        'divisi',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'no_rack',
        'jenis_pekerjaan',
        'keterangan_others',
        'deskripsi',
        'status'
    ];

    public function rekanans()
    {
        return $this->hasMany(RekananVms::class);
    }
}