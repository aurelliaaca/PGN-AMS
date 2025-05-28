<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipeJaringan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ListJaringan extends Model
{
    use HasFactory;

    protected $table = 'listjaringan';
    protected $primaryKey = 'id_jaringan';
    public $timestamps = false;

    protected $fillable = [
        'id_jaringan',
        'kode_region',
        'kode_tipejaringan',
        'segmen',
        'jartatup_jartaplok',
        'panjang',
        'panjang_drawing',
        'jumlah_core',
        'jenis_kabel',
        'tipe_kabel',
        'status',
        'keterangan',
        'kode_site_insan',
        'travelling_time',
        'restoration_time',
        'total_corrective_time',
        'milik',
    ];

    public function tipejaringan()
    {
        return $this->belongsTo(TipeJaringan::class, 'kode_tipejaringan', 'kode_tipejaringan')
            ->select(['kode_tipejaringan', 'nama_tipejaringan']);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region')->select(['kode_region', 'nama_region']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'milik', 'id');
    }

    protected static function booted()
    {
        static::updating(function ($jaringan) {
            $changes = $jaringan->getDirty();
            if (count($changes) === 0)
                return;

            $histori = 'Diedit: ';
            $logPerubahan = [];

            foreach ($changes as $field => $newValue) {
                $oldValue = $jaringan->getOriginal($field);
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


            DB::table('historijaringan')->insert([
                'kode_region' => $jaringan->kode_region,
                'kode_tipejaringan' => $jaringan->kode_tipejaringan,
                'segmen' => $jaringan->segmen,
                'jartatup_jartaplok' => $jaringan->jartatup_jartaplok,
                'mainlink_backuplink' => $jaringan->mainlink_backuplink,
                'panjang' => $jaringan->panjang,
                'panjang_drawing' => $jaringan->panjang_drawing,
                'jumlah_core' => $jaringan->jumlah_core,
                'jenis_kabel' => $jaringan->jenis_kabel,
                'tipe_kabel' => $jaringan->tipe_kabel,
                'status' => $jaringan->status,
                'keterangan' => $jaringan->keterangan,
                'kode_site_insan' => $jaringan->kode_site_insan,
                'dci_eqx' => $jaringan->dci_eqx,
                'travelling_time' => $jaringan->travelling_time,
                'verification_time' => $jaringan->verification_time,
                'restoration_time' => $jaringan->restoration_time,
                'total_corrective_time' => $jaringan->total_corrective_time,
                'milik' => $jaringan->milik,
                'histori' => $histori,
                'tanggal_perubahan' => Carbon::now('Asia/Jakarta'),
            ]);
        });

        static::deleting(function ($jaringan) {
            DB::table('historijaringan')->insert([
                'kode_region' => $jaringan->kode_region,
                'kode_tipejaringan' => $jaringan->kode_tipejaringan,
                'segmen' => $jaringan->segmen,
                'jartatup_jartaplok' => $jaringan->jartatup_jartaplok,
                'mainlink_backuplink' => $jaringan->mainlink_backuplink,
                'panjang' => $jaringan->panjang,
                'panjang_drawing' => $jaringan->panjang_drawing,
                'jumlah_core' => $jaringan->jumlah_core,
                'jenis_kabel' => $jaringan->jenis_kabel,
                'tipe_kabel' => $jaringan->tipe_kabel,
                'status' => $jaringan->status,
                'keterangan' => $jaringan->keterangan,
                'kode_site_insan' => $jaringan->kode_site_insan,
                'dci_eqx' => $jaringan->dci_eqx,
                'travelling_time' => $jaringan->travelling_time,
                'verification_time' => $jaringan->verification_time,
                'restoration_time' => $jaringan->restoration_time,
                'total_corrective_time' => $jaringan->total_corrective_time,
                'milik' => $jaringan->milik,
                'histori' => 'Dihapus',
                'tanggal_perubahan' => Carbon::now('Asia/Jakarta'),
            ]);
        });
    }
}