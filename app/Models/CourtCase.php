<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourtCase extends Model
{

    use HasFactory;

    protected $fillable = [
        'type',
        'number',
        'year',
        'tribunal_id',
        'department_id',
        'created_by',
        'judge_id',
    ];
     protected $table = 'court_cases';
    // المحكمة المرتبطة
    public function tribunal()
    {
        return $this->belongsTo(Tribunal::class, 'tribunal_id');
    }

    // القلم المرتبط
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // المستخدم الذي أنشأ القضية
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // الأطراف المرتبطة بالقضية (نضيف جدول participants لاحقًا)
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function judge()
{
    return $this->belongsTo(User::class, 'judge_id');
}

public function session()
{
    return $this->hasOne(CaseSession::class);
}

public function transfer()
{
    return $this->hasOne(CaseTransfer::class, 'target_case_id');
}
}

