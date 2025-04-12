<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandAlatukur extends Model
{
    use HasFactory;
    protected $table = 'brandalatukur';
    protected $primaryKey = 'kode_brand';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_brand',
        'nama_brand'
    ];
    public $timestamps = false;
    public function brandalatukur()
    {
        return $this->hasMany(ListAlatukur::class, 'kode_brand', 'kode_brand');
    }
}
