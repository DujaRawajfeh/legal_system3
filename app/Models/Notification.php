<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'case_id',
        'participant_name',
        'method',
        'notified_at',
    ];

    public function case()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

}
