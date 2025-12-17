// app/Http/Controllers/Public/ApplicationController.php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\Candidate;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // دالة لعرض نموذج التقديم
    public function create(Vacancy $vacancy)
    {
        return view('public.applications.create', compact('vacancy'));
    }

    // دالة لحفظ بيانات المتقدم
    public function store(Request $request, Vacancy $vacancy)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048', // ملف PDF أو Word، بحد أقصى 2MB
        ]);

        // 1. حفظ السيرة الذاتية (Resume)
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // 2. إنشاء سجل للمرشح (Candidate)
        // سنحتاج لإنشاء نموذج وجدول Candidates
        $candidate = Candidate::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'resume_path' => $resumePath,
        ]);

        // 3. ربط المرشح بالوظيفة التي تقدم لها (علاقة Many-to-Many)
        // سنحتاج لإنشاء جدول وسيط `application` أو `candidate_vacancy`
        $candidate->vacancies()->attach($vacancy->id, [
            'status' => 'new', // الحالة الأولية للطلب
            'applied_at' => now(),
        ]);

        // 4. إعادة توجيه المستخدم إلى صفحة شكر
        return redirect()->route('vacancies.apply.success');
    }
}
