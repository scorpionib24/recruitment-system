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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // المسمى الوظيفي
            $table->longText('description'); // الوصف الكامل
            $table->longText('requirements'); // المتطلبات
            $table->foreignId('branch_id')->constrained('branches'); // علاقة مع الفروع
            $table->foreignId('user_id')->constrained('users'); // مسؤول التوظيف الذي نشرها
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->date('deadline')->nullable(); // آخر موعد للتقديم
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
