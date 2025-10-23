<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'reference_id',
        'referral_code',
        'status',
        'commission_amount',
        'reference_amount',
        'commission_paid',
        'completed_at',
        'rewarded_at',
    ];

    protected $casts = [
        'commission_amount' => 'decimal:2',
        'commission_paid' => 'boolean',
        'completed_at' => 'datetime',
        'rewarded_at' => 'datetime',
    ];

    /**
     * Get the user who referred
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the referred user
     */
    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    /**
     * Get the reference request associated with this commission
     */
    public function reference()
    {
        return $this->belongsTo(\App\Models\Reference::class, 'reference_id');
    }

    /**
     * Mark referral as completed
     */
    public function markAsCompleted($commissionAmount = 0)
    {
        $this->update([
            'status' => 'completed',
            'commission_amount' => $commissionAmount,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark referral as rewarded
     */
    public function markAsRewarded()
    {
        $this->update([
            'status' => 'rewarded',
            'commission_paid' => true,
            'rewarded_at' => now(),
        ]);
    }

    /**
     * Scope for pending referrals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed referrals
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for rewarded referrals
     */
    public function scopeRewarded($query)
    {
        return $query->where('status', 'rewarded');
    }
}
