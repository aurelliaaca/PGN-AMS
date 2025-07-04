<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearImgFolder extends Command
{
    // Command yang dipanggil di terminal
    protected $signature = 'img:clear';

    // Deskripsi singkat command
    protected $description = 'Clear all files in public/img folder';

    public function handle()
    {
        $imgPath = public_path('img');

        if (File::exists($imgPath)) {
            File::cleanDirectory($imgPath);
            $this->info('Folder public/img sudah dibersihkan.');
        } else {
            $this->error('Folder public/img tidak ditemukan.');
        }
    }
}
