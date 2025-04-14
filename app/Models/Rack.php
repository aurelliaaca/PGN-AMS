<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    // Tentukan nama tabel jika tidak mengikuti konvensi
    protected $table = 'rack';

    // Tentukan primary key jika tidak mengikuti konvensi (id)
    protected $primaryKey = ['kode_region', 'kode_site', 'no_rack', 'u'];

    // Tentukan kolom yang dapat diisi (mass assignment)
    protected $fillable = [
        'kode_region',
        'kode_site',
        'no_rack',
        'u',
        'id_fasilitas',
        'id_perangkat'
    ];

    // Tentukan apakah primary key berupa kombinasi (jika menggunakan composite key)
    public $incrementing = false; // Karena primary key bukan auto-incrementing

    // Tentukan tipe data primary key
    protected $keyType = 'string'; // Kalau primary key kombinasi antara string dan integer

    // Definisikan relasi jika diperlukan, misalnya ke tabel Region, Site, dll.
    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    public function listperangkat()
    {
        return $this->belongsTo(ListPerangkat::class, 'id_perangkat', 'id_perangkat');
    }

    public function listfasilitas()
    {
        return $this->belongsTo(ListFasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }
}
