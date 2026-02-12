<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'notes',
    ];

    /**
     * العلاقة التي تجلب الموظف المرتبط بسجل الحضور هذا.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
