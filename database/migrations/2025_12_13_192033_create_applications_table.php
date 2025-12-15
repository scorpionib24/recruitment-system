<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates');
            $table->foreignId('vacancy_id')->constrained('vacancies');  
           // (new, screening, interview, offer, rejected, hired)
            $table->string('stage')->default('new');
            // (Qualified, Not Qualified, Qualified with Training)
            $table->string('rating')->nullable();
            $table->timestamps();
             //     // تعديل القيد لمنع التقديم المزدوج
            $table->unique(['candidate_id', 'vacancy_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
