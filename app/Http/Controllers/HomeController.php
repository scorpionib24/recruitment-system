<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    // في وحدة التحكم الخاصة بالمرشحين
    // if (Auth::guard('candidate')->attempt($credentials)) {
    //     // تم تسجيل الدخول بنجاح
    // }


    // // في الـ Controller
    // if (Gate::denies('access-admin-panel')) {
    //     abort(403);
    // }

    // // في ملفات Blade
    // @can('access-admin-panel')
    //     <a href="/admin">لوحة تحكم المدير</a>
    // @endcan


}
