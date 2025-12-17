<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // Eager Loading: نحمل علاقة 'branch' مع الوظائف لتجنب استعلامات N+1
        $vacancies = Vacancy::with('branch')->latest()->paginate(10);

        return view('dashboard.vacancies.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 1. احصل على جميع الفروع من قاعدة البيانات لعرضها في القائمة المنسدلة
        $branches = Branch::orderBy('name')->get();

        return view('dashboard.vacancies.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id', // تأكد أن الفرع موجود في جدول الفروع
            'description' => 'required|string',
            'requirements' => 'required|string',
            'deadline' => 'nullable|date|after_or_equal:today', // يجب أن يكون تاريخاً بعد اليوم
        ]);

        // 2. أضف user_id الخاص بالموظف الذي أنشأ الوظيفة
        $validatedData['user_id'] = auth()->id();

        // 3. إنشاء سجل جديد في قاعدة البيانات
        Vacancy::create($validatedData);

        // 4. إعادة توجيه المستخدم إلى صفحة الوظائف مع رسالة نجاح
        return redirect()->route('dashboard.vacancies.index')
                        ->with('success', 'تمت إضافة الوظيفة بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacancy $vacancy)
    {
        // نقوم بتحميل علاقة 'branch' مع الوظيفة
        $vacancy->load('branch');

        // نرسل بيانات الوظيفة إلى واجهة العرض العامة
        return view('dashboard.vacancies.show', compact('vacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacancy $vacancy)
    {
          // نحصل على قائمة الفروع لعرضها في القائمة المنسدلة
    $branches = Branch::orderBy('name')->get();
    
         // نرسل الوظيفة الحالية وقائمة الفروع إلى الـ view
        return view('dashboard.vacancies.edit', compact('vacancy', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacancy $vacancy)
    {
          $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'deadline' => 'nullable|date|after_or_equal:today',
          ]);

        $vacancy->update($validatedData);

        return redirect()->route('dashboard.vacancies.index')
                     ->with('success', 'تم تحديث الوظيفة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();

        return redirect()->route('dashboard.vacancies.index')
                     ->with('success', 'تم حذف الوظيفة بنجاح.');
    }
}
