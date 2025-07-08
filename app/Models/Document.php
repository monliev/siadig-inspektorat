<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'document_number',
        'document_date',
        'description',
        'original_filename',
        'stored_path',
        'file_size',
        'uploaded_by',
        'status',
        'classification',
        'physical_location_building',
        'physical_location_cabinet',
        'physical_location_rack',
        'physical_location_box',
        'document_request_id',
        'approved_by',
    ];

    /**
     * Relasi ke DocumentCategory Model
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    /**
     * Relasi ke User Model (sebagai pengunggah)
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function documentRequest(): BelongsTo
    {
        return $this->belongsTo(DocumentRequest::class);
    }
}