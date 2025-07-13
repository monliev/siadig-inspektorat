<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewServiceRequest extends Notification
{
    use Queueable;

    public $serviceRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // Kita akan mulai dengan notifikasi database (untuk ditampilkan di dalam aplikasi)
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * Ini adalah data yang akan disimpan di database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->serviceRequest->id,
            'applicant_name' => $this->serviceRequest->applicant->name,
            'message' => 'Permohonan baru telah diajukan.',
            'url' => route('service-requests.show', $this->serviceRequest->id),
        ];
    }
}