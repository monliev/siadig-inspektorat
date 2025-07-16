<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

// --- Controllers ---
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\DispositionController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequiredDocumentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\UserController;

// --- Models ---
use App\Models\AuditTrail;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE PUBLIK (Bisa diakses tanpa login) ---
Route::get('/', [PortalController::class, 'index'])->name('portal');
Route::get('/bantuan', [HelpController::class, 'index'])->name('help.index');
Route::get('/layanan/skbt', [ServiceRequestController::class, 'showSkbtLandingPage'])->name('skbt.landing');
Route::get('/disposition-response/{token}', [DispositionController::class, 'showViaMagicLink'])->middleware('signed')->name('dispositions.respond.magic');
Route::post('/disposition-response/store', [DispositionController::class, 'storePublicResponse'])->name('dispositions.respond.store');
Route::get('/documents/public-stream/{document}', [App\Http\Controllers\DocumentController::class, 'publicStream'])->middleware('signed')->name('documents.public-stream');

// --- RUTE OTENTIKASI (Login, Register, dll) ---
require __DIR__.'/auth.php';


// --- GRUP UNTUK SEMUA PENGGUNA YANG SUDAH LOGIN ---
Route::middleware('auth')->group(function () {

    // Dashboard Utama (Gerbang setelah login)
    Route::get('/dashboard', function () {
        $user = auth()->user();
    
        // Logika pengalihan untuk peran non-internal
        if ($user->role && in_array($user->role->name, ['Klien Eksternal', 'Pemohon'])) {
            // ... (logika redirect Anda)
        }
    
        // --- LOGIKA UNTUK DASHBOARD INTERNAL ---
        $viewData = [
            'recentInternalDocuments' => \App\Models\Document::with('category')
                ->whereNull('document_request_id')
                ->latest()->take(5)->get(),
            'userActivities' => \App\Models\AuditTrail::where('user_id', $user->id)
                ->latest()->take(10)->get(),
        ];
    
        if (Gate::allows('view-admin-stats')) {
            $viewData['internalDocsCount'] = \App\Models\Document::whereNull('document_request_id')->count();
            $viewData['internalCatsCount'] = \App\Models\DocumentCategory::where('scope', 'internal')->count();
            $viewData['internalReviewCount'] = \App\Models\Document::whereNull('document_request_id')->where('status', 'Menunggu Review')->count();
            $viewData['totalRequestsCount'] = \App\Models\DocumentRequest::count();
            $viewData['externalDocsCount'] = \App\Models\Document::whereNotNull('document_request_id')->count();
            $viewData['externalReviewCount'] = \App\Models\Document::whereNotNull('document_request_id')->where('status', 'Menunggu Review')->count();
    
            if (Gate::allows('isAdmin')) {
                $viewData['allActivities'] = \App\Models\AuditTrail::with('user')->latest()->take(10)->get();
            }
        }
    
        return view('dashboard', $viewData);
    
    })->middleware(['auth', 'verified'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    //tautan preview dokumen
    Route::get('/documents/secure-stream/{document}', [DocumentController::class, 'stream'])
        ->name('documents.stream')
        ->middleware('signed');
    /*
    |--------------------------------------------------------------------------
    | RUMAH UNTUK PENGGUNA EKSTERNAL
    |--------------------------------------------------------------------------
    */
    // RUMAH UNTUK PEMOHON SKBT
    Route::middleware('can:isApplicant')->prefix('layanan')->name('service-requests.')->group(function() {
        Route::get('/bebas-temuan/create', [ServiceRequestController::class, 'create'])->name('create');
        Route::post('/bebas-temuan', [ServiceRequestController::class, 'store'])->name('store');
        Route::get('/permohonan/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('show');
        Route::post('/permohonan/{serviceRequest}/submit-revision', [ServiceRequestController::class, 'submitRevision'])->name('submitRevision');
    });
    
    // RUMAH UNTUK KLIEN EKSTERNAL (OPD / DESA)
    Route::middleware('can:isClient')->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/requests/{documentRequest}/upload', [ClientDashboardController::class, 'createDocumentForRequest'])->name('requests.documents.create');
        Route::post('/requests/{documentRequest}/upload', [ClientDashboardController::class, 'storeDocumentForRequest'])->name('requests.documents.store');
    });

    /*
    |--------------------------------------------------------------------------
    | RUMAH UNTUK PENGGUNA INTERNAL
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:isInternalUser')->group(function() {
        Route::resource('documents', DocumentController::class)->except(['destroy']);
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::get('/dispositions', [DispositionController::class, 'index'])->name('dispositions.index');
        Route::get('/dispositions/{disposition}', [DispositionController::class, 'show'])->name('dispositions.show');
        Route::patch('/dispositions/{disposition}/complete', [DispositionController::class, 'markAsCompleted'])->name('dispositions.complete');
        Route::post('/dispositions/{disposition}/responses', [DispositionController::class, 'storeResponse'])->name('dispositions.responses.store');
        Route::get('/dispositions-sent', [DispositionController::class, 'sent'])->name('dispositions.sent');
        Route::get('/documents/secure-stream/{document}', [DocumentController::class, 'stream'])->name('documents.stream')->middleware('signed');
        
        // RUMAH UNTUK YANG BISA MEMBUAT DISPOSISI
        Route::middleware('can:can-disposition')->group(function() {
            Route::post('/documents/{document}/dispositions', [DispositionController::class, 'store'])->name('dispositions.store')->middleware('can:can-disposition');
        });

        // RUMAH UNTUK YANG BISA MENGELOLA FITUR SKBT
        Route::middleware('can:manage-skbt')->group(function() {
            Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
            Route::post('/service-requests/{serviceRequest}/assign', [ServiceRequestController::class, 'assignHandler'])->name('service-requests.assign');
            Route::post('/service-requests/{serviceRequest}/add-revision', [ServiceRequestController::class, 'addRevision'])->name('service-requests.addRevision');
            Route::post('/service-requests/{serviceRequest}/approve', [ServiceRequestController::class, 'approveRequest'])->name('service-requests.approve');

        });

        // RUMAH UNTUK ADMIN ARSIP (otomatis bisa diakses Super Admin)
        Route::middleware('can:isAdmin')->group(function() {
            Route::resource('document-requests', DocumentRequestController::class);
            Route::resource('document-categories', DocumentCategoryController::class);
            Route::resource('required-documents', RequiredDocumentController::class);
            Route::get('/client-submissions', [DocumentController::class, 'clientSubmissions'])->name('documents.client_submissions');
            Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        });

        // RUMAH UNTUK SUPER ADMIN SAJA
        Route::middleware('can:isSuperAdmin')->group(function() {
            Route::get('/log-viewer', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('log-viewer');
            Route::resource('users', UserController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('entities', EntityController::class);
        });

    });

});