<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * عرض قائمة بكل الأقسام.
     */
    public function index()
    {
        $departments = Department::latest()->paginate(15);
        return view('dashboard.departments.index', compact('departments'));
    }

    /**
     * عرض نموذج إضافة قسم جديد.
     */
    public function create()
    {
        return view('dashboard.departments.create');
    }

    /**
     * حفظ قسم جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        Department::create($validatedData);

        return redirect()->route('dashboard.departments.index')
                         ->with('success', 'تمت إضافة القسم بنجاح.');
    }

    /**
     * عرض نموذج تعديل قسم.
     */
    public function edit(Department $department)
    {
        return view('dashboard.departments.edit', compact('department'));
    }

    /**
     * تحديث بيانات قسم في قاعدة البيانات.
     */
    public function update(Request $request, Department $department)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);

        $department->update($validatedData);

        return redirect()->route('dashboard.departments.index')
                         ->with('success', 'تم تحديث القسم بنجاح.');
    }

    /**
     * حذف قسم من قاعدة البيانات.
     */
    public function destroy(Department $department)
    {
        // ملاحظة: في نظام حقيقي، يجب التحقق أولاً من عدم وجود موظفين مرتبطين بهذا القسم قبل حذفه.
        // سنتجاوز هذه النقطة الآن للتبسيط.
        $department->delete();

        return redirect()->route('dashboard.departments.index')
                         ->with('success', 'تم حذف القسم بنجاح.');
    }
}
