<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VerifikasiDokumen;
use App\Notifications\DokumenMasaBerlakuNotification;
use Carbon\Carbon;

class CheckDokumenMasaBerlaku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dokumen:check-masa-berlaku';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek masa berlaku dokumen dan kirim notifikasi jika akan berakhir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan masa berlaku dokumen...');
        
        // Cek dokumen yang akan berakhir dalam 1 bulan
        $this->checkDokumenMasaBerlaku('1bulan', 30, '30 hari');
        
        // Cek dokumen yang akan berakhir dalam 1 minggu
        $this->checkDokumenMasaBerlaku('1minggu', 7, '7 hari');
        
        // Cek dokumen yang akan berakhir dalam 5 menit
        $this->checkDokumenMasaBerlaku('5menit', 5/24/60, '5 menit'); // 5 menit dalam hari
        
        $this->info('Pengecekan masa berlaku dokumen selesai.');
    }
    
    /**
     * Cek dokumen yang akan berakhir dalam waktu tertentu
     * 
     * @param string $sisaWaktu Jenis sisa waktu ('1bulan', '1minggu', atau '5menit')
     * @param int|float $days Jumlah hari sebelum masa berlaku habis
     * @param string $displayText Teks yang akan ditampilkan di output
     */
    private function checkDokumenMasaBerlaku($sisaWaktu, $days, $displayText)
    {
        $today = Carbon::now();
        $targetDate = $today->copy()->addDays($days);
        
        // Ambil dokumen yang akan berakhir dalam waktu tertentu
        $dokumen = VerifikasiDokumen::where('status', 'diterima')
            ->whereDate('masa_berlaku', $targetDate->format('Y-m-d'))
            ->whereTime('masa_berlaku', $targetDate->format('H:i:s'))
            ->with('user')
            ->get();
        
        $count = 0;
        
        foreach ($dokumen as $d) {
            // Kirim notifikasi ke user
            $d->user->notify(new DokumenMasaBerlakuNotification($d, $sisaWaktu));
            $count++;
        }
        
        $this->info("Ditemukan {$count} dokumen yang akan berakhir dalam {$displayText}.");
    }
}
