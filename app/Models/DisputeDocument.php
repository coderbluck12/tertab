<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisputeDocument extends Model
{
    protected $fillable = [
        'dispute_id',
        'user_id',
        'file_path',
        'original_name',
        'file_type',
        'file_size',
    ];

    /**
     * Get the dispute that owns the document.
     */
    public function dispute(): BelongsTo
    {
        return $this->belongsTo(Dispute::class);
    }

    /**
     * Get the user who uploaded the document.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full file path for the document.
     */
    public function getFullPathAttribute(): string
    {
        return storage_path('app/' . $this->file_path);
    }

    /**
     * Get the file URL for downloading.
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('dispute.documents.download', $this->id);
    }
}
