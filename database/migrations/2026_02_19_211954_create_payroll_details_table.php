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
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade'); // مربوط بالمسير
            $table->foreignId('employee_id')->constrained('employees'); // مربوط بالموظف

            // --- تفاصيل الراتب ---
            $table->decimal('base_salary', 10, 2); // الراتب الأساسي وقتها
            $table->integer('days_worked')->default(0); // أيام العمل الفعلية
            $table->decimal('gross_salary', 10, 2); // الراتب الإجمالي (بعد حساب أيام العمل)
            $table->decimal('deductions', 10, 2)->default(0); // إجمالي الخصومات
            $table->decimal('bonuses', 10, 2)->default(0); // إجمالي الحوافز
            $table->decimal('net_salary', 10, 2); // صافي الراتب (الذي سيتم دفعه)

            $table->timestamps();

            // قيد فريد لمنع تكرار نفس الموظف في نفس المسير
            $table->unique(['payroll_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};
