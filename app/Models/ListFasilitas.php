<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;

class ListFasilitas extends Model
{
    use HasFactory;

    protected $table = 'listfasilitas'; 

    public $timestamps = false; // nggak pakai created_at dan updated_at

    protected $primaryKey = 'id_fasilitas'; 

    protected $fillable = [
        'id_fasilitas',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_fasilitas',
        'fasilitas_ke',
        'kode_brand',
        'type',
        'serialnumber',
        'jml_fasilitas',
        'status',
        'uawal',
        'uakhir',
        'milik',
    ];

    public function getRouteKeyName()
    {
        return 'id_fasilitas';
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    public function jenisfasilitas()
    {
        return $this->belongsTo(JenisFasilitas::class, 'kode_fasilitas', 'kode_fasilitas');
    }

    public function brandfasilitas()
    {
        return $this->belongsTo(BrandFasilitas::class, 'kode_brand', 'kode_brand');
    }

    public function rack()
    {
        return $this->hasMany(Rack::class, 'id_fasilitas', 'id_fasilitas');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'milik', 'id');
    }

    protected static function booted()
    {
        static::updating(function ($fasilitas) {
            $changes = $fasilitas->getDirty();
            if (count($changes) == 0) return;
        
            $isDikeluarkanDariRack = 
                array_key_exists('no_rack', $changes) && $changes['no_rack'] === null &&
                array_key_exists('uawal', $changes) && $changes['uawal'] === null &&
                array_key_exists('uakhir', $changes) && $changes['uakhir'] === null;

            if ($isDikeluarkanDariRack && count($changes) == 3) {
                $noRackLama = $fasilitas->getOriginal('no_rack');
                $uAwalLama = $fasilitas->getOriginal('uawal');
                $uAkhirLama = $fasilitas->getOriginal('uakhir');
                $histori = "Dikeluarkan dari rack $noRackLama u$uAwalLama – $uAkhirLama.";
            } else {
                $histori = 'Diedit: ';
                $logPerubahan = [];

                foreach ($changes as $field => $newValue) {
                    $oldValue = $fasilitas->getOriginal($field);
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


            DB::table('historifasilitas')->insert([
                'id_fasilitas' => $fasilitas->id_fasilitas,
                'kode_region' => $fasilitas->kode_region,
                'kode_site' => $fasilitas->kode_site,
                'no_rack' => $fasilitas->no_rack,
                'kode_fasilitas' => $fasilitas->kode_fasilitas,
                'fasilitas_ke' => $fasilitas->fasilitas_ke,
                'kode_brand' => $fasilitas->kode_brand,
                'type' => $fasilitas->type,
                'serialnumber' => $fasilitas->serialnumber,
                'jml_fasilitas' => $fasilitas->jml_fasilitas,
                'status' => $fasilitas->status,
                'uawal' => $fasilitas->uawal,
                'uakhir' => $fasilitas->uakhir,
                'milik' => $fasilitas->milik,
                'histori' => $histori,
                'tanggal_perubahan' => Carbon::now('Asia/Jakarta'), 
            ]);
        });

        static::deleting(function ($fasilitas) {
            DB::table('historifasilitas')->insert([
                'id_fasilitas' => $fasilitas->id_fasilitas,
                'kode_region' => $fasilitas->kode_region,
                'kode_site' => $fasilitas->kode_site,
                'no_rack' => $fasilitas->no_rack,
                'kode_fasilitas' => $fasilitas->kode_fasilitas,
                'fasilitas_ke' => $fasilitas->fasilitas_ke,
                'kode_brand' => $fasilitas->kode_brand,
                'type' => $fasilitas->type,
                'serialnumber' => $fasilitas->serialnumber,
                'jml_fasilitas' => $fasilitas->jml_fasilitas,
                'status' => $fasilitas->status,
                'uawal' => $fasilitas->uawal,
                'uakhir' => $fasilitas->uakhir,
                'milik' => $fasilitas->milik,
                'histori' => 'Dihapus',
                'tanggal_perubahan' => Carbon::now('Asia/Jakarta'), 
            ]);
        });
    }
}