<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\BranchController;
use App\Http\Controllers\Dashboard\VacancyController;


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

// =============================================
// ====     روابط لوحة التحكم المحمية      ====
// =============================================

// Middleware('auth') يضمن أن المستخدم يجب أن يكون مسجلاً دخوله لزيارة هذه الروابط
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    
    // رابط لجميع عمليات الفروع
    Route::resource('branches', BranchController::class);

    // الخطوة 2: استثناء 'show' من الـ resource المحمي
    Route::resource('vacancies', VacancyController::class); // ->except(['show']);

});