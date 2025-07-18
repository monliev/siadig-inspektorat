<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // <-- Tambahkan ini
use Illuminate\Support\Str;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentRequest;
use App\Services\WhatsAppService; // <-- Tambahkan ini

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan user adalah klien dan terhubung ke sebuah entitas
        if (!$user->entity_id) {
            // Jika tidak, tampilkan halaman kosong atau pesan error
            return view('client.dashboard', ['requests' => collect()]);
        }

        // Ambil semua permintaan yang ditujukan ke entitas pengguna ini
        $requests = DocumentRequest::whereHas('entities', function ($query) use ($user) {
            $query->where('entity_id', $user->entity_id);
        })->with('documents')->latest()->paginate(10);

        return view('client.dashboard', compact('requests'));
    }

    public function createDocumentForRequest(DocumentRequest $documentRequest)
    {
        $categories = DocumentCategory::where('scope', 'external')->orderBy('name')->get();

        // Ambil dokumen yang sudah diunggah oleh user ini untuk permintaan ini
        $submittedDocuments = Document::where('document_request_id', $documentRequest->id)
                                    ->where('uploaded_by', Auth::id())
                                    ->latest()
                                    ->get();

        return view('client.create', compact('categories', 'documentRequest', 'submittedDocuments'));
    }

    /**
     * Menyimpan dokumen yang diunggah untuk sebuah permintaan spesifik.
     */
    public function storeDocumentForRequest(Request $request, DocumentRequest $documentRequest)
    {
        $request->validate([
            'category_id' => 'required|exists:document_categories,id',
            'title' => 'required|string|max:255',
            'document_date' => 'required|date',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,zip,rar|max:10240',
        ]);

        $file = $request->file('file');
        // --- PERBAIKAN NAMA FILE DI SINI ---
        $originalName = $file->getClientOriginalName();
        $sanitizedName = preg_replace('/[^A-Za-z0-9\._-]/', '', str_replace(' ', '_', $originalName));
        $fileName = time() . '_' . $sanitizedName;

        $filePath = $file->storeAs('documents', $fileName, 'public');

        $document = Document::create([
            'document_request_id' => $documentRequest->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'document_date' => $request->document_date,
            'description' => $request->description,
            'original_filename' => $originalName, // Simpan nama asli
            'stored_path' => $filePath, // Simpan path yang bersih
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
            'status' => 'Menunggu Review',
        ]);

        try {
            $whatsapp = new WhatsAppService();
            $whatsapp->sendClientSubmissionNotification($document);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi WA unggahan klien: ' . $e->getMessage());
        }

        return redirect()->route('client.dashboard')->with('success', 'Dokumen berhasil diunggah.');
    }
}