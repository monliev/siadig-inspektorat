<?php

namespace App\Http\Controllers;

use App\Models\RequiredDocument;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\WhatsAppService; // Pastikan ini ada
use Illuminate\Support\Facades\Log; // Pastikan ini ada

class ServiceRequestController extends Controller
{
    public function index()
    {
        // Ambil semua permohonan, urutkan dari yang terbaru, dan sertakan data pemohonnya
        $serviceRequests = ServiceRequest::with('applicant')->latest()->paginate(15);

        return view('pages.service-requests.index', compact('serviceRequests'));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();
    
        // Eager load semua relasi yang dibutuhkan
        $serviceRequest->load(['applicant', 'uploadedDocuments.requirement', 'revisions.auditor']);
    
        // Jika yang mengakses adalah PEMOHON
        if ($user->hasRole('Pemohon')) {
            // Pastikan pemohon hanya bisa melihat permohonan miliknya sendiri
            if ($serviceRequest->user_id !== $user->id) {
                abort(403, 'AKSI TIDAK DIIZINKAN');
            }
            return view('pages.pemohon.show', compact('serviceRequest'));
        }

        // TAMBAHKAN BLOK KODE INI
        if (request()->has('read')) {
            $notification = auth()->user()->notifications()->where('id', request('read'))->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }
        // AKHIR BLOK KODE TAMBAHAN
    
        // Jika yang mengakses adalah ADMIN/AUDITOR (selain pemohon)
        return view('pages.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Menampilkan form untuk membuat permohonan baru.
     */
    public function create()
    {
        // Ambil semua dokumen yang disyaratkan untuk layanan 'bebas_temuan'
        $requiredDocuments = RequiredDocument::where('service_type', 'bebas_temuan')
                                             ->where('is_active', true)
                                             ->get();

        return view('pages.service-requests.create', compact('requiredDocuments'));
    }

    /**
     * Menyimpan permohonan baru beserta dokumen yang diunggah.
     */
    public function store(Request $request)
    {
        // 1. Validasi input (pastikan semua file ada)
        $requiredDocs = RequiredDocument::where('service_type', 'bebas_temuan')->where('is_active', true)->get();
        $validationRules = [];
        foreach ($requiredDocs as $doc) {
            $validationRules['doc_'.$doc->id] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }
        $request->validate($validationRules);

        // 2. Gunakan DB Transaction untuk keamanan data
        DB::beginTransaction();
        try {
            // 3. Buat entri permohonan baru
            $serviceRequest = ServiceRequest::create([
                'user_id' => Auth::id(),
                'service_type' => 'bebas_temuan',
                'status' => 'BARU',
            ]);

            // 4. Proses setiap file yang diunggah
            foreach ($requiredDocs as $doc) {
                $file = $request->file('doc_'.$doc->id);
                // Simpan file ke storage/app/public/bebas_temuan_docs
                $path = $file->store('bebas_temuan_docs', 'public');

                // Buat entri di tabel uploaded_documents
                $serviceRequest->uploadedDocuments()->create([
                    'required_document_id' => $doc->id,
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                ]);
            }

            // =======================================================
            // ## KIRIM NOTIFIKASI SETELAH SEMUA BERHASIL ##
            // 1. Ambil semua user dengan peran yang relevan
            $admins = User::role(['Super Admin', 'Admin Arsip'])->get();

            // 2. Kirim notifikasi kepada mereka
            Notification::send($admins, new NewServiceRequest($serviceRequest));
            // =======================================================

            try {
                // 1. Ambil semua user dengan peran yang relevan
                $admins = User::role(['Super Admin', 'Admin Arsip'])->whereNotNull('phone_number')->get();
                $applicantName = $serviceRequest->applicant->name;
                $message = "Notifikasi SIADIG: Permohonan Surat Bebas Temuan baru #{$serviceRequest->id} dari *{$applicantName}* telah diajukan dan menunggu verifikasi.";
            
                // 2. Kirim notifikasi ke setiap admin menggunakan method baru
                $whatsapp = new WhatsAppService();
                foreach ($admins as $admin) {
                    $whatsapp->sendSimpleText($admin->phone_number, $message);
                }
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi WA permohonan baru: ' . $e->getMessage());
            }

            // Jika semua berhasil, commit transaksi
            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Permohonan Anda berhasil diajukan.');

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua proses
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mengajukan permohonan.');
        }
    }

    /**
     * Menyimpan catatan revisi baru dan mengubah status permohonan.
     */
    public function addRevision(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'notes' => 'required|string|min:10',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan catatan revisi
            $serviceRequest->revisions()->create([
                'auditor_user_id' => Auth::id(),
                'notes' => $request->notes,
            ]);

            // 2. Update status permohonan utama
            $serviceRequest->status = 'BUTUH REVISI';
            $serviceRequest->save();

            // =======================================================
            // ## KIRIM NOTIFIKASI WHATSAPP KE PEMOHON ##
            try {
                $applicant = $serviceRequest->applicant;
                if ($applicant && $applicant->phone_number) {
                    $message = "Pemberitahuan SIADIG:\n\nPermohonan Anda (#{$serviceRequest->id}) memerlukan revisi. Silakan login ke aplikasi untuk melihat catatan dari auditor dan mengunggah dokumen perbaikan.";

                    $whatsapp = new WhatsAppService();
                    $whatsapp->sendSimpleText($applicant->phone_number, $message);
                }
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi WA revisi ke pemohon: ' . $e->getMessage());
            }
            // =======================================================

            DB::commit();
            return redirect()->route('service-requests.show', $serviceRequest->id)
                            ->with('success', 'Catatan revisi berhasil dikirimkan ke pemohon.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim catatan revisi.');
        }
    }

    public function showSkbtLandingPage()
    {
        return view('pages.service-requests.landing');
    }

    /**
     * Memproses dokumen perbaikan yang diunggah oleh pemohon.
     */
    public function submitRevision(Request $request, ServiceRequest $serviceRequest)
    {
        // 1. Otorisasi: Pastikan hanya pemilik permohonan yang bisa submit
        if ($serviceRequest->user_id !== auth()->id()) {
            abort(403);
        }

        // 2. Validasi file yang diunggah (minimal ada satu file)
        $request->validate([
            'revisi_docs' => 'required|array|min:1',
            'revisi_docs.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // 3. Loop melalui file perbaikan yang diunggah
            foreach ($request->file('revisi_docs') as $required_doc_id => $file) {
                // Cari dokumen lama yang berhubungan
                $oldDocument = $serviceRequest->uploadedDocuments()->where('required_document_id', $required_doc_id)->first();

                if ($oldDocument) {
                    // Hapus file lama dari storage
                    Storage::disk('public')->delete($oldDocument->file_path);

                    // Simpan file baru
                    $path = $file->store('bebas_temuan_docs', 'public');

                    // Update record di database dengan path file yang baru
                    $oldDocument->update([
                        'file_path' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                    ]);
                }
            }

            // 4. Update status permohonan menjadi 'DIREVISI' agar auditor tahu
            $serviceRequest->status = 'DIREVISI';
            $serviceRequest->save();

            // =======================================================
            // ## KIRIM NOTIFIKASI WHATSAPP KE AUDITOR ##
            try {
                // Cek apakah ada auditor yang ditugaskan
                $auditor = $serviceRequest->handler; 

                if ($auditor && $auditor->phone_number) {
                    $applicantName = $serviceRequest->applicant->name;
                    $message = "Pemberitahuan SIADIG:\n\n{$applicantName} telah mengirimkan perbaikan untuk permohonan #{$serviceRequest->id}. Mohon untuk direview kembali.";

                    $whatsapp = new WhatsAppService();
                    $whatsapp->sendSimpleText($auditor->phone_number, $message);
                }
                // Jika tidak ada handler spesifik, bisa juga dikirim ke semua admin
                // else { /* Logika kirim ke semua admin */ }

            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi WA perbaikan ke auditor: ' . $e->getMessage());
            }
            // =======================================================

            DB::commit();
            return redirect()->route('service-requests.show', $serviceRequest->id)
                            ->with('success', 'Dokumen perbaikan berhasil diunggah.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mengunggah dokumen perbaikan.');
        }
    }

    /**
     * Menyetujui permohonan dan mengunggah surat balasan.
     */
    public function approveRequest(Request $request, ServiceRequest $serviceRequest)
    {
        // 1. Validasi: pastikan ada file yang diunggah
        $request->validate([
            'final_document' => 'required|file|mimes:pdf|max:2048',
        ]);

        $file = $request->file('final_document');
        
        // 2. Simpan file final ke storage
        $path = $file->store('surat_bebas_temuan', 'public');

        // 3. Update status dan path file di database
        $serviceRequest->update([
            'status' => 'SELESAI',
            'final_document_path' => $path,
        ]);

        // =======================================================
        // ## KIRIM NOTIFIKASI WHATSAPP KE PEMOHON ##
        try {
            $applicant = $serviceRequest->applicant;
            if ($applicant && $applicant->phone_number) {
                $message = "Selamat! Permohonan Anda (#{$serviceRequest->id}) telah disetujui.\n\nSurat Keterangan Bebas Temuan sudah bisa diunduh melalui akun Anda di aplikasi SIADIG.";

                $whatsapp = new WhatsAppService();
                $whatsapp->sendSimpleText($applicant->phone_number, $message);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi WA persetujuan ke pemohon: ' . $e->getMessage());
        }
        // =======================================================
        
        return redirect()->route('service-requests.show', $serviceRequest->id)
                        ->with('success', 'Permohonan telah disetujui dan surat balasan berhasil diunggah.');
    }
}