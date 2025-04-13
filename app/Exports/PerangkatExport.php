<?php

namespace App\Exports;

use App\Models\ListPerangkat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PerangkatExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua data perangkat yang ingin diekspor
        return ListPerangkat::all();
    }

    public function headings(): array
    {
        // Tentukan heading untuk kolom yang akan diekspor
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
        ];
    }
}
