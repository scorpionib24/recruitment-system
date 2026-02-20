<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use \DB;
use Carbon\Carbon; // سنستخدم مكتبة Carbon للتعامل مع التواريخ

class AttendanceController extends Controller
{
    /**
     * مشكلة تسجيل الحضور لكل الايام وعدم تلوين الايام بالاخضر للحضور
     * عرض سجل الحضور للشهر المحدد.
     */
    public function index(Request $request)
    {
        // 1. تحديد الشهر والسنة المستهدفة
        // إذا لم يرسل المستخدم شهراً وسنة، استخدم الشهر والسنة الحاليين
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // 2. إنشاء كائن تاريخ للشهر المحدد
        $date = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $date->daysInMonth;
        $monthName = $date->translatedFormat('F'); // اسم الشهر (مثلاً: يناير)

        // 3. جلب كل الموظفين النشطين
        $employees = Employee::where('status', 'active')->get();

        // 4. جلب سجلات الحضور للشهر المحدد وتنظيمها
        // keyBy() لتسهيل الوصول إلى سجل الحضور لكل موظف في يوم معين
        $attendances = Attendance::whereYear('date', $year)
                                 ->whereMonth('date', $month)
                                 ->get()
                                 ->keyBy(function ($item) {
                                     return $item->employee_id . '-' . Carbon::parse($item->date)->day;
                                 });

        // 5. إرسال كل البيانات إلى الواجهة
        return view('dashboard.attendance.index', compact(
            'employees',
            'attendances',
            'daysInMonth',
            'month',
            'year',
            'monthName'
        ));
    }

    /**
     * حفظ أو تحديث سجلات الحضور.
     */
    /**
     * حفظ أو تحديث سجلات الحضور.
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'employees' => 'required|array',
        ]);

        $month = $validated['month'];
        $year = $validated['year'];

        try {
            DB::beginTransaction();

            // --- الخطوة الجديدة: مسح السجلات القديمة ---
            // 1. احصل على قائمة ID الموظفين القادمين من النموذج
            $employeeIds = array_keys($validated['employees']);

            // 2. احذف كل سجلات الحضور لهؤلاء الموظفين في الشهر والسنة المحددين
            Attendance::whereIn('employee_id', $employeeIds)
                        ->whereYear('date', $year)
                        ->whereMonth('date', $month)
                        ->delete();
            // -----------------------------------------

            // 3. المرور على البيانات الجديدة وحفظها
            foreach ($validated['employees'] as $employeeId => $statuses) {
                foreach ($statuses as $date => $status) {
                    // الآن، بما أننا مسحنا القديم، يمكننا إنشاء الجديد مباشرة
                    // نحن نثق أن النموذج يرسل حالة لكل يوم
                    Attendance::create([
                        'employee_id' => $employeeId,
                        'date' => $date,
                        'status' => $status,
                    ]);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حفظ البيانات.');
        }

        return back()->with('success', 'تم حفظ سجل الحضور بنجاح.');
    }


}
