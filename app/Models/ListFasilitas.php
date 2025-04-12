<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListFasilitas extends Model
{
    use HasFactory;

    protected $table = 'listfasilitas'; 

    public $timestamps = false; // nggak pakai created_at dan updated_at

    protected $primaryKey = 'id_fasilitas'; 

    protected $fillable = [
        'id_fasilitas',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_fasilitas',
        'fasilitas_ke',
        'kode_brand',
        'type',
        'serialnumber',
        'jml_fasilitas',
        'status',
        'uawal',
        'uakhir',
    ];

    public function getRouteKeyName()
    {
        return 'id_fasilitas';
    }

    // Relasi ke tabel region
    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    // Relasi ke tabel site
    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    // Relasi ke tabel jenis fasilitas
    public function jenisfasilitas()
    {
        return $this->belongsTo(JenisFasilitas::class, 'kode_fasilitas', 'kode_fasilitas');
    }

    // Relasi ke tabel brand fasilitas
    public function brandfasilitas()
    {
        return $this->belongsTo(BrandFasilitas::class, 'kode_brand', 'kode_brand');
    }
}
