<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// --- أضف هذه النماذج ---
use App\Models\RawFingerprint; // أو أي اسم لنموذج البصمات الخام
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class ProcessFingerprints extends Command
{
    protected $signature = 'app:process-fingerprints';
    protected $description = 'Process raw fingerprint data and update attendance records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to process fingerprints...');

        // 1. جلب كل السجلات "غير المعالجة"
        $rawPunches = RawFingerprint::where('is_processed', false)->orderBy('punch_time')->get();

        if ($rawPunches->isEmpty()) {
            $this->info('No new fingerprints to process.');
            return;
        }

        foreach ($rawPunches as $punch) {
            // 2. تحديد تاريخ البصمة والموظف
            $punchTime = Carbon::parse($punch->punch_time);
            $punchDate = $punchTime->toDateString(); // 'Y-m-d'
            $employee = Employee::where('employee_number', $punch->employee_number)->first();

            if (!$employee) {
                $this->warn("Employee with number {$punch->employee_number} not found. Skipping.");
                $punch->update(['is_processed' => true]); // تحديثه لتجنب معالجته مرة أخرى
                continue;
            }

            // 3. البحث عن سجل حضور أو إنشائه
            $attendance = Attendance::firstOrCreate(
                [
                    'employee_id' => $employee->id,
                    'date'        => $punchDate,
                ],
                [
                    // قيم افتراضية إذا تم إنشاء سجل جديد
                    'status'        => 'present',
                    'check_in_time' => $punchTime->toTimeString(), // 'H:i:s'
                ]
            );

            // 4. تحديث وقت الخروج (دائماً)
            // هذا يضمن أن آخر بصمة في اليوم هي وقت الخروج
            $attendance->check_out_time = $punchTime->toTimeString();
            $attendance->save();

            // 5. تحديث سجل البصمة الخام
            $punch->is_processed = true;
            $punch->save();

            $this->info("Processed punch for employee #{$employee->employee_number} at {$punch->punch_time}");
        }

        $this->info('Finished processing fingerprints.');
    }
}
