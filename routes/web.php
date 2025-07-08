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

use App\Models\DocumentRequest;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\AuditTrail;
use App\Http\Controllers\EntityController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    // ---- Statistik Internal ----
    $internalDocsCount = Document::whereNull('document_request_id')->count();
    $internalCatsCount = DocumentCategory::where('scope', 'internal')->count();
    $internalReviewCount = Document::whereNull('document_request_id')->where('status', 'Menunggu Review')->count();
    
    // ---- Statistik Eksternal (Kiriman Klien) ----
    $totalRequestsCount = DocumentRequest::count(); // <-- DATA BARU
    $externalDocsCount = Document::whereNotNull('document_request_id')->count();
    $externalReviewCount = Document::whereNotNull('document_request_id')->where('status', 'Menunggu Review')->count();

    // ---- Daftar & Aktivitas ----
    $recentDocuments = Document::with('category')->whereNull('document_request_id')->latest()->take(5)->get();
    $userActivities = AuditTrail::where('user_id', Auth::id())->latest()->take(5)->get();

    return view('dashboard', compact(
        'internalDocsCount',
        'internalCatsCount',
        'internalReviewCount',
        'totalRequestsCount', // <-- Kirim data baru
        'externalDocsCount',
        'externalReviewCount',
        'recentDocuments',
        'userActivities'
    ));
})->middleware(['auth', 'verified', 'can:isInternalUser'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk semua pengguna yang sudah login
    Route::resource('documents', DocumentController::class)->except(['destroy']);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

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