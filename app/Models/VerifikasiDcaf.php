<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiDcaf extends Model
{
    protected $table = 'verifikasidcaf';

    protected $fillable = [
        'user_id',
        'nda_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'no_rack',
        'jenis_pekerjaan',
        'deskripsi_pekerjaan',
        'signature',
        'pengawas',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rekanans()
    {
    return $this->hasMany(RekananVms::class, 'dcaf_id', 'id');
    }

    public function perlengkapans()
    {
        return $this->hasMany(PerlengkapanVms::class, 'dcaf_id', 'id');
    }

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasukVms::class, 'dcaf_id', 'id');
    }

    public function barangKeluars()
    {
        return $this->hasMany(BarangKeluarVms::class, 'dcaf_id', 'id');
    }

     public function nda()
    {
        return $this->belongsTo(VerifikasiNda::class, 'nda_id');
    }
    public function dcaf()
    {
        return $this->belongsTo(VerifikasiDcaf::class);
    }
        public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function pengawasDCAF()
    {
        return $this->belongsTo(User::class,'pengawas');
    }
} 
