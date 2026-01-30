<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'employee_number',
        'position',
        'branch_id',
        'department_id',
        'hire_date',
        'salary',
        'status',
    ];



    /**
     * العلاقة التي تجلب الفرع الذي ينتمي إليه الموظف.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * العلاقة التي تجلب القسم الذي ينتمي إليه الموظف.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
