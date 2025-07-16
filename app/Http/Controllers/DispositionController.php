<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\DispositionResponse;
use App\Models\DispositionResponseAttachment;
use App\Models\Document;
use App\Models\User;
use App\Models\Role; // Pastikan Role di-import
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\WhatsAppService;

class DispositionController extends Controller
{
    /**
     * Menampilkan daftar disposisi yang diterima pengguna.
     */
    public function index()
    {
        // PERBAIKAN: Menggunakan relasi many-to-many yang baru
        $dispositions = auth()->user()->dispositions()
            ->with(['document', 'fromUser'])
            ->latest()
            ->paginate(15);
            
        return view('pages.dispositions.index', compact('dispositions'));
    }

    /**
     * Menampilkan daftar disposisi yang dikirim pengguna.
     */
    public function sent()
    {
        $dispositions = Disposition::where('from_user_id', Auth::id())
                                      // PERBAIKAN: Mengganti toUser dengan recipients
                                      ->with(['document', 'recipients'])
                                      ->latest()
                                      ->paginate(15);

        return view('pages.dispositions.sent', compact('dispositions'));
    }

    /**
     * Menampilkan ruang kerja untuk satu disposisi.
     */
    public function show(Disposition $disposition)
    {
        $this->authorize('view', $disposition);

        // PERBAIKAN: Logika status 'Dibaca' sekarang memeriksa koleksi recipients
        if ($disposition->status == 'Terkirim' && $disposition->recipients->contains(auth()->user())) {
            // Logika untuk menandai 'Dibaca' per user bisa lebih kompleks,
            // untuk saat ini kita sederhanakan dengan tidak mengubah status utama.
        }

        // PERBAIKAN: Memuat relasi 'recipients' bukan 'toUser'
        $disposition->load(['document.uploader', 'onBehalfOfUser', 'recipients', 'responses.user', 'responses.attachments']);
        
        return view('pages.dispositions.show', compact('disposition'));
    }

