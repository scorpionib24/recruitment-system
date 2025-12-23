<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model // <-- الخطوة 1: يرث من Model مباشرة
{
    use HasFactory;

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
    return $this->belongsToMany(Vacancy::class, 'applications')
                ->using(Application::class) // <-- نفس السطر المهم
                ->withPivot('stage', 'rating', 'applied_at')
                ->withTimestamps();
}

}
