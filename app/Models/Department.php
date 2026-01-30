<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];

    /**
 * العلاقة التي تجلب كل الموظفين في هذا القسم.
 */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
