<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrestMemo extends Model
{
    use HasFactory;

    protected $table = 'arrest_memos'; // ← اسم الجدول

    protected $fillable = [
        'case_id',
        'judge_name',
        'detention_duration',
        'detention_reason',
        'detention_center',
        'created_by',
        'participant_name',
        'released',         // ✅ حالة الإفراج 
    ];

    // علاقة مع المستخدم اللي أنشأ المذكرة
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // علاقة اختيارية مع جدول القضايا إذا بدك تربطيه لاحقًا
    // public function case()
    // {
    //     return $this->belongsTo(Case::class, 'case_id', 'id');
    // }
}