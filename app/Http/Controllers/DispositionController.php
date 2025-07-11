<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DispositionResponse;
use App\Models\DispositionResponseAttachment;

class DispositionController extends Controller
{
    public function index()
    {
        $dispositions = Disposition::where('to_user_id', Auth::id())
                                ->with(['document', 'fromUser'])
                                ->latest()
                                ->paginate(15);

        return view('pages.dispositions.index', compact('dispositions'));
    }

    public function store(Request $request, Document $document)
    {
        // Otorisasi: Hanya admin yang bisa melakukan disposisi
        if (Auth::id() !== $disposition->from_user_id && Auth::id() !== $disposition->to_user_id) {
            abort(403);
        }

        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'instructions' => 'required|string',
        ]);

        Disposition::create([
            'document_id' => $document->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'instructions' => $request->instructions,
        ]);

        // TO-DO: Tambahkan logika untuk mengirim notifikasi WhatsApp di sini

        return redirect()->route('dispositions.sent')->with('success', 'Disposisi berhasil dikirim.');
    }

    public function markAsCompleted(Request $request, Disposition $disposition)
    {
        if (Auth::id() !== $disposition->from_user_id && Auth::id() !== $disposition->to_user_id) {
            abort(403);
        }

        $disposition->update([
            'status' => 'Selesai',
            'closing_note' => $request->input('closing_note') // Simpan catatan penutupan
        ]);

        return redirect()->route('dispositions.index')->with('success', 'Disposisi telah ditandai selesai.');
    }

    public function show(Disposition $disposition)
    {
        // Otorisasi: pastikan hanya pengirim atau penerima yang bisa melihat
        $this->authorize('view', $disposition);

        // Tandai disposisi sebagai 'Dibaca' saat pertama kali dibuka oleh penerima
        if ($disposition->status == 'Terkirim' && $disposition->to_user_id == Auth::id()) {
            $disposition->update(['status' => 'Dibaca']);
        }

        // Ambil semua data terkait
        $disposition->load(['document.uploader', 'fromUser', 'toUser', 'responses.user', 'responses.attachments']);

        return view('pages.dispositions.show', compact('disposition'));
    }

    public function storeResponse(Request $request, Disposition $disposition)
    {
        $this->authorize('createResponse', $disposition);

        $request->validate([
            'notes' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,zip,rar|max:10240'
        ]);

            // 1. Simpan tanggapan
            $response = DispositionResponse::create([
                'disposition_id' => $disposition->id,
                'user_id' => Auth::id(),
                'notes' => $request->notes,
            ]);

            // 2. Simpan lampiran jika ada
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

        // Saat ditanggapi, ubah status menjadi 'Dibalas'
        if ($disposition->status !== 'Selesai') {
            $disposition->update(['status' => 'Dibalas']);
        }

            return redirect()->route('dispositions.show', $disposition->id)->with('success', 'Tanggapan berhasil dikirim.');
        }

        public function sent()
        {
            $dispositions = Disposition::where('from_user_id', Auth::id())
                                    ->with(['document', 'toUser'])
                                    ->latest()
                                    ->paginate(15);

            return view('pages.dispositions.sent', compact('dispositions'));
        }
}