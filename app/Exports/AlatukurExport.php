<?php

namespace App\Exports;

use App\Models\ListAlatukur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlatukurExport implements FromCollection, WithHeadings
{
    protected $regions;

    public function __construct($regions = null)
    {
        $this->regions = $regions;
    }

    public function collection()
    {
        $query = ListAlatukur::query();

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
            'Alat Ukur',
            'Alat Ukur ke',
            'Brand',
            'Type',
            'Serial Number',
            'Tahun Perolehan',
            'Kondisi',
            'Keterangan',
            'Milik',
        ];
    }
}
