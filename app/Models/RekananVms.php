<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekananVms extends Model
{
    protected $table = 'rekanans';

    protected $fillable = [
        'dcaf_id',
        'nama',
        'perusahaan',
        'ktp',
        'telp',
    ];

    public function dcaf()
    {
        return $this->belongsTo(VerifikasiDcaf::class);
    }
}
