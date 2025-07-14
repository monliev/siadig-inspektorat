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
        $disposition->load(['document.uploader', 'fromUser', 'recipients', 'responses.user', 'responses.attachments']);
        
        return view('pages.dispositions.show', compact('disposition'));
    }

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
            return back()->with('error', 'Anda harus memilih minimal satu penerima disposisi.');
        }

        DB::beginTransaction();
        try {
            // Generate token untuk magic link sekali saja
            $disposition = $document->dispositions()->create([
                'from_user_id' => auth()->id(),
                'instructions' => $request->instructions,
                'status' => 'Terkirim',
                'response_token' => Str::random(32),
                'token_expires_at' => now()->addHours(24),
            ]);

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
            // ## LOGIKA NOTIFIKASI WHATSAPP YANG BENAR ##
            $recipients = User::find($uniqueRecipientIds);
            $whatsapp = new WhatsAppService();

            foreach ($recipients as $recipient) {
                // Lewati jika penerima tidak punya nomor HP
                if (!$recipient->phone_number) {
                    continue;
                }

                try {
                    // Buat magic link unik untuk setiap penerima
                    $magicLink = URL::temporarySignedRoute(
                        'dispositions.respond.magic',
                        now()->addHours(24),
                        ['token' => $disposition->response_token, 'user' => $recipient->id]
                    );

                    // Panggil method yang sudah ada di WhatsAppService Anda
                    $whatsapp->sendNewDispositionNotification($disposition, $magicLink);
                    
                } catch (\Exception $e) {
                    Log::error("Gagal kirim WA disposisi ke {$recipient->name}: " . $e->getMessage());
                }
            }
            // =======================================================

            DB::commit();

            return back()->with('success', 'Disposisi berhasil dikirimkan ke ' . count($uniqueRecipientIds) . ' penerima.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
        if (! $request->hasValidSignature()) { abort(403, 'Link tidak valid atau kedaluwarsa.'); }
        $disposition = Disposition::where('response_token', $token)->firstOrFail();
        if ($disposition->token_used_at) { abort(403, 'Link ini sudah pernah digunakan.'); }
        if ($disposition->token_expires_at && $disposition->token_expires_at->isPast()) { abort(403, 'Link ini sudah kedaluwarsa.'); }

        return view('pages.dispositions.respond-publicly', ['disposition' => $disposition, 'token' => $token]);
    }

    /**
     * Menyimpan respons yang dikirim dari halaman publik.
     */
    public function storePublicResponse(Request $request)
    {
        // PERBAIKAN: Menghapus referensi to_user_id
        $request->validate([
            'response_token' => 'required|string|exists:dispositions,response_token',
            'user_id' => 'required|integer|exists:users,id', // Validasi user ID
            'notes' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,zip,rar|max:10240'
        ]);
    
        $disposition = Disposition::where('response_token', $request->response_token)->firstOrFail();
    
        if ($disposition->token_used_at || ($disposition->token_expires_at && $disposition->token_expires_at->isPast())) {
            abort(403, 'Link ini sudah tidak berlaku.');
        }
    
        if (!$disposition->recipients->contains($request->user_id)) {
            abort(403, 'Anda tidak memiliki izin untuk merespons disposisi ini.');
        }
    
        $response = DispositionResponse::create([
            'disposition_id' => $disposition->id,
            'user_id' => $request->user_id, // Menggunakan user_id dari request
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
        
        $disposition->update(['status' => 'Dibalas', 'token_used_at' => now()]);

        try {
            $whatsapp = new WhatsAppService();
            $whatsapp->sendResponseNotification($response);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi balasan disposisi: ' . $e->getMessage());
        }

        return "Tanggapan Anda telah berhasil dikirim. Terima kasih.";
    }
}