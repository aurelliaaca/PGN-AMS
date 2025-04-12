<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisAlatukur extends Model
{
    use HasFactory;
    protected $table = 'jenisalatukur';
    protected $primaryKey = 'kode_alatukur';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_alatukur',
        'nama_alatukur'
    ];
    public $timestamps = false;
    public function jenisalatukur()
    {
        return $this->hasMany(ListFasilitas::class, 'kode_alatukur', 'kode_alatukur');
    }
}
