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
            $query->whereIn('kode_region', $this->regions);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Region',
            'Tipe Jaringan',
            'Segmen',
            'Jartatup Jartaplok',
            'Mainlink Backuplink',
            'Panjang',
            'Panjang Drawing',
            'Jumlah Core',
            'Jenis Kabel',
            'Tipe Kabel',
            'Status',
            'Keterangan',
            'Kode Site Insan',
            'DCI-EQX',
            'Travelling Time',
            'Verification Time',
            'Restoration Time',
            'Total Corrective Time',
            'Milik',
        ];
    }
}
