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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->year('year'); // سنة المسير
            $table->tinyInteger('month'); // شهر المسير (1-12)
            $table->enum('status', ['draft', 'processed', 'paid'])->default('draft'); // حالة المسير
            $table->foreignId('processed_by')->nullable()->constrained('users'); // من قام بمعالجة المسير
            $table->timestamp('processed_at')->nullable(); // متى تمت المعالجة
            $table->timestamps();

            // قيد فريد لمنع إنشاء مسيرين لنفس الشهر والسنة
            $table->unique(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
