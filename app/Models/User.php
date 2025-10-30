<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'national_id',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function tribunal()
{
    return $this->belongsTo(Tribunal::class);
}

public function department()
{
    return $this->belongsTo(\App\Models\Department::class);
}


public function cases()
{
    return $this->hasMany(CourtCase::class, 'judge_id');
}
}