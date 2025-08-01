<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'entity_id',
        'nip',
        'jabatan',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi BARU: Satu user bisa menerima BANYAK disposisi.
     */
    public function dispositions(): BelongsToMany
    {
        return $this->belongsToMany(Disposition::class, 'disposition_user');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function createdDocumentRequests(): HasMany
    {
        return $this->hasMany(DocumentRequest::class, 'created_by');
    }

    public function sentDispositions(): HasMany
    {
        return $this->hasMany(Disposition::class, 'from_user_id');
    }

     public function dispositionResponses(): HasMany
    {
        return $this->hasMany(DispositionResponse::class);
    }
}