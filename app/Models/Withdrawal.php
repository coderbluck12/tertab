<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_name',
        'account_number',
        'account_name',
        'amount',
        'withdrawal_reason',
        'status',
        'request_date',
        'processed_date',
        'admin_notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'request_date' => 'datetime',
        'processed_date' => 'datetime'
    ];

    /**
     * Get the user that owns the withdrawal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status badge color for display.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'completed' => 'blue',
            'rejected' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the formatted amount.
     */
    public function getFormattedAmountAttribute()
    {
        return '₦' . number_format($this->amount, 2);
    }
}
