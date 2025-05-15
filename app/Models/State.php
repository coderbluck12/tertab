<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function institutions()
    {
        return $this->hasMany(Institution::class);
    }

    public function lecturers()
    {
        return $this->hasMany(User::class, 'state_id');
    }
}
