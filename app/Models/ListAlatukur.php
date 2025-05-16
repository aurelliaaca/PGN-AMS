<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ListAlatukur extends Model
{
    use HasFactory;

    protected $table = 'listalatukur';
    public $timestamps = false;

    protected $primaryKey = 'id_alatukur';

    protected $fillable = [
        'id_alatukur',
        'kode_region',
        'kode_alatukur',
        'kode_brand',
        'type',
        'serialnumber',
        'alatukur_ke',
        'tahunperolehan',
        'kondisi',
        'keterangan',
        'milik',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function jenisalatukur()
    {
        return $this->belongsTo(JenisAlatukur::class, 'kode_alatukur', 'kode_alatukur');
    }

    public function brandalatukur()
    {
        return $this->belongsTo(BrandAlatukur::class, 'kode_brand', 'kode_brand');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'milik', 'id');
    }

    protected static function booted()
    {
        static::updating(function ($alatukur) {
            $changes = $alatukur->getDirty();
            if (count($changes) === 0) return;

            $histori = 'Diedit: ';
            $logPerubahan = [];

            foreach ($changes as $field => $newValue) {
                $oldValue = $alatukur->getOriginal($field);
                $logPerubahan[] = "$field dari '$oldValue' menjadi '$newValue'";
            }

            $count = count($logPerubahan);
            if ($count === 1) {
                $histori .= $logPerubahan[0];
            } elseif ($count === 2) {
                $histori .= $logPerubahan[0] . ' dan ' . $logPerubahan[1];
            } else {
                $histori .= implode(', ', array_slice($logPerubahan, 0, -1));
                $histori .= ', dan ' . end($logPerubahan);
            }


            DB::table('historialatukur')->insert([
                'kode_region'     => $alatukur->kode_region,
                'kode_alatukur'   => $alatukur->kode_alatukur,
                'alatukur_ke'     => $alatukur->alatukur_ke,
                'kode_brand'      => $alatukur->kode_brand,
                'type'            => $alatukur->type,
                'serialnumber'    => $alatukur->serialnumber,
                'kondisi'         => $alatukur->kondisi,
                'keterangan'      => $alatukur->keterangan,
                'histori'         => $histori,
                'tanggal_perubahan' => Carbon::now('Asia/Jakarta'),
            ]);
        });

        static::deleting(function ($alatukur) {
            DB::table('historialatukur')->insert([
                'kode_region'     => $alatukur->kode_region,
                'kode_alatukur'   => $alatukur->kode_alatukur,
                'alatukur_ke'     => $alatukur->alatukur_ke,
                'kode_brand'      => $alatukur->kode_brand,
                'type'            => $alatukur->type,
                'serialnumber'    => $alatukur->serialnumber,
                'kondisi'         => $alatukur->kondisi,
                'keterangan'      => $alatukur->keterangan,
                'histori'         => 'Dihapus',
                'tanggal_perubahan' => Carbon::now('Asia/Jakarta'),
            ]);
        });
    }
}
