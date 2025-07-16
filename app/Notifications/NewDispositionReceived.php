<?php

namespace App\Notifications;

use App\Models\Disposition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDispositionReceived extends Notification
{
    use Queueable;

    public $disposition;

    /**
     * Create a new notification instance.
     */
    public function __construct(Disposition $disposition)
    {
        $this->disposition = $disposition;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Kita akan menyimpan notifikasi ini ke database
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Data ini yang akan disimpan di kolom 'data' pada tabel notifikasi
        return [
            'disposition_id' => $this->disposition->id,
            'from_user_name' => $this->disposition->fromUser->name,
            'document_title' => $this->disposition->document->title,
            'message' => 'Anda menerima disposisi baru.',
            'url' => route('dispositions.show', $this->disposition->id),
        ];
    }
}