<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'handler_user_id',
        'service_type',
        'status',
        'final_document_path',
        'notes',
    ];

    /**
     * Relasi ke pemohon (user eksternal).
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke auditor yang menangani.
     */
    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handler_user_id');
    }

    /**
     * Relasi ke dokumen-dokumen yang diunggah.
     */
    public function uploadedDocuments(): HasMany
    {
        return $this->hasMany(UploadedDocument::class);
    }

    /**
     * Relasi ke riwayat revisi.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(RequestRevision::class);
    }
}