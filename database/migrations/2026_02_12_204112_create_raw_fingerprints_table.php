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
        Schema::create('raw_fingerprints', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number'); // الرقم الوظيفي
            $table->timestamp('punch_time');   // وقت البصمة
            $table->boolean('is_processed')->default(false); // هل تمت معالجة هذا السجل؟
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_fingerprints');
    }
};
