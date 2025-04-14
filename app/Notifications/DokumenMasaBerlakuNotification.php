<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\VerifikasiDokumen;
use Carbon\Carbon;

class DokumenMasaBerlakuNotification extends Notification
{
    use Queueable;

    protected $dokumen;
    protected $sisaWaktu;

    /**
     * Create a new notification instance.
     */
    public function __construct(VerifikasiDokumen $dokumen, $sisaWaktu)
    {
        $this->dokumen = $dokumen;
        $this->sisaWaktu = $sisaWaktu;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $sisaWaktuText = $this->getSisaWaktuText();
        
        return (new MailMessage)
            ->subject('Pengingat Masa Berlaku Dokumen')
            ->greeting('Halo ' . $notifiable->name)
            ->line('Dokumen Anda akan berakhir dalam ' . $sisaWaktuText . '.')
            ->line('Nama Dokumen: ' . $this->dokumen->nama_dokumen)
            ->line('Tanggal Berakhir: ' . $this->dokumen->masa_berlaku->format('d/m/Y H:i'))
            ->action('Lihat Detail', url('/verifikasi/user'))
            ->line('Silakan perpanjang dokumen Anda sebelum masa berlaku habis.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $sisaWaktuText = $this->getSisaWaktuText();
        
        return [
            'dokumen_id' => $this->dokumen->id,
            'nama_dokumen' => $this->dokumen->nama_dokumen,
            'sisa_waktu' => $sisaWaktuText,
            'tanggal_berakhir' => $this->dokumen->masa_berlaku->format('d/m/Y H:i'),
            'message' => 'Dokumen Anda akan berakhir dalam ' . $sisaWaktuText . '.',
        ];
    }
    
    /**
     * Get the text representation of the remaining time
     * 
     * @return string
     */
    private function getSisaWaktuText()
    {
        switch ($this->sisaWaktu) {
            case '1bulan':
                return '1 bulan';
            case '1minggu':
                return '1 minggu';
            case '5menit':
                return '5 menit';
            default:
                return $this->sisaWaktu;
        }
    }
} 