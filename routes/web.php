<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 

use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DispositionController;
use App\Http\Middleware\ValidateDispositionMagicLink;
use App\Http\Controllers\RequiredDocumentController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HelpController;

use App\Models\DocumentRequest;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\AuditTrail;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PortalController;

Route::get('/', [PortalController::class, 'index'])->name('portal');
Route::get('/bantuan', [HelpController::class, 'index'])->name('help.index');
Route::get('/layanan/skbt', [ServiceRequestController::class, 'showSkbtLandingPage'])->name('skbt.landing');
Route::get('login-skbt', [AuthenticatedSessionController::class, 'createSkbtLogin'])
                ->middleware('guest')
                ->name('login.skbt');

Route::get('register', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register');

Route::post('register', [RegisteredUserController::class, 'store'])
                ->middleware('guest');

Route::get('/dashboard', function () {
    $user = Auth::user();
    $viewData = [];

    // Data default untuk semua pengguna internal
    $viewData['recentInternalDocuments'] = Document::with('category')
        ->whereNull('document_request_id')
        ->latest()->take(5)->get();
    
    $viewData['userActivities'] = AuditTrail::where('user_id', $user->id)
        ->latest()->take(10)->get();

    // Data khusus untuk Admin & Pejabat Struktural
    if ($user->can('isAdmin') || $user->role->name === 'Pejabat Struktural') {
        // Statistik Internal
        $viewData['internalDocsCount'] = Document::whereNull('document_request_id')->count();
        $viewData['internalCatsCount'] = DocumentCategory::where('scope', 'internal')->count();
        $viewData['internalReviewCount'] = Document::whereNull('document_request_id')->where('status', 'Menunggu Review')->count();
        
        // Statistik Klien
        $viewData['totalRequestsCount'] = DocumentRequest::count();
        $viewData['externalDocsCount'] = Document::whereNotNull('document_request_id')->count();
        $viewData['externalReviewCount'] = Document::whereNotNull('document_request_id')->where('status', 'Menunggu Review')->count();

        // Log Aktivitas Seluruh Pengguna (Hanya untuk Admin)
        if ($user->can('isAdmin')) {
             $viewData['allActivities'] = AuditTrail::with('user')->latest()->take(10)->get();
        }
    }

    return view('dashboard', $viewData);

})->middleware(['auth', 'verified', 'can:isInternalUser'])->name('dashboard');

// LETAKKAN ROUTE INI DI LUAR GRUP AUTH
Route::get('/disposition-response/{token}', [DispositionController::class, 'showViaMagicLink'])
    ->middleware('signed')
    ->name('dispositions.respond.magic');
    // Route untuk menyimpan respons dari halaman publik
Route::post('/disposition-response/store', [DispositionController::class, 'storePublicResponse'])->name('dispositions.respond.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk semua pengguna yang sudah login
    Route::resource('documents', DocumentController::class)->except(['destroy']);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/{document}/dispositions', [DispositionController::class, 'store'])->name('dispositions.store');
    Route::get('/dispositions', [DispositionController::class, 'index'])->name('dispositions.index');
    Route::patch('/dispositions/{disposition}/complete', [DispositionController::class, 'markAsCompleted'])->name('dispositions.complete');
    Route::get('/dispositions/{disposition}', [DispositionController::class, 'show'])->name('dispositions.show');
    Route::post('/dispositions/{disposition}/responses', [DispositionController::class, 'storeResponse'])->name('dispositions.responses.store');
    Route::get('/dispositions-sent', [DispositionController::class, 'sent'])->name('dispositions.sent');

    // Route untuk fitur permohonan bebas temuan
    Route::get('/layanan/bebas-temuan/create', [ServiceRequestController::class, 'create'])->name('service-requests.create');
    Route::post('/layanan/bebas-temuan', [ServiceRequestController::class, 'store'])->name('service-requests.store');
    Route::post('/service-requests/{serviceRequest}/add-revision', [ServiceRequestController::class, 'addRevision'])->name('service-requests.addRevision');
    Route::post('/service-requests/{serviceRequest}/submit-revision', [ServiceRequestController::class, 'submitRevision'])->name('service-requests.submitRevision');
    Route::resource('service-requests', ServiceRequestController::class)->only([
        'index', 'show', 'create', 'store'
    ]);

    // Grup untuk semua yang berhubungan dengan Service Request
    Route::prefix('service-requests')->name('service-requests.')->group(function () {
        
        // Hanya yang punya izin 'view' bisa melihat daftar & detail
        Route::get('/', [ServiceRequestController::class, 'index'])->name('index')->middleware('can:view-service-requests');
        Route::get('/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('show')->middleware('can:view-service-requests');
        
        // Hanya yang punya izin 'process' bisa melakukan revisi & approve
        Route::post('/{serviceRequest}/add-revision', [ServiceRequestController::class, 'addRevision'])->name('addRevision')->middleware('can:process-service-requests');
        Route::post('/{serviceRequest}/approve', [ServiceRequestController::class, 'approveRequest'])->name('approve')->middleware('can:process-service-requests');
        
        // Route untuk pemohon tidak perlu diubah karena sudah dicek di dalam controller
        Route::get('/create', [ServiceRequestController::class, 'create'])->name('create');
        Route::post('/', [ServiceRequestController::class, 'store'])->name('store');
    });
    
    // ROUTE UNTUK KLIEN EKSTERNAL
    Route::middleware(['auth', 'can:isClient'])->group(function () {
        Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
        Route::get('/client/requests/{documentRequest}/upload', [ClientDashboardController::class, 'createDocumentForRequest'])->name('client.requests.documents.create');
        Route::post('/client/requests/{documentRequest}/upload', [ClientDashboardController::class, 'storeDocumentForRequest'])->name('client.requests.documents.store');
    });
    
    // ROUTE KHUSUS UNTUK ADMIN
    Route::middleware('can:isAdmin')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('document-categories', DocumentCategoryController::class);
        Route::resource('users', UserController::class);
        Route::resource('entities', EntityController::class);
        Route::resource('document-requests', DocumentRequestController::class);
        Route::resource('required-documents', RequiredDocumentController::class);
        
        Route::get('/review-documents', [DocumentController::class, 'reviewList'])->name('documents.reviewList');
        Route::patch('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
        Route::patch('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
        Route::delete('/documents/{document}/destroy', [DocumentController::class, 'destroy'])->name('documents.destroy');
        
        Route::get('/document-requests/{documentRequest}/entity/{entity}', [DocumentRequestController::class, 'showEntityUploads'])->name('document-requests.show-entity-uploads');
        Route::get('/client-submissions', [DocumentController::class, 'clientSubmissions'])->name('documents.client_submissions');
        Route::get('/client-submissions/{document}', [DocumentController::class, 'showClientSubmission'])->name('client-submissions.show');
    });
});

require __DIR__.'/auth.php';