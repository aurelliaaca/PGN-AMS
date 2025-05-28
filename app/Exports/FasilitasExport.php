<?php

namespace App\Exports;

use App\Models\ListFasilitas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FasilitasExport implements FromCollection, WithHeadings
{
    protected $regions;

    public function __construct($regions = null)
    {
        $this->regions = $regions;
    }

    public function collection()
    {
        $query = ListFasilitas::query();

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
            'Fasilitas',
            'Fasilitas ke',
            'Brand',
            'Type',
            'Serial Number',
            'Jumlah Fasilitas',
            'Status',
            'UAwal',
            'UAkhir',
            'Milik',
        ];
    }
}
