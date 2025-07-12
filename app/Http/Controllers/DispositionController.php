<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\DispositionResponse;
use App\Models\DispositionResponseAttachment;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Services\WhatsAppService;
use Illuminate\Support\Str;

class DispositionController extends Controller
{
    /**
     * Menampilkan daftar disposisi yang diterima pengguna.
     */
    public function index()
    {
        $dispositions = Disposition::where('to_user_id', Auth::id())
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
                                   ->with(['document', 'toUser'])
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
        if ($disposition->status == 'Terkirim' && $disposition->to_user_id == Auth::id()) {
            $disposition->update(['status' => 'Dibaca']);
        }
        $disposition->load(['document.uploader', 'fromUser', 'toUser', 'responses.user', 'responses.attachments']);
        return view('pages.dispositions.show', compact('disposition'));
    }

    /**
     * Menyimpan disposisi baru.
     */
    public function store(Request $request, Document $document)
    {
        if (!Auth::user()->can('can-disposition')) { abort(403); }

        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'instructions' => 'required|string',
        ]);

        $disposition = Disposition::create([
            'document_id' => $document->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'instructions' => $request->instructions,
            'response_token' => Str::random(60), // Menggunakan panjang 60
            'token_expires_at' => now()->addHours(8),
        ]);
        
        try {
            $magicLink = URL::temporarySignedRoute('dispositions.respond.magic', now()->addHours(8), ['token' => $disposition->response_token]);
            $whatsapp = new WhatsAppService();
            $whatsapp->sendNewDispositionNotification($disposition, $magicLink);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi WhatsApp: ' . $e->getMessage());
        }

        return redirect()->route('dispositions.sent')->with('success', 'Disposisi berhasil dikirim.');
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
        if (Auth::id() !== $disposition->from_user_id && Auth::id() !== $disposition->to_user_id) {
            abort(403);
        }

        $disposition->update([
            'status' => 'Selesai',
            'closing_note' => $request->input('closing_note')
        ]);

        // Kembali ke halaman detail disposisi yang baru saja diselesaikan
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
        $request->validate([
            'response_token' => 'required|string|exists:dispositions,response_token',
            'notes' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,zip,rar|max:10240'
        ]);

        $disposition = Disposition::where('response_token', $request->response_token)->firstOrFail();
        if ($disposition->token_used_at || ($disposition->token_expires_at && $disposition->token_expires_at->isPast())) {
            abort(403, 'Link ini sudah tidak berlaku.');
        }

        $response = DispositionResponse::create([
            'disposition_id' => $disposition->id,
            'user_id' => $disposition->to_user_id,
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