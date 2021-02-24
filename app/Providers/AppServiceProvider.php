<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return bool
     */
    public function boot()
    {
        Blade::if('admin', function () {
            return Auth::user()->role_id == '1';
        });

        Blade::if('applicant', function () {
            return Auth::user()->role_id == '2';
        });
    }
}
