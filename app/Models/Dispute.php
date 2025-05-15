<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = ['reference_id', 'created_by', 'status'];

    public function messages()
    {
        return $this->hasMany(DisputeMessage::class);
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class);
    }
}
