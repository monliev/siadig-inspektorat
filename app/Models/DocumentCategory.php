<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    use HasFactory;

    // Izinkan kolom ini untuk diisi
    protected $fillable = ['name', 'description', 'scope'];
}