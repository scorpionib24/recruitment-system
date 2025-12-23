<?php

namespace App\Models;

// غيّر الـ use statement
use Illuminate\Database\Eloquent\Relations\Pivot;

// اجعل الكلاس يرث من Pivot
class Application extends Pivot
{
    // هذا يخبر Laravel أن هذا النموذج لا يستخدم auto-incrementing IDs
    public $incrementing = true;

    protected $table = 'applications'; // تحديد اسم الجدول صراحةً (ممارسة جيدة)

    protected $fillable = [
        'candidate_id',
        'vacancy_id',
        'stage',
        'rating',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    // يمكننا أيضاً تعريف العلاقات العكسية هنا (اختياري ولكنه مفيد)
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }
}
