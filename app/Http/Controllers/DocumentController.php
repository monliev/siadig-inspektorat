<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Entity;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // HAPUS FUNGSI __construct() YANG LAMA

    /**
     * Menampilkan daftar semua dokumen.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Document::class);

        $categories = DocumentCategory::where('scope', 'internal')->orderBy('name')->get();
        
        // Definisikan $search di awal
        $search = $request->input('search');

        $query = Document::with('category', 'uploader')
                        ->whereNull('document_request_id')
                        ->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('document_number', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->filled('date_from')) {
            $query->where('document_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('document_date', '<=', $request->input('date_to'));
        }

        $documents = $query->paginate(10);
        return view('pages.documents.index', compact('documents', 'categories', 'search'));
    }

    /**
     * Menampilkan form untuk membuat dokumen baru.
     */
    public function create()
    {
        $this->authorize('create', Document::class);
        $categories = DocumentCategory::where('scope', 'internal')->orderBy('name')->get();
        
        // Ambil data entitas untuk dropdown
        $entities = Entity::orderBy('agency_code')->get();
        
        return view('pages.documents.create', compact('categories', 'entities'));
    }

    /**
     * Menyimpan dokumen yang baru dibuat.
     */
    public function store(Request $request)
    {

        $this->authorize('create', Document::class);

        $request->validate([
            'category_id' => 'required|exists:document_categories,id',
            'title' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'document_date' => 'required|date',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,zip,rar|max:51200',
            'physical_location_building' => 'nullable|string|max:255',
            'physical_location_cabinet' => 'nullable|string|max:255',
            'physical_location_rack' => 'nullable|string|max:255',
            'physical_location_box' => 'nullable|string|max:255',
            'from_entity_id' => 'required_if:correspondence_type,incoming', // Wajib jika surat masuk
            'external_sender_name' => 'required_if:from_entity_id,is_external', // Wajib jika pengirim eksternal
            'to_entity_id' => 'required_if:correspondence_type,outgoing', // Wajib jika surat keluar
        ]);

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            // Ganti spasi dengan underscore dan bersihkan karakter lain
            $sanitizedName = preg_replace('/[^A-Za-z0-9\._-]/', '', str_replace(' ', '_', $originalName));
            $fileName = time() . '_' . $sanitizedName;
            
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $user = Auth::user();
            $status = 'Menunggu Review';
            $approved_by = null;

            // PERBAIKAN LOGIKA: Cek apakah pengguna memiliki role 'isAdmin'
            if ($user->can('isAdmin')) {
                $status = 'Diarsip';
                $approved_by = $user->id;
            }

        Document::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'document_number' => $request->document_number,
            'document_date' => $request->document_date,
            'description' => $request->description,
            'original_filename' => $originalName, // Simpan nama asli untuk ditampilkan
            'stored_path' => $filePath, // Simpan path dengan nama yang sudah bersih
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
            'status' => $status,
            'approved_by' => $approved_by,
            'classification' => 'Biasa',
            'physical_location_building' => $request->physical_location_building,
            'physical_location_cabinet' => $request->physical_location_cabinet,
            'physical_location_rack' => $request->physical_location_rack,
            'physical_location_box' => $request->physical_location_box,
            // Simpan data korespondensi baru
            'from_entity_id' => $request->from_entity_id === 'is_external' ? null : $request->from_entity_id,
            'to_entity_id' => $request->to_entity_id,
            'external_sender_name' => $request->from_entity_id === 'is_external' ? $request->external_sender_name : null,
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Menampilkan detail satu dokumen spesifik
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);

        AuditTrail::create([
            'user_id' => Auth::id(),
            'document_id' => $document->id,
            'action' => 'VIEW',
            'description' => 'Melihat detail dokumen: ' . $document->title,
        ]);

        $activities = AuditTrail::where('document_id', $document->id)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Ambil daftar pengguna internal untuk tujuan disposisi
        $internalUsers = User::whereHas('role', function($q){
            $q->where('name', '!=', 'Klien Eksternal');
        })->orderBy('name')->get();

        // Tambahkan $internalUsers ke data yang dikirim ke view
        return view('pages.documents.show', compact('document', 'activities', 'internalUsers')); 
    }

    public function showClientSubmission(Document $document)
    {
        // Otorisasi untuk melihat dokumen
        $this->authorize('view', $document);

        // Catat aktivitas bahwa dokumen ini sedang direview
        AuditTrail::create([
            'user_id' => Auth::id(),
            'document_id' => $document->id,
            'action' => 'REVIEW_VIEW',
            'description' => 'Membuka detail review untuk dokumen: ' . $document->title,
        ]);

        // Ambil riwayat aktivitas untuk dokumen ini
        $activities = AuditTrail::where('document_id', $document->id)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Ambil daftar pengguna internal untuk tujuan disposisi
        $internalUsers = User::whereHas('role', function($q){
            $q->where('name', '!=', 'Klien Eksternal');
        })->orderBy('name')->get();

        // Tambahkan $internalUsers ke data yang dikirim ke view
        return view('pages.documents.show-client-submission', compact('document', 'activities', 'internalUsers')); 
    }

    /**
     * Menampilkan form untuk mengedit dokumen.
     */
    public function edit(Document $document)
    {
        $this->authorize('update', $document);
        $categories = DocumentCategory::all();
        return view('pages.documents.edit', compact('document', 'categories'));
    }

    /**
     * Menyimpan perubahan pada dokumen.
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'title' => 'required|string|max:255',
            'physical_location_building' => 'nullable|string|max:255',
            'physical_location_cabinet' => 'nullable|string|max:255',
            'physical_location_rack' => 'nullable|string|max:255',
            'physical_location_box' => 'nullable|string|max:255',
        ]);

        $document->update($request->all());

        return redirect()->route('documents.show', $document->id)->with('success', 'Lokasi fisik dan detail dokumen berhasil diperbarui.');
    }

    /**
     * Menghapus dokumen dari storage dan database.
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        if (Storage::disk('public')->exists($document->stored_path)) {
            Storage::disk('public')->delete($document->stored_path);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Mengunduh file dokumen.
     */
    public function download(Document $document)
    {
        $this->authorize('view', $document);

        if (Storage::disk('public')->exists($document->stored_path)) {
            AuditTrail::create([
                'user_id' => Auth::id(),
                'document_id' => $document->id,
                'action' => 'DOWNLOAD',
                'description' => 'Mengunduh file: ' . $document->original_filename,
            ]);

            return Storage::disk('public')->download($document->stored_path, $document->original_filename);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * Menampilkan daftar dokumen yang menunggu review.
     */
    public function reviewList()
    {
        $this->authorize('reviewAny', Document::class);
        $reviewDocuments = Document::with('category', 'uploader')
                                    ->where('status', 'Menunggu Review')
                                    ->whereNull('document_request_id') // <-- HANYA AMBIL DOKUMEN INTERNAL
                                    ->latest()
                                    ->get();
        
        return view('pages.documents.review', compact('reviewDocuments'));
    }

    /**
     * Menyetujui dokumen.
     */
    public function approve(Document $document)
    {
        $this->authorize('reviewAny', Document::class);
        $document->update([
            'status' => 'Diarsip',
            'approved_by' => Auth::id(),
        ]);
        return redirect()->route('documents.reviewList')->with('success', 'Dokumen telah disetujui dan diarsipkan.');
    }

    /**
     * Menolak dokumen.
     */
    public function reject(Document $document)
    {
        $this->authorize('reviewAny', Document::class);
        $document->update(['status' => 'Ditolak']);
        return redirect()->route('documents.reviewList')->with('success', 'Dokumen telah ditolak.');
    }

    public function clientSubmissions(Request $request)
    {
        $this->authorize('isAdmin');

        $categories = DocumentCategory::where('scope', 'external')->orderBy('name')->get();
        
        // Definisikan $search di awal
        $search = $request->input('search');

        $query = Document::with(['category', 'uploader.entity', 'documentRequest'])
                        ->whereNotNull('document_request_id')
                        ->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                ->orWhereHas('uploader.entity', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%');
                });
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        $documents = $query->paginate(15);
        return view('pages.documents.client-submissions', compact('documents', 'categories', 'search'));
    }
}