<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Tambahkan ini

class DocumentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'due_date',
        'status',
    ];

    /**
     * Relasi ke user yang membuat permintaan (Admin/Auditor).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke entitas yang dituju (OPD/Desa).
     */
    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'document_request_entity');
    }

    /**
     * Relasi ke dokumen yang diunggah untuk permintaan ini.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}