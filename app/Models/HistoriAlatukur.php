<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriAlatukur extends Model
{
    protected $table = 'historialatukur';

    protected $fillable = [
        'id_alatukur',
        'kode_region',
        'kode_alatukur',
        'alatukur_ke',
        'kode_brand',
        'type',
        'serialnumber',
        'tahunperolehan',
        'keterangan',
        'kondisi',
        'histori',
        'tanggal_perubahan',
    ];

    public $timestamps = false;

    protected $dates = ['tanggal_perubahan'];
    public function listalatukur()
    {
        return $this->belongsTo(ListAlatukur::class, 'id_alatukur', 'id_alatukur');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region'); // 'kode_region' is the foreign key, 'id' is the primary key in Region model
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    public function jenisalatukur()
    {
        return $this->belongsTo(JenisAlatukur::class, 'kode_alatukur', 'kode_alatukur');
    }

    public function brandalatukur()
    {
        return $this->belongsTo(BrandAlatukur::class, 'kode_brand', 'kode_brand');
    }
}
