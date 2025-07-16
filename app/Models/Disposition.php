<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'from_user_id',
        //'to_user_id',
        'instructions',
        'status',
        'closing_note',
        'response_token',
        'token_expires_at',
        'token_used_at',
        'on_behalf_of',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // TAMBAHKAN BLOK INI UNTUK MEMBERITAHU LARAVEL
    // BAHWA KOLOM INI ADALAH OBJEK TANGGAL
    protected $casts = [
        'token_expires_at' => 'datetime',
        'token_used_at' => 'datetime',
    ];

    /**
     * Relasi ke user yang disposisinya diwakilkan.
     */
    public function onBehalfOfUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'on_behalf_of');
    }
    
    /**
     * Relasi BARU: Satu disposisi bisa memiliki BANYAK penerima (user).
     */
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'disposition_user');
    }
    
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(DispositionResponse::class);
    }
}