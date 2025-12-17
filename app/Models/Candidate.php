<?php

namespace App\Models;
use Illuminate\foudation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Candidate extends    Authenticatable
{
    //

    public function vacancies() {
        return $this->belongsToMany(Vacancy::class, 'applications')->withTimestamps();  
    }

}
