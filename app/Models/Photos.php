<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'photos';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'title',
        'text',
        'file_path',
    ];

    // Jika Anda ingin menambahkan timestamp otomatis
    public $timestamps = true;
} 