<?php

namespace App\Http\Controllers;
use App\Models\HistoriPerangkat;

use Illuminate\Http\Request;

class HistoriController extends Controller
{
    public function indexHistori()
    {
        $historiperangkat = HistoriPerangkat::with(['listperangkat'])->get();
    
        return view('menu.histori', compact('historiperangkat'));
    }}
