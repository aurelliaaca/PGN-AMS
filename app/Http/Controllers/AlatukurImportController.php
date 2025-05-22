<?php

namespace App\Http\Controllers;

use App\Imports\AlatukurImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AlatukurImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        $file = $request->file('file');
        
        Excel::import(new AlatukurImport, $file);

        return redirect()->back()
        ->with('success', 'Data alatukur berhasil diimpor')
        ->with('error', 'Terjadi kesalahan saat mengimpor alatukur. Silakan coba lagi.');
    }
}
