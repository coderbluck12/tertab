<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'phone',
        'address',
        'password',
        'status',
        'referral_code',
        'referred_by'
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

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function requests()
    {
        return $this->hasMany(Reference::class, 'lecturer_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function attended()
    {
        return $this->hasMany(InstitutionAttended::class);
    }

    public function verificationRequest()
    {
        return $this->hasOne(VerificationRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Get the user who referred this user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Get all users referred by this user
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    /**
     * Get all referral records where this user is the referrer
     */
    public function referralsMade()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Get the referral record where this user was referred
     */
    public function referralReceived()
    {
        return $this->hasOne(Referral::class, 'referred_user_id');
    }

    /**
     * Generate a unique referral code for the user
     */
    public static function generateReferralCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Get referral link
     */
    public function getReferralLinkAttribute()
    {
        return url('/register?ref=' . $this->referral_code);
    }

    /**
     * Get total referral earnings
     */
    public function getTotalReferralEarningsAttribute()
    {
        return $this->referralsMade()->sum('commission_amount');
    }

    /**
     * Get pending referral earnings
     */
    public function getPendingReferralEarningsAttribute()
    {
        return $this->referralsMade()->where('commission_paid', false)->sum('commission_amount');
    }
}
