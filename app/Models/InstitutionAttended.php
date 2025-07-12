<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InstitutionAttended extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'state_id', 
        'institution_id', 
        'type', 
        'field_of_study', 
        'position', 
        'start_date', 
        'end_date',
        'school_email',
        'email_verified_at',
        'email_verification_token',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'email_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Check if the institution is verified
     */
    public function isVerified(): bool
    {
        return $this->status === 'verified' && $this->email_verified_at !== null;
    }

    /**
     * Check if the institution is pending verification
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Generate a verification token
     */
    public function generateVerificationToken(): string
    {
        $token = Str::random(64);
        $this->update(['email_verification_token' => $token]);
        return $token;
    }

    /**
     * Mark the institution as verified
     */
    public function markAsVerified(): void
    {
        $this->update([
            'status' => 'verified',
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);
    }

    /**
     * Check if the school email is valid (.edu or .edu.ng domain)
     */
    public function hasValidSchoolEmail(): bool
    {
        return $this->school_email && (str_ends_with($this->school_email, '.edu') || str_ends_with($this->school_email, '.edu.ng'));
    }
}
