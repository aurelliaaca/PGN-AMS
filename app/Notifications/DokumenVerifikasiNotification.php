<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\VerifikasiDokumen;

class DokumenVerifikasiNotification extends Notification
{
    use Queueable;

    protected $dokumen;

    /**
     * Create a new notification instance.
     */
    public function __construct(VerifikasiDokumen $dokumen)
    {
        $this->dokumen = $dokumen;
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
        $status = $this->dokumen->status === 'diterima' ? 'diterima' : 'ditolak';
        $message = $this->dokumen->status === 'diterima' 
            ? 'Dokumen NDA dan DCAF Anda telah diverifikasi dan diterima.' 
            : 'Dokumen NDA dan DCAF Anda telah diverifikasi dan ditolak.';
        
        return (new MailMessage)
            ->subject('Status Verifikasi Dokumen')
            ->greeting('Halo ' . $notifiable->name)
            ->line($message)
            ->line('Status: ' . ucfirst($status))
            ->line('Tanggal Verifikasi: ' . now()->format('d/m/Y H:i'))
            ->line('Periode: ' . $this->dokumen->start_date->format('d/m/Y') . ' - ' . $this->dokumen->end_date->format('d/m/Y'))
            ->action('Lihat Detail', url('/verifikasi/user'))
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'dokumen_id' => $this->dokumen->id,
            'status' => $this->dokumen->status,
            'message' => $this->dokumen->status === 'diterima' 
                ? 'Dokumen NDA dan DCAF Anda telah diverifikasi dan diterima.' 
                : 'Dokumen NDA dan DCAF Anda telah diverifikasi dan ditolak.',
        ];
    }
} 