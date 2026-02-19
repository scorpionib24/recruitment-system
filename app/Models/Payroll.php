<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
    'year',
    'month',
    'status',
    'processed_by',
    'processed_at',
    ];

    /**
     * العلاقة التي تجلب كل تفاصيل هذا المسير.
     */
    public function details()
    {
        return $this->hasMany(PayrollDetail::class);
    }
}
