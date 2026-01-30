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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique(); // الرقم الوظيفي (فريد)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable(); // تاريخ الميلاد

            // --- معلومات وظيفية ---
            $table->foreignId('branch_id')->constrained('branches'); // مربوط بجدول الفروع
            $table->foreignId('department_id')->constrained('departments'); // سنضيفها لاحقاً
            $table->string('position'); // المسمى الوظيفي
            $table->date('hire_date'); // تاريخ التعيين
            $table->decimal('salary', 10, 2)->nullable(); // الراتب (مثال: 10000.00)

            // --- الحالة ---
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active'); // حالة الموظف

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
