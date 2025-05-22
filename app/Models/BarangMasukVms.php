<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasukVms extends Model
{
    protected $table = 'barang_masuks';

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
