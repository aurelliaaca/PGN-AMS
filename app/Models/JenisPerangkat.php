<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisPerangkat extends Model
{
    use HasFactory;
    protected $table = 'jenisperangkat';
    protected $primaryKey = 'kode_perangkat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_perangkat',
        'nama_perangkat'
    ];
    public $timestamps = false;
    public function jenisperangkat()
    {
        return $this->hasMany(ListPerangkat::class, 'kode_perangkat', 'kode_perangkat');
    }
}
