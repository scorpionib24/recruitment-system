<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // $this->registerPolicies();

            // صلاحية للمدير فقط
            // Gate::define('access-admin-panel', function (User $user) {
            //     return $user->role === 'admin';
            // });

            // صلاحية لمسؤول التوظيف والمدير
            // Gate::define('manage-jobs', function (User $user) {
            //     return in_array($user->role, ['admin', 'recruiter']);
            // });


    }
}
