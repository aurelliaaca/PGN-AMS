<?php
namespace App\Imports;

use App\Models\HistoriPerangkat;
use App\Models\ListPerangkat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;


class PerangkatImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        \DB::transaction(function () use ($row) {

            $siteId = $row['kode_site'];
            $perangkatKe = $this->getPerangkatKe($siteId);

            $perangkatBaru = ListPerangkat::create([
                'kode_region' => $row['kode_region'],
                'kode_site' => $row['kode_site'],
                'no_rack' => $row['no_rack'],
                'kode_perangkat' => $row['kode_perangkat'],
                'perangkat_ke' => $perangkatKe,
                'kode_brand' => $row['kode_brand'],
                'type' => $row['type'],
                'uawal' => $row['uawal'],
                'uakhir' => $row['uakhir'],
                'milik' => $row['milik'],
            ]);

            HistoriPerangkat::create([
                'id_perangkat' => $perangkatBaru->id_perangkat,
                'kode_region' => $row['kode_region'],
                'kode_site' => $row['kode_site'],
                'no_rack' => $row['no_rack'],
                'kode_perangkat' => $row['kode_perangkat'],
                'perangkat_ke' => $perangkatKe,
                'kode_brand' => $row['kode_brand'],
                'type' => $row['type'],
                'uawal' => $row['uawal'],
                'uakhir' => $row['uakhir'],
                'milik' => $row['milik'],
                'histori' => 'Diimpor',
                'tanggal_perubahan' => Carbon::now('Asia/Jakarta'),
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
                            'id_perangkat' => $perangkatBaru->id_perangkat,
                            'milik' => $row['milik'],
                            'updated_at' => now()
                        ]
                    );
                }
            }
        });
        return null;
    }

    protected function getPerangkatKe($kodeSite)
    {
        $lastPerangkatKe = ListPerangkat::where('kode_site', $kodeSite)
            ->max('perangkat_ke');
        return $lastPerangkatKe ? $lastPerangkatKe + 1 : 1;
    }
}
