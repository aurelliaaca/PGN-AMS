<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearPdfFolder extends Command
{
    // Nama command yang dipanggil di terminal
    protected $signature = 'pdf:clear';

    // Deskripsi singkat command
    protected $description = 'Clear all files in public/pdf folder';

    public function handle()
    {
        $pdfPath = public_path('pdf');

        if (File::exists($pdfPath)) {
            File::cleanDirectory($pdfPath);
            $this->info('Folder public/pdf sudah dibersihkan.');
        } else {
            $this->error('Folder public/pdf tidak ditemukan.');
        }
    }
}
