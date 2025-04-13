<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriPerangkat extends Model
{
    // Tentukan nama tabel yang digunakan oleh model ini
    protected $table = 'historiperangkat';

    // Tentukan kolom-kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'id_perangkat',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_perangkat',
        'perangkat_ke',
        'kode_brand',
        'type',
        'uawal',
        'uakhir',
        'histori',
        'tanggal_perubahan',
    ];

    // Jika kamu tidak menggunakan timestamps default dari Eloquent (created_at, updated_at)
    public $timestamps = false;

    // Tentukan format tanggal jika diperlukan
    protected $dates = ['tanggal_perubahan'];
    public function listperangkat()
    {
        return $this->belongsTo(ListPerangkat::class, 'id_perangkat', 'id_perangkat');
    }

}
