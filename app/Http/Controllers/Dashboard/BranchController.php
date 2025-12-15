<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $branches = Branch::latest()->paginate(10); // latest() لترتيبها من الأحدث للأقدم

        // 2. أرسل البيانات إلى ملف الـ view
        return view('dashboard.branches.index', compact('branches'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         // هذه الدالة وظيفتها فقط عرض صفحة النموذج
        return view('dashboard.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validatedData = $request->validate([
            // 'name' must be unique in the 'branches' table for the given 'city'
            'name' => 'required|string|max:255|unique:branches,name,NULL,id,city,' . $request->city,
            // 'name' => 'required|string|max:255|unique:branches,name',
            'city' => 'required|string|max:255',
        ]);

         // 2. إنشاء سجل جديد في قاعدة البيانات
         Branch::create($validatedData);

         // 3. إعادة توجيه المستخدم إلى صفحة الفروع مع رسالة نجاح
        return redirect()->route('dashboard.branches.index')
                     ->with('success', 'تمت إضافة الفرع بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        // Laravel سيجد الفرع تلقائياً ويرسله إلى الـ view
        return view('dashboard.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        // 1. التحقق من صحة البيانات (Validation)
        $request->validate([
        // 'name' must be unique, but ignore the current branch's ID
        'name' => 'required|string|max:255|unique:branches,name,' . $branch->id,
        'city' => 'required|string|max:255',
        ]);

        // 2. تحديث السجل في قاعدة البيانات
        $branch->update($validatedData);

        // 3. إعادة توجيه المستخدم إلى صفحة الفروع مع رسالة نجاح
        return redirect()->route('dashboard.branches.index')
                        ->with('success', 'تم تحديث الفرع بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        // 1. قم بحذف الفرع من قاعدة البيانات
        // Laravel سيقوم تلقائياً بإيجاد الفرع من الـ ID الموجود في الرابط (Route Model Binding)
        $branch->delete();

        // التأكد من عدم وجود علاقات مع الجدول قبل الحذف 

        // 2. أعد توجيه المستخدم إلى صفحة الفروع مع رسالة نجاح
        return redirect()->route('dashboard.branches.index')
                     ->with('success', 'تم حذف الفرع بنجاح.');

    }
}
