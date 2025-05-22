<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluarVms extends Model
{
    protected $table = 'barang_keluars';

    protected $fillable = [
        'dcaf_id',
        'nama',
        'jumlah',
        'berat',
        'keterangan',
    ];

    public function dcaf()
    {
        return $this->belongsTo(VerifikasiDcaf::class);
    }
}
