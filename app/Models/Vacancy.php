<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    // app/Models/Vacancy.php
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'branch_id',
        'user_id',
        'status',
        'deadline',
    ];


    // app/Models/Vacancy.php
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    
    /**
     * The attributes that should be cast.
     *
     * هذا هو الكود الجديد الذي يجب إضافته
     * @var array
     */
    protected $casts = [
        'deadline' => 'date',
    ];

    public function candidates() {
        return $this->belongsToMany(Candidate::class, 'applications')->withTimestamps();
    }



}
