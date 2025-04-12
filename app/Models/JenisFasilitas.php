<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisFasilitas extends Model
{
    use HasFactory;
    protected $table = 'jenisfasilitas';
    protected $primaryKey = 'kode_fasilitas';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_fasilitas',
        'nama_fasilitas'
    ];
    public $timestamps = false;
    public function jenisfasilitas()
    {
        return $this->hasMany(ListFasilitas::class, 'kode_fasilitas', 'kode_fasilitas');
    }
}
