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
        // 🌟 1. LẤY LINK HIỆN TẠI MÀ BẠN/NGƯỜI DÙNG ĐANG TRUY CẬP
        $currentUrl = request()->getSchemeAndHttpHost();

        // 🌟 2. ÉP LARAVEL QUÊN FILE .ENV ĐI VÀ DÙNG LINK HIỆN TẠI
        config(['app.url' => $currentUrl]);
        URL::forceRootUrl($currentUrl);

        // 🌟 3. ÉP HTTPS NẾU ĐANG DÙNG NGROK (Để không vỡ giao diện)
        if (str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
        }

        // --- Các phần dưới giữ nguyên ---
        try {
            \Illuminate\Support\Facades\View::share('globalCategories', \App\Models\Category::all());
        } catch (\Exception $e) {}

        \Illuminate\Pagination\Paginator::useTailwind();
    }
}