    /**
     * Menyimpan disposisi baru untuk banyak penerima.
     */
   /**
     * Menyimpan disposisi baru untuk banyak penerima.
     */
    public function store(Request $request, Document $document)
    {
        $request->validate([
            'instructions' => 'required|string|min:5',
            'roles' => 'nullable|array',
            'users' => 'nullable|array',
        ]);

        if (empty($request->roles) && empty($request->users)) {
            // Langsung kembali dengan pesan error untuk popup
            return back()->with('error', 'Anda harus memilih minimal satu penerima disposisi.');
        }

        DB::beginTransaction();
        try {
            // Generate token untuk magic link
            $disposition = $document->dispositions()->create([
                'from_user_id' => auth()->id(),
                'on_behalf_of' => $request->on_behalf_of,
                'instructions' => $request->instructions,
                'status' => 'Terkirim',
                'response_token' => Str::random(32),
                'token_expires_at' => now()->addHours(24),
            ]);

            // ... (kode untuk mengambil $uniqueRecipientIds tetap sama)
            $recipientIds = [];
            if ($request->filled('roles')) {
                $usersInRoles = User::whereIn('role_id', $request->roles)->pluck('id');
                $recipientIds = array_merge($recipientIds, $usersInRoles->all());
            }
            if ($request->filled('users')) {
                $recipientIds = array_merge($recipientIds, $request->users);
            }
            $uniqueRecipientIds = array_unique($recipientIds);
            $disposition->recipients()->attach($uniqueRecipientIds);

            // =======================================================
            // ## LOGIKA NOTIFIKASI DENGAN ERROR HANDLING & LOGGING ##
            $recipients = User::find($uniqueRecipientIds);
            $whatsapp = new WhatsAppService();
            $notification = new \App\Notifications\NewDispositionReceived($disposition);
            $failedRecipients = [];

            Log::info("--- Memulai Pengiriman Disposisi #{$disposition->id} ---");

            foreach ($recipients as $recipient) {
                // 1. Kirim notifikasi database (untuk ikon lonceng)
                $recipient->notify($notification);

                if (!$recipient->phone_number) {
                    Log::warning("[Disposisi #{$disposition->id}] PENGGUNA DILEWATI: User '{$recipient->name}' tidak memiliki nomor HP.");
                    $failedRecipients[] = $recipient->name . " (tidak ada nomor HP)";
                    continue; // Lanjut ke penerima berikutnya
                }

                try {
                    // Buat magic link unik untuk setiap penerima
                    $magicLink = URL::temporarySignedRoute(
                        'dispositions.respond.magic',
                        now()->addHours(24),
                        ['token' => $disposition->response_token, 'user' => $recipient->id]
                    );
                    
                    Log::info("[Disposisi #{$disposition->id}] MENCOBA KIRIM ke: {$recipient->name} ({$recipient->phone_number})");
                    
                    // Kita asumsikan WhatsAppService akan mengembalikan true/false
                    $isSent = $whatsapp->sendNewDispositionNotification($disposition, $recipient, $magicLink);

                    if ($isSent) {
                        Log::info("[Disposisi #{$disposition->id}] BERHASIL kirim ke: {$recipient->name}");
                    } else {
                         Log::error("[Disposisi #{$disposition->id}] GAGAL kirim ke: {$recipient->name} (Method sendNewDispositionNotification mengembalikan false)");
                         $failedRecipients[] = $recipient->name;
                    }

                } catch (\Exception $e) {
                    Log::error("[Disposisi #{$disposition->id}] FATAL ERROR saat kirim ke {$recipient->name}: " . $e->getMessage());
                    $failedRecipients[] = $recipient->name;
                }

                // Jeda acak antara 5 sampai 15 detik
                // sleep(rand(5, 15));

            }
            Log::info("--- Pengiriman Disposisi #{$disposition->id} Selesai ---");
            // =======================================================

            // ## LOGIKA NOTIFIKASI BARU: KIRIM PEKERJAAN KE ANTRIAN ##
            $recipients = User::find($uniqueRecipientIds);
            \App\Jobs\SendDispositionNotifications::dispatch($disposition, $recipients);
            DB::commit();

            // Pesan ini akan langsung muncul tanpa menunggu WhatsApp terkirim
            return back()->with('success', 'Disposisi berhasil dibuat dan notifikasi sedang dalam proses pengiriman ke ' . count($uniqueRecipientIds) . ' penerima.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("FATAL ERROR di Controller saat membuat disposisi: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan fatal. Silakan cek log sistem.');
        }
    }

    /**
     * Menyimpan tanggapan dari pengguna yang sudah login.
     */
    public function storeResponse(Request $request, Disposition $disposition)
    {
        $this->authorize('createResponse', $disposition);

        $request->validate([
            'notes' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,zip,rar|max:10240'
        ]);

        $response = DispositionResponse::create([
            'disposition_id' => $disposition->id,
            'user_id' => Auth::id(),
            'notes' => $request->notes,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('disposition_attachments', $fileName, 'public');
                DispositionResponseAttachment::create([
                    'disposition_response_id' => $response->id,
                    'file_path' => $filePath,
                    'original_filename' => $file->getClientOriginalName(),
                ]);
            }
        }

        if ($disposition->status !== 'Selesai') {
            $disposition->update(['status' => 'Dibalas']);
        }
        
        try {
            $whatsapp = new WhatsAppService();
            $whatsapp->sendResponseNotification($response);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi balasan disposisi: ' . $e->getMessage());
        }

        return redirect()->route('dispositions.show', $disposition->id)->with('success', 'Tanggapan berhasil dikirim.');
    }

