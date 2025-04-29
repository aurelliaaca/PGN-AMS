<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nda extends Model
{
    use HasFactory;
    protected $table = 'nda';

    protected $fillable = [
        'name',
        'no_ktp',
        'alamat',
        'perusahaan',
        'region',
        'bagian',
        'tanggal',
        'tanggal_berlaku',
        'signature',
    ];

    public $timestamps = false;

    protected $dates = ['tanggal', 'tanggal_berlaku'];

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }
}
