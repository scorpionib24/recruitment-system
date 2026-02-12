<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon; // سنستخدم مكتبة Carbon للتعامل مع التواريخ

class AttendanceController extends Controller
{
    /**
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
        // 1. التحقق من صحة البيانات الأساسية (الشهر والسنة)
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'status' => 'required|array', // التأكد من أن 'status' مصفوفة
        ]);

        $month = $request->input('month');
        $year = $request->input('year');
        $statuses = $request->input('status');

        // 2. المرور على كل موظف تم إرسال بياناته
        foreach ($statuses as $employeeId => $days) {
            // 3. المرور على كل يوم لهذا الموظف
            foreach ($days as $day => $status) {
                // 4. إنشاء التاريخ الكامل من اليوم والشهر والسنة
                $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');

                // 5. استخدام دالة updateOrCreate()
                // هذه الدالة تبحث عن سجل يطابق الشرط الأول (employee_id, date)
                // إذا وجدته، تقوم بتحديثه بالبيانات في الشرط الثاني.
                // إذا لم تجده، تقوم بإنشاء سجل جديد بالبيانات كلها.
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date'        => $date,
                    ],
                    [
                        'status'      => $status,
                        // يمكنك إضافة حقول أخرى هنا إذا أردت، مثل وقت الحضور والانصراف
                        // 'check_in_time' => ($status == 'present') ? '09:00:00' : null,
                    ]
                );
            }
        }

        // 6. إعادة التوجيه إلى الصفحة السابقة مع رسالة نجاح
        return back()->with('success', 'تم حفظ سجلات الحضور بنجاح.');
    }

}
