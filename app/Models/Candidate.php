<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // <-- تأكد من وجود هذا السطر


class Candidate extends Model // <-- الخطوة 1: يرث من Model مباشرة
{
    use HasFactory, Notifiable; // <-- إضافة Notifiable هنا

    /**
     * الحقول المسموح بتعبئتها في جدول 'candidates'.
     */
    protected $fillable = [ // <-- الخطوة 2: لا يوجد حقل 'password'
        'first_name',
        'last_name',
        'email',
        'phone',
        'resume_path',
    ];

    /**
     * علاقة (Many-to-Many): كل متقدم يمكنه التقديم على عدة وظائف.
     */
   // في app/Models/Candidate.php
    public function vacancies()
    {
        // هذا هو التعريف الصحيح الذي يعمل في كل الحالات
        return $this->belongsToMany(Vacancy::class, 'applications')
                    ->as('application') // نستخدم 'as' بدلاً من 'using'
                    ->withPivot('id', 'stage', 'rating', 'applied_at') // أضفنا 'id' هنا
                    ->withTimestamps();
    }

}
