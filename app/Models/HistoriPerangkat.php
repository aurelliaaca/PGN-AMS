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
        'milik',
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

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region'); // 'kode_region' is the foreign key, 'id' is the primary key in Region model
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    public function jenisperangkat()
    {
        return $this->belongsTo(JenisPerangkat::class, 'kode_perangkat', 'kode_perangkat');
    }

    public function brandperangkat()
    {
        return $this->belongsTo(BrandPerangkat::class, 'kode_brand', 'kode_brand');
    }

}
