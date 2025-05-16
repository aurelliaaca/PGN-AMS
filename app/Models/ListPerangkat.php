<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;


class ListPerangkat extends Model
{
    use HasFactory;

    protected $table = 'listperangkat';

    public $timestamps = false;

    protected $primaryKey = 'id_perangkat';

    protected $fillable = [
        'id_perangkat',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_perangkat',
        'perangkat_ke',
        'kode_brand',
        'type',
        'uawal',
        'uakhir',
        'milik',
    ];

    public function getRouteKeyName()
    {
        return 'id_perangkat';
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    public function jenisperangkat()
    {
        return $this->belongsTo(JenisPerangkat::class, 'kode_perangkat', 'kode_perangkat');
    }

    public function brandperangkat()
    {
        return $this->belongsTo(BrandPerangkat::class, 'kode_brand', 'kode_brand');
    }

    public function historiperangkat()
    {
        return $this->hasMany(HistoriPerangkat::class, 'id_perangkat', 'id_perangkat');
    }

    public function rack()
    {
        return $this->hasMany(Rack::class, 'id_perangkat', 'id_perangkat');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'milik', 'id');
    }

    protected static function booted()
    {
        static::updating(function ($perangkat) {
            $changes = $perangkat->getDirty();
            if (count($changes) == 0) return;
        
                $isDikeluarkanDariRack = 
                array_key_exists('no_rack', $changes) && $changes['no_rack'] === null &&
                array_key_exists('uawal', $changes) && $changes['uawal'] === null &&
                array_key_exists('uakhir', $changes) && $changes['uakhir'] === null;

            if ($isDikeluarkanDariRack && count($changes) == 3) {
                $noRackLama = $perangkat->getOriginal('no_rack');
                $uAwalLama = $perangkat->getOriginal('uawal');
                $uAkhirLama = $perangkat->getOriginal('uakhir');
                $histori = "Dikeluarkan dari rack $noRackLama u$uAwalLama – $uAkhirLama.";
            } else {
                $histori = 'Diedit: ';
                $logPerubahan = [];

                foreach ($changes as $field => $newValue) {
                    $oldValue = $perangkat->getOriginal($field);
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
            }

            DB::table('historiperangkat')->insert([
                'id_perangkat' => $perangkat->id_perangkat,
                'kode_region' => $perangkat->kode_region,
                'kode_site' => $perangkat->kode_site,
                'no_rack' => $perangkat->no_rack,
                'kode_perangkat' => $perangkat->kode_perangkat,
                'perangkat_ke' => $perangkat->perangkat_ke,
                'kode_brand' => $perangkat->kode_brand,
                'type' => $perangkat->type,
                'uawal' => $perangkat->uawal,
                'uakhir' => $perangkat->uakhir,
                'milik' => $perangkat->milik,
                'histori' => $histori,
                'tanggal_perubahan' => Carbon::now('Asia/Jakarta'), // Menambahkan waktu dengan timezone yang diinginkan
            ]);
        });

        // Saat data dihapus
        static::deleting(function ($perangkat) {
            DB::table('historiperangkat')->insert([
                'id_perangkat' => $perangkat->id_perangkat,
                'kode_region' => $perangkat->kode_region,
                'kode_site' => $perangkat->kode_site,
                'no_rack' => $perangkat->no_rack,
                'kode_perangkat' => $perangkat->kode_perangkat,
                'perangkat_ke' => $perangkat->perangkat_ke,
                'kode_brand' => $perangkat->kode_brand,
                'type' => $perangkat->type,
                'uawal' => $perangkat->uawal,
                'uakhir' => $perangkat->uakhir,
                'milik' => $perangkat->milik,
                'histori' => 'Dihapus',
                'tanggal_perubahan' => Carbon::now('Asia/Jakarta'),
            ]);
        });
    }
}