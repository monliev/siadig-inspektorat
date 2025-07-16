<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Disposition;
use App\Models\Document;
use App\Models\DispositionResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class WhatsAppService
{
    protected $baseUrl;
    protected $apiKey;
    protected $sessionName = 'default';

    public function __construct()
    {
    // Hapus sementara pembacaan dari .env
    // $this->baseUrl = env('WAHA_API_URL');
    // $this->apiKey = env('WAHA_API_KEY');

    // 'Hardcode' langsung nilainya di sini untuk tes
    $this->baseUrl = 'http://203.194.114.220:3000';
    $this->apiKey = 'KunciRahasiaSuperAmanAnda';
    }

    /**
     * Mengirim notifikasi disposisi baru (HANYA TEKS & LINK)
     */
    /**
     * Mengirim notifikasi disposisi baru ke SEMUA penerima.
     */
    /**
     * Mengirim notifikasi disposisi baru ke SATU penerima.
     * Fungsi ini sekarang akan mengembalikan true jika berhasil, false jika gagal.
     */
    // Kode yang benar
    public function sendNewDispositionNotification(Disposition $disposition, \App\Models\User $recipient, string $magicLink): bool

    {
        // LOG 1: Memeriksa konfigurasi dasar
        if (!$this->baseUrl) {
            Log::error('[WA Service] GAGAL: Variabel WAHA_API_URL belum diatur di file .env.');
            return false; // Kembalikan false jika gagal
        }

        if (!$recipient->phone_number) {
            // LOG 2: Mencatat jika user yang dikirim tidak punya nomor HP
            Log::warning("[WA Service] PENGGUNA DILEWATI: User '{$recipient->name}' (ID: {$recipient->id}) tidak memiliki nomor HP.");
            return false; // Kembalikan false
        }

        // --- Membuat Pesan ---
        $chatId = $recipient->phone_number . '@c.us';
        $downloadLink = route('documents.download', $disposition->document->id);
        $documentOrigin = $disposition->document->fromEntity->name ?? 'Dokumen Internal';
        $pengirim = $disposition->fromUser;
        $sender = $disposition->onBehalfOfUser ?? $disposition->fromUser;

        $message = "*Yth. {$recipient->name},*\n\n";
        $message .= "Anda menerima disposisi baru dari: *{$sender->name}* ({$sender->jabatan}).\n\n";
        $message .= "*Terkait Dokumen:*\n{$disposition->document->title}\n\n";
        $message .= "*Instruksi:*\n{$disposition->instructions}\n\n";
        $message .= "*Silakan unduh file lampiran di link berikut:*\n{$downloadLink}\n\n";
        $message .= "*Untuk merespons disposisi, silakan klik link sekali pakai di bawah ini (berlaku 24 jam):*\n" . $magicLink;

        $payload = [
            'session' => $this->sessionName,
            'chatId' => $chatId,
            'text' => $message,
        ];
        
        Log::info("Mencoba mengirim notifikasi DISPOSISI BARU ke {$recipient->name}", ['payload_data' => $payload]);

        try {
            $response = Http::withHeaders(['X-Api-Key' => $this->apiKey])->post("{$this->baseUrl}/api/sendText", $payload);
            
            // PERBAIKAN: Cek status respons dari Waha
            if ($response->successful()) {
                Log::info("[WA Service] Respons SUKSES dari Waha untuk {$recipient->name}: " . $response->body());
                return true; // Berhasil
            } else {
                Log::error("[WA Service] Respons GAGAL dari Waha untuk {$recipient->name}. Status: {$response->status()}. Body: " . $response->body());
                return false; // Gagal
            }

        } catch (\Exception $e) {
            Log::error("[WA Service] ERROR KONEKSI ke Waha saat kirim ke {$recipient->name}: " . $e->getMessage());
            return false; // Gagal karena error koneksi
        }
    }
    
    /**
     * Mengirim notifikasi ke pimpinan saat staf memberi tanggapan.
     */
    public function sendResponseNotification(DispositionResponse $response)
    {
        // Notifikasi dikirim ke PENGIRIM disposisi asli (pimpinan)
        $recipient = $response->disposition->fromUser;
        
        if (!$this->baseUrl || !$recipient || !$recipient->phone_number) {
            return;
        }
        
        $chatId = $recipient->phone_number . '@c.us';
        $responseLink = route('dispositions.show', $response->disposition->id);

        $message = "*SIADIG: Tanggapan Disposisi Diterima*\n\n";
        $message .= "Anda menerima tanggapan baru dari *{$response->user->name}* terkait dokumen '{$response->disposition->document->title}'.\n\n";
        $message .= "*Isi Tanggapan:*\n" . Str::limit($response->notes, 100) . "\n\n";
        $message .= "*Silakan klik link berikut untuk melihat detailnya:*\n{$responseLink}";
        
        $payload = [
            'session' => $this->sessionName,
            'chatId' => $chatId,
            'text' => $message,
        ];
        
        Log::info('Mencoba mengirim notifikasi TEKS BALASAN DISPOSISI ke Waha.', ['payload_data' => $payload]);
        
        Http::withHeaders(['X-Api-Key' => $this->apiKey])->post("{$this->baseUrl}/api/sendText", $payload);
    }

    /**
     * Mengirim notifikasi ke Admin/Auditor saat klien mengunggah dokumen.
     */
    public function sendClientSubmissionNotification(Document $document)
    {
        $recipient = $document->documentRequest?->creator;
        if (!$this->baseUrl || !$recipient || !$recipient->phone_number) {
            return;
        }
        
        $chatId = $recipient->phone_number . '@c.us';
        
        $reviewLink = route('client-submissions.show', $document->id);

        $message = "*SIADIG: Unggahan Dokumen Baru Diterima*\n\n";
        $message .= "Pengguna dari *{$document->uploader->entity->name}* telah mengirimkan dokumen baru:\n\n";
        $message .= "*Judul Dokumen:* {$document->title}\n";
        $message .= "*Untuk Permintaan:* {$document->documentRequest->title}\n\n";
        $message .= "*Silakan klik link di bawah ini untuk me-review:*\n{$reviewLink}";

        $payload = [
            'session' => $this->sessionName,
            'chatId' => $chatId,
            'text' => $message,
        ];
        
        Log::info('Mencoba mengirim notifikasi TEKS UNGGAHAN KLIEN ke Waha.', ['payload_data' => $payload]);

        Http::withHeaders(['X-Api-Key' => $this->apiKey])->post("{$this->baseUrl}/api/sendText", $payload);
    }

    public function sendSimpleText(string $phoneNumber, string $message)
    {
        if (!$this->baseUrl || !$phoneNumber) {
            Log::error('[WA Service] GAGAL: URL Waha atau nomor telepon kosong.');
            return;
        }

        $chatId = $phoneNumber . '@c.us';

        $payload = [
            'session' => $this->sessionName,
            'chatId' => $chatId,
            'text' => $message,
        ];
        
        Log::info("[WA Service] Mengirim simple text ke {$phoneNumber}", ['payload' => $payload]);
        
        try {
            $response = Http::withHeaders(['X-Api-Key' => $this->apiKey])->post("{$this->baseUrl}/api/sendText", $payload);
            Log::info("[WA Service] Respons Waha: " . $response->body());
        } catch (\Exception $e) {
            Log::error("[WA Service] ERROR KONEKSI ke Waha: " . $e->getMessage());
        }
    }
}