<?php

namespace App\Jobs;

use App\Models\Disposition;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class SendDispositionNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $disposition;
    protected $recipients;

    public function __construct(Disposition $disposition, $recipients)
    {
        $this->disposition = $disposition;
        $this->recipients = $recipients;
    }

    public function handle(): void
    {
        $whatsapp = new WhatsAppService();

        foreach ($this->recipients as $recipient) {
            if (!$recipient->phone_number) {
                Log::warning("[QUEUE] Dilewati: {$recipient->name} tidak punya nomor HP.");
                continue;
            }

            try {
                $magicLink = URL::temporarySignedRoute(
                    'dispositions.respond.magic',
                    now()->addHours(24),
                    ['token' => $this->disposition->response_token, 'user' => $recipient->id]
                );

                // Panggil method yang ada di WhatsAppService Anda
                $whatsapp->sendNewDispositionNotification($this->disposition, $recipient, $magicLink);

            } catch (\Exception $e) {
                Log::error("[QUEUE] Gagal kirim WA ke {$recipient->name}: " . $e->getMessage());
            }

            // Jeda 5 detik antar pengiriman
            sleep(5);
        }
    }
}