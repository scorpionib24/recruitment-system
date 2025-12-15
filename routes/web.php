<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\BranchController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// هذا هو الكود الجديد الذي ستضيفه
// سنضع كل روابط لوحة التحكم داخل مجموعة واحدة
// Middleware('auth') يضمن أن المستخدم يجب أن يكون مسجلاً دخوله لزيارة هذه الروابط
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    
    // رابط لجميع عمليات الفروع
    Route::resource('branches', BranchController::class);

});