<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionAttended extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'state_id', 'institution_id', 'type', 'field_of_study', 'position', 'start_date', 'end_date'];

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
}
