<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriJaringan extends Model
{
    protected $table = 'historijaringan';

    protected $fillable = [
        'id_jaringan',
        'kode_region',
        'kode_tipejaringan',
        'segmen',
        'jartatup_jartaplok',
        'mainlink_backuplink',
        'panjang',
        'panjang_drawing',
        'jumlah_core',
        'jenis_kabel',
        'tipe_kabel',
        'status',
        'keterangan',
        'kode_site_insan',
        'dci_eqx',
        'travelling_time',
        'verification_time',
        'restoration_time',
        'total_corrective_time',
        'milik',
        'histori',
        'tanggal_perubahan',
    ];
    public $timestamps = false;

    protected $dates = ['tanggal_perubahan'];
    public function listjaringan()
    {
        return $this->belongsTo(ListJaringan::class, 'id_jaringan', 'id_jaringan');
    }

    public function tipejaringan()
    {
        return $this->belongsTo(TipeJaringan::class, 'kode_tipejaringan', 'kode_tipejaringan')
            ->select(['kode_tipejaringan', 'nama_tipejaringan']);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region')->select(['kode_region', 'nama_region']);
    }

}
