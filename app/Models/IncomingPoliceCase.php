<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingPoliceCase extends Model
{
    protected $table = 'incoming_police_cases';

    protected $fillable = [
        'center_name',
        'police_case_number',
        'police_registration_date',
        'crime_date',
        'status',
        'case_type',

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

        'tribunal_id',
        'department_id',
    ];

    // العلاقة مع القلم
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // العلاقة مع المحكمة
    public function tribunal()
    {
        return $this->belongsTo(Tribunal::class);
    }
}
