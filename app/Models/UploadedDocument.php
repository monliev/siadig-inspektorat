<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'required_document_id',
        'file_path',
        'original_filename',
    ];

    /**
     * Relasi ke permohonan induk.
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Relasi ke jenis dokumen yang disyaratkan.
     */
    public function requirement(): BelongsTo
    {
        return $this->belongsTo(RequiredDocument::class, 'required_document_id');
    }
}