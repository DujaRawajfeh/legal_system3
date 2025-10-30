<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingProsecutorCase extends Model
{
    protected $fillable = [
    'case_number',
    'title',
    'department_id',
    'records',
    'tribunal_id',
    'plaintiff_name',
    'plaintiff_national_id',
    'plaintiff_residence',
    'plaintiff_job',
    'plaintiff_phone',
    'plaintiff_type',
    'defendant_name',
    'defendant_national_id',
    'defendant_residence',
    'defendant_job',
    'defendant_phone',
    'defendant_type',
    'third_party_name',
    'third_party_national_id',
    'third_party_residence',
    'third_party_job',
    'third_party_phone',
    'third_party_type',
];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}