<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditTrail extends Model
{
    use HasFactory;

    /**
     * Menentukan nama tabel secara eksplisit karena tidak sesuai konvensi standar Laravel.
     */
    protected $table = 'audit_trails';

    /**
     * Laravel tidak akan mencoba mengisi kolom 'updated_at'.
     */
    const UPDATED_AT = null;

    /**
     * Kolom yang diizinkan untuk diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'document_id',
        'action',
        'description',
        'ip_address',
    ];

    /**
     * Relasi ke User yang melakukan aktivitas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}