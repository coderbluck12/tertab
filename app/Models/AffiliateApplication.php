<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'reason',
        'status',
        'user_id',
        'admin_notes',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user associated with this application
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Approve the application
     */
    public function approve($adminNotes = null)
    {
        $this->update([
            'status' => 'approved',
            'admin_notes' => $adminNotes,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject the application
     */
    public function reject($adminNotes = null)
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $adminNotes,
        ]);
    }

    /**
     * Scope for pending applications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved applications
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected applications
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
