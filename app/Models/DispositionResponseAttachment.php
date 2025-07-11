<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DispositionResponseAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['disposition_response_id', 'file_path', 'original_filename'];

    /**
     * Relasi ke tanggapan induk.
     */
    public function dispositionResponse(): BelongsTo
    {
        return $this->belongsTo(DispositionResponse::class);
    }
}