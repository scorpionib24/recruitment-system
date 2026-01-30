<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\Department;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //    استخدمنا with('branch') لجلب بيانات الفرع المرتبط بكل موظف بكفاءة (Eager Loading)
        $employees = Employee::with('branch', 'department')->latest()->paginate(15);
        return view('dashboard.employees.index', compact('employees',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $branches = Branch::all();
         $departments = Department::all();
        return view('dashboard.employees.create', compact('branches', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // 1. التحقق من صحة البيانات
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'employee_number' => 'required|string|unique:employees,employee_number',
            'position' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'department_id' =>'nullable|exists:departments,id',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,on_leave',
        ]);

        // 2. إنشاء الموظف الجديد
        Employee::create($validatedData);

        // 3. إعادة التوجيه إلى صفحة القائمة مع رسالة نجاح
        return redirect()->route('dashboard.employees.index')
                        ->with('success', 'تمت إضافة الموظف بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(Employee $employee)
    {
        $branches = Branch::all();
        $departments = Department::all();
        return view('dashboard.employees.edit', compact('employee', 'branches', 'departments'));
    }


   public function update(Request $request, Employee $employee)
    {
        // 1. التحقق من صحة البيانات (مع استثناء الموظف الحالي من قاعدة unique)
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'employee_number' => 'required|string|unique:employees,employee_number,' . $employee->id,
            'position' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'nullable|exists:departments,id',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,on_leave',
        ]);

        // 2. تحديث بيانات الموظف
        $employee->update($validatedData);

        // 3. إعادة التوجيه إلى صفحة القائمة مع رسالة نجاح
        return redirect()->route('dashboard.employees.index')
                        ->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }


    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('dashboard.employees.index')
                        ->with('success', 'تم حذف الموظف بنجاح.');
    }

}
