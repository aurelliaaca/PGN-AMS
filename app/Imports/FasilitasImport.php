<?php
namespace App\Imports;

use App\Models\HistoriFasilitas;
use App\Models\ListFasilitas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FasilitasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Ambil kode_site untuk menentukan fasilitas_ke
        $siteId = $row['kode_site'];
        $fasilitasKe = $this->getFasilitasKe($siteId);

        // Simpan ke tabel ListFasilitas dan simpan object-nya
        $fasilitasBaru = ListFasilitas::create([
            'kode_region'    => $row['kode_region'],
            'kode_site'      => $row['kode_site'],
            'no_rack'        => $row['no_rack'],
            'kode_fasilitas' => $row['kode_fasilitas'],
            'fasilitas_ke'   => $fasilitasKe,
            'kode_brand'     => $row['kode_brand'],
            'type'           => $row['type'],
            'uawal'          => $row['uawal'],
            'uakhir'         => $row['uakhir'],
        ]);

        // Simpan juga ke tabel HistoriFasilitas dengan id dari fasilitasBaru
        HistoriFasilitas::create([
            'id_fasilitas'   => $fasilitasBaru->id_fasilitas, // ambil ID dari ListFasilitas
            'kode_region'    => $row['kode_region'],
            'kode_site'      => $row['kode_site'],
            'no_rack'        => $row['no_rack'],
            'kode_fasilitas' => $row['kode_fasilitas'],
            'fasilitas_ke'   => $fasilitasKe,
            'kode_brand'     => $row['kode_brand'],
            'type'           => $row['type'],
            'uawal'          => $row['uawal'],
            'uakhir'         => $row['uakhir'],
            'histori'        => 'Diimpor',
        ]);

        return null;
    }

    protected function getFasilitasKe($kodeSite)
    {
        $lastFasilitasKe = ListFasilitas::where('kode_site', $kodeSite)
                                    ->max('fasilitas_ke');
        return $lastFasilitasKe ? $lastFasilitasKe + 1 : 1;
    }
}
