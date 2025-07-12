<?php

namespace App\Models;

// Ganti 'use Illuminate\Database\Eloquent\Model;' dengan ini:
use Spatie\Permission\Models\Role as SpatieRole;

// Ganti 'class Role extends Model' dengan ini:
class Role extends SpatieRole
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'guard_name', // Pastikan guard_name ada di sini
    ];

}