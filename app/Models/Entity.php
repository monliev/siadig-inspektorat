<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'parent_id', 'agency_code'];

    /**
     * Relasi ke entitas induk (parent).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'parent_id');
    }

    /**
     * Relasi ke entitas anak (children).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Entity::class, 'parent_id');
    }
    
    /**
     * Relasi ke pengguna (users).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function documentRequests(): BelongsToMany
    {
        return $this->belongsToMany(DocumentRequest::class, 'document_request_entity');
    }
}