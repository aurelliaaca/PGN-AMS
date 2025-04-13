<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;


class ListPerangkat extends Model
{
    use HasFactory;

    protected $table = 'listperangkat'; // Nama tabel di database

    public $timestamps = false; // Nggak pakai created_at dan updated_at

    protected $primaryKey = 'id_perangkat'; // Primary key custom

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
    ];

    public function getRouteKeyName()
    {
        return 'id_perangkat';
    }

    // Relasi ke tabel region
    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    // Relasi ke tabel site
    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    // Relasi ke tabel jenis perangkat
    public function jenisperangkat()
    {
        return $this->belongsTo(JenisPerangkat::class, 'kode_perangkat', 'kode_perangkat');
    }

    // Relasi ke tabel brand perangkat
    public function brandperangkat()
    {
        return $this->belongsTo(BrandPerangkat::class, 'kode_brand', 'kode_brand');
    }

    public function historiperangkat()
    {
        return $this->hasMany(HistoriPerangkat::class, 'id_perangkat', 'id_perangkat');
    }

    // Event untuk mencatat perubahan data perangkat
    protected static function booted()
    {
        // Saat data diedit (update)
        static::updating(function ($perangkat) {
            $changes = $perangkat->getDirty(); // Ambil data yang diubah
            $histori = 'Diedit: ';
            
            foreach ($changes as $field => $newValue) {
                $oldValue = $perangkat->getOriginal($field); // Ambil nilai lama
                $histori .= "$field dari '$oldValue' menjadi '$newValue'. ";
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
                'histori' => $histori,
                'tanggal_perubahan' => Carbon::now(),
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
                'histori' => 'Dihapus',
                'tanggal_perubahan' => Carbon::now(),
            ]);
        });
    }
}
