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
        \DB::transaction(function () use ($row) {
            $siteId = $row['kode_site'];
            $fasilitasKe = $this->getFasilitasKe($siteId);

            $fasilitasBaru = ListFasilitas::create([
                'kode_region' => $row['kode_region'],
                'kode_site' => $row['kode_site'],
                'no_rack' => $row['no_rack'],
                'kode_fasilitas' => $row['kode_fasilitas'],
                'fasilitas_ke' => $fasilitasKe,
                'kode_brand' => $row['kode_brand'],
                'type' => $row['type'],
                'serialnumber' => $row['serialnumber'],
                'jml_fasilitas' => $row['jml_fasilitas'],
                'status' => $row['status'],
                'uawal' => $row['uawal'],
                'uakhir' => $row['uakhir'],
                'milik' => $row['milik'],
            ]);

            HistoriFasilitas::create([
                'id_fasilitas' => $fasilitasBaru->id_fasilitas,
                'kode_region' => $row['kode_region'],
                'kode_site' => $row['kode_site'],
                'no_rack' => $row['no_rack'],
                'kode_fasilitas' => $row['kode_fasilitas'],
                'fasilitas_ke' => $fasilitasKe,
                'kode_brand' => $row['kode_brand'],
                'type' => $row['type'],
                'serialnumber' => $row['serialnumber'],
                'jml_fasilitas' => $row['jml_fasilitas'],
                'status' => $row['status'],
                'uawal' => $row['uawal'],
                'uakhir' => $row['uakhir'],
                'histori' => 'Diimpor',
                'milik' => $row['milik'],
            ]);

            if (!empty($row['no_rack'])) {
                for ($u = $row['uawal']; $u <= $row['uakhir']; $u++) {
                    \DB::table('rack')->updateOrInsert(
                        [
                            'kode_region' => $row['kode_region'],
                            'kode_site' => $row['kode_site'],
                            'no_rack' => $row['no_rack'],
                            'u' => $u
                        ],
                        [
                            'id_fasilitas' => $fasilitasBaru->id_fasilitas,
                            'milik' => $row['milik'],
                            'updated_at' => now()
                        ]
                    );
                }
            }
        });

        return null;
    }
    protected function getFasilitaske($kodeSite)
    {
        $lastFasilitasKe = ListFasilitas::where('kode_site', $kodeSite)
                                    ->max('fasilitas_ke');
        return $lastFasilitasKe ? $lastFasilitasKe + 1 : 1;
    }
}