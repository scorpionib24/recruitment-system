<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use App\Models\Branch;



class ReportController extends Controller
{
    //
    public function index()
    {
        // ==================================================
        // 1. تقرير قمع التوظيف (Recruitment Funnel Report)
        // ==================================================
        $vacanciesReport = Vacancy::with('branch')->withCount([
                // عد كل المتقدمين لهذه الوظيفة
                'applications as total_applicants',
                // عد المتقدمين في كل مرحلة على حدة
                'applications as new_applicants' => function ($query) {
                    $query->where('stage', 'new');
                },
                'applications as screening_applicants' => function ($query) {
                    $query->where('stage', 'screening');
                },
                'applications as interview_applicants' => function ($query) {
                    $query->where('stage', 'interview');
                },
                'applications as offer_applicants' => function ($query) {
                    $query->where('stage', 'offer');
                },
                'applications as hired_applicants' => function ($query) {
                    $query->where('stage', 'hired');
                },
                'applications as rejected_applicants' => function ($query) {
                    $query->where('stage', 'rejected');
                },
            ])
            ->latest() // عرض أحدث الوظائف أولاً
            ->get();

            
            // ==================================================
            // 2. تقرير أداء الفروع (Branches Performance Report)
            // ==================================================
            $branchesReport = Branch::withCount([
                // عد كل الوظائف التابعة لهذا الفرع
                'vacancies as total_vacancies',

            // عد كل من تم توظيفهم في هذا الفرع
            // (نحتاج إلى علاقة جديدة لهذا، سنقوم بإنشائها)
            'applications as total_hired' => function ($query) {
                $query->where('stage', 'hired');
            }
            ])
            ->get();


            // 3. إرسال البيانات إلى الواجهة
            return view('dashboard.reports.index', compact(
                'vacanciesReport',
                'branchesReport'
            ));
    }

}
