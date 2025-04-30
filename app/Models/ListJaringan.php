<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HistoriJaringan;
use App\Models\Tipe;

class ListJaringan extends Model
{
    use HasFactory;

    protected $table = 'listjaringan';
    protected $primaryKey = 'id_jaringan';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_jaringan',
        'RO',
        'tipe_jaringan',
        'segmen',
        'jartatup_jartaplok',
        'mainlink_backuplink',
        'panjang',
        'panjang_drawing',
        'jumlah_core',
        'jenis_kabel',
        'tipe_kabel',
        'status',
        'ket',
        'ket2',
        'kode_site_insan',
        'update',
        'route',
        'dci_eqx',
    ];

    public function tipe()
    {
        return $this->belongsTo(Tipe::class, 'tipe_jaringan', 'kode_tipe');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'RO', 'kode_region')->select(['kode_region', 'nama_region']);
    }
}