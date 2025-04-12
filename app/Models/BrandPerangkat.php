<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandPerangkat extends Model
{
    use HasFactory;
    protected $table = 'brandperangkat';
    protected $primaryKey = 'kode_brand';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_brand',
        'nama_brand'
    ];
    public $timestamps = false;
    public function brandfasilitas()
    {
        return $this->hasMany(ListFasilitas::class, 'kode_brand', 'kode_brand');
    }
}
