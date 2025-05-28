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
        \DB::transaction(function () use ($row) {
            $regionID = $row['kode_region'];
            $alatukurKe = $this->getAlatukurKe($regionID);

            $alatukurBaru = ListAlatukur::create([
                'kode_region' => $row['kode_region'],
                'kode_alatukur' => $row['kode_alatukur'],
                'alatukur_ke' => $alatukurKe,
                'kode_brand' => $row['kode_brand'],
                'type' => $row['type'],
                'serialnumber' => $row['serialnumber'],
                'tahunperolehan' => $row['tahunperolehan'],
                'kondisi' => $row['kondisi'],
                'keterangan' => $row['keterangan'],
                'milik' => $row['milik'],
            ]);

            HistoriAlatukur::create([
                'id_alatukur' => $alatukurBaru->id_alatukur, 
                'kode_region' => $row['kode_region'],
                'kode_alatukur' => $row['kode_alatukur'],
                'alatukur_ke' => $alatukurKe,
                'kode_brand' => $row['kode_brand'],
                'type' => $row['type'],
                'serialnumber' => $row['serialnumber'],
                'tahunperolehan' => $row['tahunperolehan'],
                'kondisi' => $row['kondisi'],
                'keterangan' => $row['keterangan'],
                'histori' => 'Diimpor',
                'milik' => $row['milik'],
            ]);
        });
        return null;
    }

    protected function getAlatukurKe($kodeRegion)
    {
        $lastAlatukurKe = ListAlatukur::where('kode_region', $kodeRegion)
            ->max('alatukur_ke');
        return $lastAlatukurKe ? $lastAlatukurKe + 1 : 1;
    }
}
