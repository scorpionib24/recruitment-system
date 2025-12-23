<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // يفضل إضافتها
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory; // يفضل إضافتها

    /**
     * الحقول المسموح بتعبئتها بشكل جماعي في جدول 'vacancies'.
     */
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'branch_id',
        // 'user_id', // هذا الحقل غير موجود في جدول vacancies حالياً، يجب إزالته أو إضافته في migration
        'deadline',
    ];

    /**
     * تحديد أنواع البيانات للحقول لضمان التحويل التلقائي.
     */
    protected $casts = [
        'deadline' => 'date', // تحويل حقل 'deadline' إلى كائن تاريخ
    ];

    /**
     * علاقة (Many-to-One): كل وظيفة تنتمي إلى فرع واحد.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * علاقة (Many-to-Many): كل وظيفة يمكن أن يتقدم لها عدة مرشحين.
     */
  // في app/Models/Vacancy.php
public function candidates()
{
    return $this->belongsToMany(Candidate::class, 'applications')
                ->using(Application::class) // <-- هذا هو السطر الجديد والمهم
                ->withPivot('id','stage', 'rating', 'applied_at')
                ->withTimestamps();
}

}
