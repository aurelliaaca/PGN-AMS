<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    
    use HasFactory;
    protected $table = 'site';
    protected $primaryKey = 'id_site';
    public $timestamps = false;

    protected $fillable = [
        'nama_site',
        'kode_site',
        'id_site',
        'jenis_site',
        'kode_region',
        'jml_rack'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function perangkat()
    {
        return $this->hasMany(ListPerangkat::class, 'kode_site', 'kode_site');
    }

    public function fasilitas()
    {
        return $this->hasMany(ListFasilitas::class, 'kode_site', 'kode_site');
    }
}
