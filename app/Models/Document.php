<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'verification_request_id',
        'institution_attended_id',
        'path',
        'name',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verificationRequest()
    {
        return $this->belongsTo(VerificationRequest::class);
    }

    public function institutionAttended()
    {
        return $this->belongsTo(InstitutionAttended::class);
    }
}
