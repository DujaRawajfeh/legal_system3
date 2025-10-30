<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_case_id',
        'type',
        'name',
        'national_id',
        'residence',
        'job',
        'phone',
        'charge',
    ];

    // العلاقة مع القضية
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class);
    }
}
