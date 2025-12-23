<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\Application;
use Illuminate\Http\Request;

class VacancyApplicationController extends Controller
{
    /**
     * دالة (Read - List): عرض قائمة بكل المتقدمين لوظيفة معينة.
     * 
     * المسؤولية:
     * 1. استقبال الوظيفة المطلوبة (باستخدام Route Model Binding).
     * 2. استخدام العلاقة 'candidates' التي عرفناها في نموذج Vacancy لجلب كل المتقدمين المرتبطين.
     * 3. تطبيق Eager Loading لتحميل بيانات المتقدمين بكفاءة وتجنب مشكلة N+1.
     * 4. إرسال بيانات الوظيفة (ومعها قائمة المتقدمين) إلى واجهة العرض لإنشاء الجدول.
     */
    public function index(Request $request, Vacancy $vacancy) // <-- أضفنا Request
    {
        // 1. ابدأ ببناء الاستعلام الأساسي للعلاقة
        $candidatesQuery = $vacancy->candidates(); // لاحظ أننا استدعينا الدالة () للحصول على باني الاستعلام

        // 2. طبق فلتر الحالة (Stage) إذا كان موجوداً في الطلب
        if ($request->filled('stage')) {
            $stage = $request->input('stage');
            // نستخدم wherePivot لتطبيق شرط على الجدول الوسيط
            $candidatesQuery->wherePivot('stage', $stage);
        }

        // 3. طبق فلتر التقييم (Rating) إذا كان موجوداً في الطلب
        if ($request->filled('rating')) {
            $rating = $request->input('rating');
            $candidatesQuery->wherePivot('rating', $rating);
        }

        // 4. قم بترتيب النتائج وتنفيذ الاستعلام مع ترقيم الصفحات (Pagination)
        $candidates = $candidatesQuery->latest('pivot_applied_at')->paginate(15); // 15 متقدم في كل صفحة

        // 5. أرسل البيانات إلى الواجهة
        return view('dashboard.applications.index', compact('vacancy', 'candidates'));
    }

     /*
    |--------------------------------------------------------------------------
    | الدوال المستقبلية (Future Methods)
    |--------------------------------------------------------------------------
    |
    | هنا سنضيف الدوال التي سنتحتاجها في الخطوات التالية، مثل تغيير حالة الطلب.
    |
    */
    
    /**
     * دالة (Update - Status): تغيير حالة طلب تقديم معين.
     * 
     * سيتم استدعاؤها عبر طلب AJAX أو من خلال form لتغيير حالة المتقدم
     * (مثلاً: من 'new' إلى 'shortlisted' أو 'rejected').
     * 
     * public function updateStatus(Request $request, Vacancy $vacancy, Candidate $candidate)
     * {
     *     // 1. التحقق من صحة الحالة الجديدة.
     *     // 2. تحديث حقل 'status' في الجدول الوسيط 'applications'.
     *     //    $vacancy->candidates()->updateExistingPivot($candidate->id, ['status' => $newStatus]);
     *     // 3. إرجاع رد JSON بالنجاح.
     * }
     */

    /**
     * دالة لتحديث حالة طلب التقديم.
     */
    public function updateStatus(Request $request, Application $application)
    {
        // 1. التحقق من صحة البيانات القادمة
        $validated = $request->validate([
            'stage' => 'required|string|in:new,screening,interview,offer,rejected,hired',
        ]);

        // 2. تحديث حالة الطلب
        $application->update([
            'stage' => $validated['stage'],
        ]);

        // 3. إعادة التوجيه إلى الصفحة السابقة مع رسالة نجاح
        return back()->with('success', 'تم تحديث حالة المتقدم بنجاح.');
    }

    /**
     * دالة لتحديث تقييم طلب التقديم.
     */
    // في VacancyApplicationController.php

/**
 * دالة لتحديث تقييم طلب التقديم.
 */
    public function updateRating(Request $request, Application $application)
    {
        // 1. التحقق من صحة البيانات القادمة
        $validated = $request->validate([
            'rating' => 'nullable|string|in:Qualified,Not Qualified,Qualified with Training',
        ]);

        // 2. تحديث تقييم الطلب
        $application->update([
            'rating' => $validated['rating'],
        ]);

        // 3. إرسال رد JSON (مهم لـ AJAX)
        return response()->json(['message' => 'تم تحديث التقييم بنجاح.']);
    }


}
