<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\VerifikasiDcaf;

class DcafNotification extends Notification
{
    use Queueable;

    protected $verifikasiDcaf;

    /**
     * Create a new notification instance.
     */
    public function __construct(VerifikasiDcaf $verifikasiDcaf)
    {
        $this->verifikasiDcaf = $verifikasiDcaf;
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
        $status = $this->verifikasiDcaf->status === 'diterima' ? 'diterima' : 'ditolak';
        $message = $this->verifikasiDcaf->status === 'diterima' 
            ? 'Permohonan DCAF Anda telah diverifikasi dan disetujui.' 
            : 'Permohonan DCAF Anda telah ditinjau dan ditolak.';
        
        $mailMessage = (new MailMessage)
            ->subject('Status Permohonan DCAF')
            ->greeting('Halo ' . $notifiable->name)
            ->line($message)
            ->line('Status: ' . ucfirst($status))
            ->line('Tanggal Verifikasi: ' . $this->verifikasiDcaf->updated_at->format('d/m/Y H:i'));
            
        if ($this->verifikasiDcaf->status === 'diterima') {
            $mailMessage->action('Download Dokumen', url('/pendaftaran/download/DATA_CENTER_FORM-' . $this->verifikasiDcaf->pendaftaran_vms_id . '.pdf'));
        }
        
        $mailMessage->line('Terima kasih telah menggunakan layanan kami.');
        
        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'dcaf_id' => $this->verifikasiDcaf->id,
            'status' => $this->verifikasiDcaf->status,
            'message' => $this->verifikasiDcaf->status === 'diterima' 
                ? 'Permohonan DCAF Anda telah diverifikasi dan disetujui.' 
                : 'Permohonan DCAF Anda telah ditinjau dan ditolak.',
        ];
    }
}