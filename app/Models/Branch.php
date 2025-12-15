<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    // هذا السطر مهم جداً للسماح بالإدخال الجماعي
    protected $fillable = ['name', 'city'];
}
