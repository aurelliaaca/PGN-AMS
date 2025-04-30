<?php

namespace App\Exports;

use App\Models\ListJaringan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JaringanExport implements FromCollection, WithHeadings
{
    protected $regions;

    public function __construct($regions = null)
    {
        $this->regions = $regions;
    }

    public function collection()
    {
        $query = ListJaringan::query();

        if (!empty($this->regions)) {
            $query->whereIn('RO', $this->regions);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'RO',
            'tipe_jaringan',
            'segmen',
            'jartatup_jartaplok',
            'mainlink_backuplink',
            'panjang',
            'panjang_drawing',
            'jumlah_core',
            'jenis_kabel',
            'tipe_kabel',
            'status',
            'ket',
            'ket2',
            'kode_site_insan',
            'update',
            'route',
            'dci_eqx',
        ];
    }
}