    /**
     * Menandai disposisi sebagai selesai.
     */
    public function markAsCompleted(Request $request, Disposition $disposition)
    {
        // PERBAIKAN: Menggunakan Policy untuk otorisasi
        $this->authorize('markAsCompleted', $disposition);
    
        $disposition->update([
            'status' => 'Selesai',
            'closing_note' => $request->input('closing_note')
        ]);
    
        return redirect()->route('dispositions.show', $disposition->id)->with('success', 'Disposisi telah ditandai selesai.');
    }
    
/**
     * Menampilkan halaman respons publik via Magic Link.
     */
    public function showViaMagicLink(Request $request, $token)
    {
        // Validasi tanda tangan link (keamanan)
        if (! $request->hasValidSignature()) {
            abort(403, 'Link tidak valid atau kedaluwarsa.');
        }

        $disposition = \App\Models\Disposition::where('response_token', $token)->firstOrFail();
        
        // PERBAIKAN: Ambil ID user dari parameter URL, lalu cari datanya
        $user = \App\Models\User::findOrFail($request->query('user'));

        // Cek apakah token sudah digunakan atau user tidak sesuai
        if ($disposition->token_used_at || !$disposition->recipients->contains($user)) {
            abort(403, 'Link ini sudah tidak berlaku atau bukan untuk Anda.');
        }

        $previewUrl = null;
        if ($disposition->document && $disposition->document->stored_path) {
            $previewUrl = URL::temporarySignedRoute(
                'documents.public-stream',
                now()->addMinutes(15), // URL ini hanya berlaku 15 menit
                ['document' => $disposition->document->id]
            );
        }
        // --- AKHIR TAMBAHAN ---

        // Kirim variabel baru $previewUrl ke view
        return view('pages.dispositions.respond-publicly', compact('disposition', 'user', 'previewUrl'));
    }

   /**
     * Menyimpan respons yang dikirim dari halaman publik.
     */
    public function storePublicResponse(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'response_token' => 'required|string|exists:dispositions,response_token',
            'user_id' => 'required|integer|exists:users,id',
            'notes' => 'required|string|min:5',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,zip,rar|max:10240'
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, kembalikan ke form dengan pesan error
            return back()->withErrors($validator)->withInput()->with('error_message', 'Tanggapan gagal dikirim. Pastikan semua kolom terisi dengan benar.');
        }

        DB::beginTransaction();
        try {
            $disposition = Disposition::where('response_token', $request->response_token)->firstOrFail();

            // 2. Cek Keabsahan Token dan Izin
            if ($disposition->token_used_at || ($disposition->token_expires_at && $disposition->token_expires_at->isPast())) {
                return back()->with('error_message', 'Link ini sudah tidak berlaku atau telah digunakan.');
            }

            if (!$disposition->recipients->contains($request->user_id)) {
                return back()->with('error_message', 'Anda tidak memiliki izin untuk merespons disposisi ini.');
            }

            // 3. Buat Entri Respons
            $response = DispositionResponse::create([
                'disposition_id' => $disposition->id,
                'user_id' => $request->user_id,
                'notes' => $request->notes,
            ]);

            // 4. Proses Lampiran
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filePath = $file->store('disposition_attachments', 'public');
                    DispositionResponseAttachment::create([
                        'disposition_response_id' => $response->id,
                        'file_path' => $filePath,
                        'original_filename' => $file->getClientOriginalName(),
                    ]);
                }
            }

            // 5. Update Status Disposisi
            $disposition->update(['status' => 'Dibalas', 'token_used_at' => now()]);

            // 6. Kirim Notifikasi WhatsApp
            try {
                $whatsapp = new WhatsAppService();
                $whatsapp->sendResponseNotification($response);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi WA balasan disposisi: ' . $e->getMessage());
            }
            
            DB::commit();

            // 7. Pengalihan Cerdas Setelah Sukses
            $successMessage = 'Tanggapan Anda telah berhasil dikirim. Terima kasih.';

            if (Auth::check()) {
                // Jika ada pengguna yang login, arahkan ke halaman detail disposisi
                return redirect()->route('dispositions.show', $disposition->id)->with('success', $successMessage);
            } else {
                // Jika tidak ada yang login (dari link publik), arahkan ke halaman login dengan pesan sukses
                return redirect()->route('login')->with('status', $successMessage);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan tanggapan publik: ' . $e->getMessage());
            // Jika gagal, kembalikan dengan pesan error spesifik
            return back()->withInput()->with('error_message', 'Maaf, terjadi kesalahan pada server. Tanggapan Anda tidak tersimpan. Silakan coba lagi.');
        }
    }
}