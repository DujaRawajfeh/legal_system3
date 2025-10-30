<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CivilRegistry extends Model
{
    protected $table = 'civil_registry'; // اسم الجدول

    protected $fillable = [
        'national_id',
        'full_name',
        'first_name',
        'father_name',
        'mother_name',
        'grandfather_name',
        'family_name',
        'birth_date',
        'age',
        'gender',
        'marital_status',
        'religion',
        'nationality',
        'place_of_birth',
        'current_address',
        'phone_number',
        'email',
        'occupation',
        'education_level',
        'civil_record_number',
        'record_location',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'age' => 'integer',
    ];
}