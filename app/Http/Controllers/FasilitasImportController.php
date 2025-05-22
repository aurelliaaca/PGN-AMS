<?php

namespace App\Http\Controllers;

use App\Imports\FasilitasImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class FasilitasImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        $file = $request->file('file');
        
        Excel::import(new FasilitasImport, $file);

        return redirect()->back()
        ->with('success', 'Data fasilitas berhasil diimpor')
        ->with('error', 'Terjadi kesalahan saat mengimpor fasilitas. Silakan coba lagi.');
    }
}
