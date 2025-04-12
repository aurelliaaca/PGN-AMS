<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListAlatukur extends Model
{
    use HasFactory;
    protected $table = 'listalatukur';
    public $timestamps = false; // Disable automatic timestamps

    protected $primaryKey = 'id_alatukur'; // Tambahkan ini untuk memastikan primary key benar

    protected $fillable = [
        'id_alatukur',
        'kode_region',
        'kode_alatukur',
        'kode_brand',
        'type',
        'serialnumber',
        'alatukur_ke',
        'tahunperolehan',
        'kondisi',
        'keterangan',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function jenisalatukur()
    {
        return $this->belongsTo(JenisAlatukur::class, 'kode_alatukur', 'kode_alatukur');
    }

    public function brand()
    {
        return $this->belongsTo(BrandAlatukur::class, 'kode_brand', 'kode_brand');
    }
}
