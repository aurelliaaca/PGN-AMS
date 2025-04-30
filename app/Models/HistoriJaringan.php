<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriJaringan extends Model
{
    // Tentukan nama tabel yang digunakan oleh model ini
    protected $table = 'historifasilitas';

    // Tentukan kolom-kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'RO',
        'tipe_jaringan',
        'segmen',
        'jartatup_jartaplok',
        'mainlink_backuplink',
        'panjang',
        'panjang_drawing',
        'jumlah_core',
        'jenis_kabel',
        'tipe_kabel',
        'status',
        'ket',
        'ket2',
        'kode_site_insan',
        'update',
        'route',
        'dci_eqx',
        'histori',
        'tanggal_perubahan',
    ];

    // Jika kamu tidak menggunakan timestamps default dari Eloquent (created_at, updated_at)
    public $timestamps = false;

    // Tentukan format tanggal jika diperlukan
    protected $dates = ['tanggal_perubahan'];
    public function listjaringan()
    {
        return $this->belongsTo(ListJaringan::class, 'id_jaringan', 'id_jaringan');
    }

    public function tipe()
    {
        return $this->belongsTo(Tipe::class, 'tipe_jaringan', 'kode_tipe');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'RO', 'kode_region')->select(['kode_region', 'nama_region']);
    }

}
