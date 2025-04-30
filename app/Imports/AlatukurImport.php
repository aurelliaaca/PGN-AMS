<?php
namespace App\Imports;

use App\Models\HistoriAlatukur;
use App\Models\ListAlatukur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlatukurImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Ambil kode_site untuk menentukan alatukur_ke
        $regionID = $row['kode_region'];
        $alatukurKe = $this->getAlatukurKe($regionID);

        // Simpan ke tabel ListAlatukur dan simpan object-nya
        $alatukurBaru = ListAlatukur::create([
            'kode_region'    => $row['kode_region'],
            'kode_alatukur' => $row['kode_alatukur'],
            'alatukur_ke'   => $alatukurKe,
            'kode_brand'     => $row['kode_brand'],
            'type'           => $row['type'],
            'serialnumber' => $row['tahunperolehan'],
            'tahunperolehan' => $row['tahunperolehan'],
            'kondisi'         => $row['kondisi'],
            'keterangan'         => $row['keterangan'],
        ]);

        // Simpan juga ke tabel HistoriAlatukur dengan id dari alatukurBaru
        HistoriAlatukur::create([
            'id_alatukur'   => $alatukurBaru->id_alatukur, // ambil ID dari ListAlatukur
            'kode_region'    => $row['kode_region'],
            'kode_alatukur' => $row['kode_alatukur'],
            'alatukur_ke'   => $alatukurKe,
            'kode_brand'     => $row['kode_brand'],
            'type'           => $row['type'],
            'serialnumber' => $row['tahunperolehan'],
            'tahunperolehan' => $row['tahunperolehan'],
            'kondisi'         => $row['kondisi'],
            'keterangan'         => $row['keterangan'],
            'histori'        => 'Diimpor',
        ]);

        return null;
    }

    protected function getAlatukurKe($kodeRegion)
    {
        $lastAlatukurKe = ListAlatukur::where('kode_region', $kodeRegion)
                                    ->max('alatukur_ke');
        return $lastAlatukurKe ? $lastAlatukurKe + 1 : 1;
    }
}
