<?php

namespace App\Http\Controllers;

use App\Imports\PerangkatImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PerangkatImportController extends Controller
{
    public function import(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        
        // Proses impor data
        Excel::import(new PerangkatImport, $file);

        return redirect()->back()
        ->with('success', 'Data perangkat berhasil diimpor')
        ->with('error', 'Terjadi kesalahan saat mengimpor perangkat. Silakan coba lagi.');
    }
}
