<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    // هذا السطر مهم جداً للسماح بالإدخال الجماعي
    protected $fillable = ['name', 'city'];


    /**
     * علاقة لجلب كل الوظائف الشاغرة التابعة لهذا الفرع.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }


    /**
     * علاقة لجلب كل طلبات التقديم التابعة لهذا الفرع
     * من خلال جدول الوظائف الشاغرة.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function applications()
    {
        // نحن نقول: "أريد الوصول إلى Application من خلال Vacancy"
        return $this->hasManyThrough(Application::class, Vacancy::class);
    }


}