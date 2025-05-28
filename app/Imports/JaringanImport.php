<?php
namespace App\Imports;

use App\Models\HistoriJaringan;
use App\Models\ListJaringan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JaringanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $jaringanBaru = ListJaringan::create([
            'kode_region'           => $row['kode_region'],
            'kode_tipejaringan'     => $row['kode_tipejaringan'],
            'segmen'                => $row['segmen'],
            'jartatup_jartaplok'    => $row['jartatup_jartaplok'],
            'mainlink_backuplink'   => $row['mainlink_backuplink'],
            'panjang'               => $row['panjang'],
            'panjang_drawing'       => $row['panjang_drawing'],
            'jumlah_core'           => $row['jumlah_core'],
            'jenis_kabel'           => $row['jenis_kabel'],
            'tipe_kabel'            => $row['tipe_kabel'],
            'status'                => $row['status'],
            'keterangan'            => $row['keterangan'],
            'kode_site_insan'       => $row['kode_site_insan'],
            'dci_eqx'               => $row['dci_eqx'],
            'travelling_time'       => $row['travelling_time'],
            'verification_time'     => $row['verification_time'],
            'restoration_time'      => $row['restoration_time'],
            'total_corrective_time' => $row['total_corrective_time'],
            'milik' => $row['milik'],
        ]);

        HistoriJaringan::create([
            'id_jaringan'           => $jaringanBaru->id_jaringan,
            'kode_region'           => $row['kode_region'],
            'kode_tipejaringan'     => $row['kode_tipejaringan'],
            'segmen'                => $row['segmen'],
            'jartatup_jartaplok'    => $row['jartatup_jartaplok'],
            'mainlink_backuplink'   => $row['mainlink_backuplink'],
            'panjang'               => $row['panjang'],
            'panjang_drawing'       => $row['panjang_drawing'],
            'jumlah_core'           => $row['jumlah_core'],
            'jenis_kabel'           => $row['jenis_kabel'],
            'tipe_kabel'            => $row['tipe_kabel'],
            'status'                => $row['status'],
            'keterangan'            => $row['keterangan'],
            'kode_site_insan'       => $row['kode_site_insan'],
            'dci_eqx'               => $row['dci_eqx'],
            'travelling_time'       => $row['travelling_time'],
            'verification_time'     => $row['verification_time'],
            'restoration_time'      => $row['restoration_time'],
            'total_corrective_time' => $row['total_corrective_time'],
            'milik'                 => $row['milik'],
            'histori'               => 'Diimpor',
        ]);

        return null;
    }
}
