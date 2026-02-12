<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\BranchController;
use App\Http\Controllers\Dashboard\DepartmentController;
use App\Http\Controllers\Dashboard\VacancyController;
use App\Http\Controllers\Public\ApplicationController;
use App\Http\Controllers\Dashboard\VacancyApplicationController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\AttendanceController;
use App\Http\Controllers\Dashboard\ReportController;

// =============================================
// ====          الروابط العامة            ====
// =============================================


Route::get('/', function () {
    return view('welcome');
});


// after login redirect to home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// رابط لعرض تفاصيل وظيفة معينة (عام، غير محمي)
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show'])->name('vacancies.show');

// رابط لعرض نموذج التقديم لوظيفة معينة
Route::get('/vacancies/{vacancy}/apply', [ApplicationController::class, 'create'])->name('vacancies.apply.create');
// رابط لارسال بيانات نموذج التقديم
Route::post('/vacancies/{vacancy}/apply', [ApplicationController::class, 'store'])->name('vacancies.apply.store');


// (اختياري ولكن جيد) رابط لصفحة "شكراً لك" بعد التقديم
Route::get('/apply/success', function () {
    return "شكراً لك، لقد تم استلام طلبك بنجاح!"; // سنقوم بإنشاء view أفضل لاحقاً
})->name('vacancies.apply.success');



// login routes | reset password routes | logout routes | password reset routes
Auth::routes();



// =============================================
// ====     روابط لوحة التحكم المحمية      ====
// Gate is public thing
// Policy is model thing
// =============================================

// Middleware('auth') يضمن أن المستخدم يجب أن يكون مسجلاً دخوله لزيارة هذه الروابط
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    
    // رابط لجميع عمليات الفروع
    Route::resource('branches', BranchController::class);
    // رابط لجميع عمليات الأقسام
    Route::resource('departments', DepartmentController::class);

    // الخطوة 2: استثناء 'show' من الـ resource المحمي
    //  وظائف CRUD للوظائف
    Route::resource('vacancies', VacancyController::class); // ->except(['show']);

    // روابط إدارة طلبات التقديم للوظائف
    Route::get('/vacancies/{vacancy}/applications', [VacancyApplicationController::class, 'index'])->name('vacancies.applications.index');

    // الرابط الجديد لتحديث حالة الطلب  
    Route::patch('/applications/{application}', [VacancyApplicationController::class, 'updateStatus'])->name('applications.updateStatus');

    // الرابط الجديد لتحديث تقييم الطلب
    Route::patch('/applications/{application}/rating', [VacancyApplicationController::class, 'updateRating'])->name('applications.updateRating');


    
    // رابط لجميع عمليات الموظفين
    Route::resource('employees', EmployeeController::class);


    // روابط وحدة الحضور والانصراف
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');


    // === الرابط الجديد لصفحة التقارير ===
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');





    // =========================================================
    // اختبار إعدادات البريد الإلكتروني 
    // في نهاية ملف routes/web.php
    Route::get('/test-mail-config', function () {
        return config('mail');
    });


});