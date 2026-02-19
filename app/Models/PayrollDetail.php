<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    protected $fillable = [
    'payroll_id',
    'employee_id',
    'base_salary',
    'days_worked',
    'gross_salary',
    'deductions',
    'bonuses',
    'net_salary',
    ];

    /**
     * العلاقة التي تجلب الموظف صاحب هذا السجل.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
