<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View; 
use App\Models\Category;           
use Illuminate\Pagination\Paginator;

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
        URL::forceRootUrl(request()->getSchemeAndHttpHost());
        if (str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
        }
        try {
            View::share('globalCategories', Category::all());
        } catch (\Exception $e) {
            // Đề phòng trường hợp database chưa chạy
        }
        Paginator::useTailwind();
    }
}
