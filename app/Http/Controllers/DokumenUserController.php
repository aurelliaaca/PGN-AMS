<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifikasiDokumen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenUserController extends Controller
{
    public function index()
    {
        $dokumen = VerifikasiDokumen::where('user_id', Auth::id())->get();
        return view('user.upload', compact('dokumen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        VerifikasiDokumen::create([
            'user_id' => Auth::id(),
            'nama_dokumen' => $request->nama_dokumen,
            'file_path' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Dokumen berhasil diupload dan menunggu verifikasi.');
    }
}
