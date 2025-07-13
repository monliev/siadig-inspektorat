<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'auditor_user_id',
        'notes',
    ];

    /**
     * Relasi ke permohonan induk.
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Relasi ke auditor yang memberi catatan.
     */
    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_user_id');
    }
}