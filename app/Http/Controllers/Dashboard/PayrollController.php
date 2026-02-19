<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Models\Payroll;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrolls = Payroll::latest()->paginate(15);
        return view('dashboard.payrolls.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.payrolls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات (الشهر والسنة)
        $validated = $request->validate([
            'year' => 'required|integer|min:2020',
            'month' => 'required|integer|between:1,12',
        ]);

        $year = $validated['year'];
        $month = $validated['month'];

        // 2. التحقق مما إذا كان المسير موجوداً بالفعل لهذا الشهر
        $existingPayroll = Payroll::where('year', $year)->where('month', $month)->first();
        if ($existingPayroll) {
            return redirect()->route('dashboard.payrolls.index')
                            ->with('error', "مسير الرواتب لشهر {$month}/{$year} موجود بالفعل.");
        }

        // 3. جلب كل الموظفين النشطين الذين لديهم راتب أساسي
        $employees = Employee::where('status', 'active')->whereNotNull('salary')->where('salary', '>', 0)->get();
        if ($employees->isEmpty()) {
            return redirect()->route('dashboard.payrolls.index')
                            ->with('error', "لا يوجد موظفون نشطون لديهم رواتب مسجلة لإنشاء المسير.");
        }

        // --- بدء محرك الرواتب ---
        try {
            // استخدام Transaction لضمان تنفيذ كل العمليات أو لا شيء
            DB::beginTransaction();

            // 4. إنشاء سجل "مسير الرواتب" الرئيسي
            $payroll = Payroll::create([
                'year' => $year,
                'month' => $month,
                'status' => 'processed', // سنعتبره معالجاً مباشرة
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // 5. المرور على كل موظف لحساب راتبه
            foreach ($employees as $employee) {
                // 6. حساب عدد أيام الحضور الفعلية للموظف في الشهر المحدد
                $daysWorked = Attendance::where('employee_id', $employee->id)
                                        ->whereYear('date', $year)
                                        ->whereMonth('date', $month)
                                        ->where('status', 'present') // فقط الأيام التي تم تسجيله فيها "حاضر"
                                        ->count();

                // 7. حساب الراتب الإجمالي (بناءً على أيام العمل)
                // (هذه معادلة بسيطة، يمكن تعقيدها لاحقاً)
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $grossSalary = ($employee->salary / $daysInMonth) * $daysWorked;

                // 8. حساب صافي الراتب (حالياً هو نفس الإجمالي)
                // (هنا سنضيف منطق الخصومات والحوافز لاحقاً)
                $deductions = 0;
                $bonuses = 0;
                $netSalary = $grossSalary - $deductions + $bonuses;

                // 9. إنشاء سجل تفاصيل الراتب للموظف
                $payroll->details()->create([
                    'employee_id' => $employee->id,
                    'base_salary' => $employee->salary,
                    'days_worked' => $daysWorked,
                    'gross_salary' => round($grossSalary, 2),
                    'deductions' => round($deductions, 2),
                    'bonuses' => round($bonuses, 2),
                    'net_salary' => round($netSalary, 2),
                ]);
            }

            // إذا نجحت كل العمليات، قم بتأكيدها
            DB::commit();

        } catch (\Exception $e) {
            // إذا حدث أي خطأ، تراجع عن كل العمليات
            DB::rollBack();
            // يمكنك تسجيل الخطأ للمراجعة
            // Log::error($e->getMessage());
            return redirect()->route('dashboard.payrolls.index')
                            ->with('error', "حدث خطأ فادح أثناء معالجة مسير الرواتب. لم يتم حفظ أي بيانات.");
        }

        // 10. إعادة التوجيه إلى صفحة تفاصيل المسير الجديد مع رسالة نجاح
        return redirect()->route('dashboard.payrolls.show', $payroll->id)
                        ->with('success', "تم إنشاء ومعالجة مسير الرواتب بنجاح.");
    }


    /**
     * Display the specified resource.
     */
   public function show(Payroll $payroll)
    {
        // استخدام Eager Loading لجلب كل التفاصيل والموظفين المرتبطين بها بكفاءة
        $payroll->load('details.employee');

        // إرسال البيانات إلى الواجهة
        return view('dashboard.payrolls.show', compact('payroll'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
