<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\BranchController;
use App\Http\Controllers\Dashboard\VacancyController;
use App\Http\Controllers\Public\ApplicationController;
use App\Http\Controllers\Dashboard\VacancyApplicationController;

// =============================================
// ====          الروابط العامة            ====
// =============================================
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




// هذا الـ Route ليس عليه أي middleware، وليس له بادئة URL، واسمه بسيط.
// إنه عام ومتاح للجميع.
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show'])->name('vacancies.show');


// رابط لعرض نموذج التقديم لوظيفة معينة
Route::get('/vacancies/{vacancy}/apply', [ApplicationController::class, 'create'])->name('vacancies.apply.create');

// رابط لاستقبال بيانات نموذج التقديم
Route::post('/vacancies/{vacancy}/apply', [ApplicationController::class, 'store'])->name('vacancies.apply.store');


// (اختياري ولكن جيد) رابط لصفحة "شكراً لك" بعد التقديم
Route::get('/apply/success', function () {
    return "شكراً لك، لقد تم استلام طلبك بنجاح!"; // سنقوم بإنشاء view أفضل لاحقاً
})->name('vacancies.apply.success');



// =============================================
// ====     روابط لوحة التحكم المحمية      ====
// =============================================

// Middleware('auth') يضمن أن المستخدم يجب أن يكون مسجلاً دخوله لزيارة هذه الروابط
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    
    // رابط لجميع عمليات الفروع
    Route::resource('branches', BranchController::class);

    // الخطوة 2: استثناء 'show' من الـ resource المحمي
    Route::resource('vacancies', VacancyController::class); // ->except(['show']);

    Route::get('/vacancies/{vacancy}/applications', [VacancyApplicationController::class, 'index'])->name('vacancies.applications.index');

    // الرابط الجديد لتحديث حالة الطلب  
    Route::patch('/applications/{application}', [VacancyApplicationController::class, 'updateStatus'])->name('applications.updateStatus');

    // الرابط الجديد لتحديث تقييم الطلب
Route::patch('/applications/{application}/rating', [VacancyApplicationController::class, 'updateRating'])->name('applications.updateRating');


});