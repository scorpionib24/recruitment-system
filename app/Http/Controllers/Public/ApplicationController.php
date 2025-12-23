<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // سنحتاج هذا لإدارة العمليات المعقدة

class ApplicationController extends Controller
{
    /**
     * عرض صفحة نموذج التقديم.
     */
    public function create(Vacancy $vacancy)
    {
        // نرسل بيانات الوظيفة إلى الواجهة لعرض اسمها
        return view('public.applications.create', compact('vacancy'));
    }

    /**
     * استقبال وحفظ بيانات طلب التقديم.
     */
    public function store(Request $request, Vacancy $vacancy)
    {
        // 1. التحقق من صحة البيانات المدخلة
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048', // 2MB max
        ]);

        // استخدام Transaction لضمان تنفيذ كل العمليات أو لا شيء
        try {
            DB::beginTransaction();

            // 2. التحقق مما إذا كان المتقدم موجوداً بالفعل أم لا
            $candidate = Candidate::firstOrCreate(
                ['email' => $validatedData['email']], // الشرط للبحث
                [ // البيانات التي سيتم استخدامها إذا تم إنشاء سجل جديد
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'phone' => $validatedData['phone'],
                    'resume_path' => '', // سيتم تحديثه لاحقاً
                ]
            );

            // 3. التحقق مما إذا كان المتقدم قد قدم على هذه الوظيفة من قبل
            if ($candidate->vacancies()->where('vacancy_id', $vacancy->id)->exists()) {
                // إذا كان قد قدم، أعده برسالة خطأ
                return back()->with('error', 'لقد قمت بالتقديم على هذه الوظيفة من قبل.');
            }

            // 4. حفظ ملف السيرة الذاتية
            $resumePath = $request->file('resume')->store('resumes', 'public');
            
            // تحديث مسار السيرة الذاتية للمتقدم
            $candidate->update(['resume_path' => $resumePath]);

            // 5. ربط المتقدم بالوظيفة في الجدول الوسيط
            $candidate->vacancies()->attach($vacancy->id, [
                'stage' => 'new',
                'applied_at' => now(),
            ]);

            DB::commit(); // تأكيد كل العمليات

        } catch (\Exception $e) {
            DB::rollBack(); // تراجع عن كل العمليات في حال حدوث أي خطأ
            // يمكنك هنا تسجيل الخطأ للمراجعة لاحقاً
            // Log::error($e->getMessage());
            // return back()->with('error', $e->getMessage() );
            return back()->with('error', 'حدث خطأ غير متوقع أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.');
        }

        // 6. إعادة توجيه المستخدم إلى صفحة النجاح
        return redirect()->route('vacancies.apply.success');
    }

      

    
}
