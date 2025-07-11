<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DispositionResponse extends Model
{
    use HasFactory;

    protected $fillable = ['disposition_id', 'user_id', 'notes'];

    /**
     * Relasi ke disposisi induk.
     */
    public function disposition(): BelongsTo
    {
        return $this->belongsTo(Disposition::class);
    }

    /**
     * Relasi ke pengguna yang memberi tanggapan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke lampiran file.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(DispositionResponseAttachment::class);
    }
}