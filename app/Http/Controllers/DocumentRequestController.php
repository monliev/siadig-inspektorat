<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRequest;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentRequestController extends Controller
{
    public function index()
    {
        // withCount('entities') akan menghitung jumlah entitas terkait
        $requests = DocumentRequest::with('creator')
                                ->withCount('entities')
                                ->latest()
                                ->paginate(10);
                                
        return view('pages.document-requests.index', compact('requests'));
    }

    public function showEntityUploads(DocumentRequest $documentRequest, Entity $entity)
    {
        // Ambil semua dokumen yang cocok dengan permintaan DAN diunggah oleh
        // pengguna dari entitas yang dipilih.
        $documents = Document::where('document_request_id', $documentRequest->id)
                             ->whereHas('uploader', function ($query) use ($entity) {
                                 $query->where('entity_id', $entity->id);
                             })
                             ->with('category', 'uploader')
                             ->latest()
                             ->get();

        return view('pages.document-requests.show-entity-uploads', compact('documentRequest', 'entity', 'documents'));
    }

    public function create()
    {
        // Ambil semua entitas untuk dropdown utama
        $entities = Entity::with('parent')->orderBy('name')->get();

        // Siapkan data untuk grup pilihan cepat
        $opdInduk = Entity::where('type', 'OPD')->whereNull('parent_id')->get();
        $semuaPuskesmas = Entity::where('name', 'like', 'Puskesmas%')->get();
        $semuaKecamatan = Entity::where('type', 'Kecamatan')->get();
        $semuaDesa = Entity::where('type', 'Desa')->get();

        // Data untuk pilihan desa per kecamatan
        $desaPerKecamatan = [];
        foreach ($semuaKecamatan as $kecamatan) {
            $desaPerKecamatan[$kecamatan->name] = Entity::where('type', 'Desa')
                                                        ->where('parent_id', $kecamatan->id)
                                                        ->pluck('id');
        }

        return view('pages.document-requests.create', compact(
            'entities', 
            'opdInduk', 
            'semuaPuskesmas',
            'semuaKecamatan', 
            'semuaDesa',
            'desaPerKecamatan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'entity_ids' => 'required|array', // Pastikan minimal satu entitas dipilih
            'entity_ids.*' => 'exists:entities,id',
        ]);

        $docRequest = DocumentRequest::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'created_by' => Auth::id(),
        ]);

        // Tautkan permintaan ini ke entitas yang dipilih
        $docRequest->entities()->attach($request->entity_ids);

        return redirect()->route('document-requests.index')->with('success', 'Permintaan dokumen baru berhasil dibuat.');
    }
    
    public function show(DocumentRequest $documentRequest)
    {
        $documentRequest->load('entities', 'documents.uploader');

        $submissionStatuses = [];
        foreach ($documentRequest->entities as $entity) {
            $entityDocuments = $documentRequest->documents->filter(function ($doc) use ($entity) {
                return $doc->uploader && $doc->uploader->entity_id == $entity->id;
            });

            if ($entityDocuments->isEmpty()) {
                $submissionStatuses[$entity->id] = 'Belum Mengunggah';
            } elseif ($entityDocuments->contains('status', 'Diarsip')) {
                $submissionStatuses[$entity->id] = 'Diterima';
            } elseif ($entityDocuments->contains('status', 'Menunggu Review')) {
                $submissionStatuses[$entity->id] = 'Perlu Direview';
            } else {
                $submissionStatuses[$entity->id] = 'Revisi Diperlukan';
            }
        }

        return view('pages.document-requests.show', compact('documentRequest', 'submissionStatuses'));
    }
}