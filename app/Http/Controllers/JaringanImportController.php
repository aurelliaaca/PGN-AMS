<?php

namespace App\Http\Controllers;

use App\Imports\JaringanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class JaringanImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        
        Excel::import(new JaringanImport, $file);

        return redirect()->back()
        ->with('success', 'Data jaringan berhasil diimpor')
        ->with('error', 'Terjadi kesalahan saat mengimpor jaringan. Silakan coba lagi.');
    }
}
