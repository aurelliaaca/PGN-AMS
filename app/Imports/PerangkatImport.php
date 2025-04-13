<?php
namespace App\Imports;

use App\Models\HistoriPerangkat;
use App\Models\ListPerangkat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PerangkatImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Ambil kode_site untuk menentukan perangkat_ke
        $siteId = $row['kode_site'];
        $perangkatKe = $this->getPerangkatKe($siteId);

        // Simpan ke tabel ListPerangkat dan simpan object-nya
        $perangkatBaru = ListPerangkat::create([
            'kode_region'    => $row['kode_region'],
            'kode_site'      => $row['kode_site'],
            'no_rack'        => $row['no_rack'],
            'kode_perangkat' => $row['kode_perangkat'],
            'perangkat_ke'   => $perangkatKe,
            'kode_brand'     => $row['kode_brand'],
            'type'           => $row['type'],
            'uawal'          => $row['uawal'],
            'uakhir'         => $row['uakhir'],
        ]);

        // Simpan juga ke tabel HistoriPerangkat dengan id dari perangkatBaru
        HistoriPerangkat::create([
            'id_perangkat'   => $perangkatBaru->id_perangkat, // ambil ID dari ListPerangkat
            'kode_region'    => $row['kode_region'],
            'kode_site'      => $row['kode_site'],
            'no_rack'        => $row['no_rack'],
            'kode_perangkat' => $row['kode_perangkat'],
            'perangkat_ke'   => $perangkatKe,
            'kode_brand'     => $row['kode_brand'],
            'type'           => $row['type'],
            'uawal'          => $row['uawal'],
            'uakhir'         => $row['uakhir'],
            'histori'        => 'Diimpor',
        ]);

        return null;
    }

    protected function getPerangkatKe($kodeSite)
    {
        $lastPerangkatKe = ListPerangkat::where('kode_site', $kodeSite)
                                    ->max('perangkat_ke');
        return $lastPerangkatKe ? $lastPerangkatKe + 1 : 1;
    }
}
