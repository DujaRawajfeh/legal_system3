<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'source_case_id',
        'target_case_id',
        'transferred_by',
        'transferred_at',
    ];

    // العلاقة مع القضية الأصلية (شرطة)
    public function sourceCase()
    {
        return $this->belongsTo(IncomingPoliceCase::class, 'source_case_id');
    }

    // العلاقة مع القضية القضائية
    public function targetCase()
    {
        return $this->belongsTo(CourtCase::class, 'target_case_id');
    }

    // العلاقة مع المستخدم اللي نفذ التحويل
    public function user()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
}