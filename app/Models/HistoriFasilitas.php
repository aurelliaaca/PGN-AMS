<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriFasilitas extends Model
{
    // Tentukan nama tabel yang digunakan oleh model ini
    protected $table = 'historifasilitas';

    // Tentukan kolom-kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'id_fasilitas',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_fasilitas',
        'fasilitas_ke',
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
    public function listfasilitas()
    {
        return $this->belongsTo(ListFasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region'); // 'kode_region' is the foreign key, 'id' is the primary key in Region model
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    public function jenisfasilitas()
    {
        return $this->belongsTo(JenisFasilitas::class, 'kode_fasilitas', 'kode_fasilitas');
    }

    public function brandfasilitas()
    {
        return $this->belongsTo(BrandFasilitas::class, 'kode_brand', 'kode_brand');
    }

}
