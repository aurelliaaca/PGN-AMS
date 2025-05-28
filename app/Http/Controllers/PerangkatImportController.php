<?php

namespace App\Http\Controllers;

use App\Imports\PerangkatImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PerangkatImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        $file = $request->file('file');
        
        Excel::import(new PerangkatImport, $file);

        return redirect()->back()
        ->with('success', 'Data perangkat berhasil diimpor')
        ->with('error', 'Terjadi kesalahan saat mengimpor perangkat. Silakan coba lagi.');
    }
}
