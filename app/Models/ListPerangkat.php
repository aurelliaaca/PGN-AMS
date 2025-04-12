<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ListPerangkat extends Model
{
    use HasFactory;

    protected $table = 'listperangkat'; // Nama tabel di database

    public $timestamps = false; // Nggak pakai created_at dan updated_at

    protected $primaryKey = 'id_perangkat'; // Primary key custom

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
    ];

    public function getRouteKeyName()
    {
        return 'id_perangkat';
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

    // Relasi ke tabel jenis perangkat
    public function jenisperangkat()
    {
        return $this->belongsTo(JenisPerangkat::class, 'kode_perangkat', 'kode_perangkat');
    }

    // Relasi ke tabel brand perangkat
    public function brandperangkat()
    {
        return $this->belongsTo(BrandPerangkat::class, 'kode_brand', 'kode_brand');
    }
}
