<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerlengkapanVms extends Model
{
    protected $table = 'perlengkapans';

    protected $fillable = [
        'dcaf_id',
        'nama',
        'jumlah',
        'keterangan',
    ];

    public function dcaf()
    {
        return $this->belongsTo(VerifikasiDcaf::class);
    }
}
