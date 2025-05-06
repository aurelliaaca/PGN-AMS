<?php

namespace App\Exports;

use App\Models\ListPerangkat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PerangkatExport implements FromCollection, WithHeadings
{
    protected $regions;

    public function __construct($regions = null)
    {
        $this->regions = $regions;
    }

    public function collection()
    {
        $query = ListPerangkat::query();

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
            'Site',
            'No Rack',
            'Perangkat',
            'Perangkat ke',
            'Brand',
            'Type',
            'UAwal',
            'UAkhir',
            'Milik',
        ];
    }
}
